<?php

namespace App\Controller\UserAdmin;

use App\Controller\AppController as BaseController;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Info;
use App\Lib\Util;
use Cake\I18n\I18n;
use Cake\Utility\Hash;
use Cake\Http\Exception\NotFoundException;
use App\Utils\CustomUtility;
use Cake\Routing\Router;

class AppController extends BaseController
{
    public $helpers = [
        'Paginator' => ['templates' => 'paginator-admin']
    ];

    public function initialize()
    {
        parent::initialize();

        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');

        $this->Session->write('current_site_id', 1);

        $this->loadComponent('AdminMenu');
    }

    // protected function _lists($cond = array(), $options = array()) {
    //     $primary_key = $this->{$this->modelName}->getPrimaryKey();

    //     $options = array_merge(
    //         array('limit' => 10,
    //             'maxLimit' => 200,
    //             'contain' => [],
    //             'order' => [$this->modelName . '.' . 'id' => 'DESC'],
    //             'paramType' => 'querystring',
    //             'conditions' => array(),
    //         ),
    //         $options
    //     );

    //     $options['sortWhitelist'] = array_keys($options['order']);
    //     if ($this->request->getQuery('sort') && $this->request->getQuery('direction')) {
    //         $options['order'] = array(
    //             $this->request->getQuery('sort') => $this->request->getQuery('direction')
    //         );
    //         $options['sortWhitelist'] = [$this->request->getQuery('sort')];
    //     }

    //     $this->paginate = array_merge(
    //         [
    //             'url' => [
    //                 'sort' => null,
    //                 'direction' => null,
    //             ],
    //             'maxLimit' => 999,
    //             'paramType' => 'querystring',
    //             //"sortWhitelist" => array()
    //         ],
    //         $options
    //     );

    //     $sql_query = null;
    //     if (array_key_exists('sql_query', $options)) {
    //         $sql_query = $options['sql_query'];
    //     }

    //     try {
    //         if ($this->paginate['limit'] === null) {
    //             unset($options['limit'],
    //                     $options['paramType']);
    //             if ($cond) {
    //                 $options['conditions'] = $cond;
    //             }
    //             $sql_query = $this->{$this->modelName}->find()->contain($options['contain'])->where($cond)->order($options['order']);
    //             $data_query = $sql_query->all();
    //         } elseif (!is_null($sql_query)) {
    //             $data_query = $this->paginate($sql_query);
    //         } else {
    //             $sql_query = $this->{$this->modelName}->find()->where($cond)->order($options['order']);
    //             $data_query = $this->paginate($sql_query);
    //         }
    //         $datas = $data_query->toArray();
    //         //$count['total'] = $data_query->count();
    //     } catch (NotFoundException $e) {
    //         $query = $this->request->query;
    //         if ($query['page']) {
    //             $query['page'] -= 1;
    //         }
    //         return $this->redirectWithException(array('action' => $this->request->action, '?' => $query));
    //     }

    //     $numrows = $sql_query->count();

    //     $this->set(compact('datas', 'data_query', 'numrows'));
    // }
    protected function _lists($cond = [], $options = [])
    {
        $primary_key = $this->{$this->modelName}->getPrimaryKey();
        $this->paginate = array_merge(
            [
                'order' => [$this->modelName . '.' . $primary_key . ' DESC'],
                'limit' => 5,
                'contain' => [],
                'paramType' => 'querystring',
                'url' => [
                    'sort' => null,
                    'direction' => null
                ]
            ],
            $options
        );

        $sql_query = array_key_exists('sql_query', $options) ? $sql_query = $options['sql_query'] : null;

        try {
            if ($this->paginate['limit'] === null) {

                unset($options['limit'], $options['paramType']);

                if ($cond)
                    $options['conditions'] = $cond;

                $data_query = $this->{$this->modelName}
                    ->find()
                    ->where($cond)
                    ->order($options['order'])
                    ->all();
            } elseif (!is_null($sql_query)) $data_query = $this->paginate($sql_query);
            else $data_query = $this->paginate($this->{$this->modelName}->find()->where($cond));

            $datas = $data_query->toArray();
            $numrows = $this->{$this->modelName}->find()->where($cond)->count();

            $this->set(compact('datas', 'data_query', 'numrows'));
        } catch (NotFoundException $e) {
            if (
                !empty($this->request->query['page'])
                && 1 < $this->request->query['page']
            )
                $this->redirect(array('action' => $this->request->action));
        }
    }


    protected function _edit($id = 0, $option = array())
    {
        $option = array_merge(
            array(
                'saveAll' => false,
                'saveMany' => false,
                'create' => null,
                'callback' => null,
                'redirect' => array('action' => 'index'),
                'contain' => [],
                'success_message' => '保存しました',
                'validate' => 'default',
                'associated' => null,
                'append_validate' => null,
                'get_callback' => null
            ),
            $option
        );
        extract($option);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();

        if (empty($contain) && !empty($associated)) {
            $contain = $associated;
        }

        $isValid = true;

        if (
            $this->request->is(array('post', 'put'))
            && $this->request->getData() //post_max_sizeを越えた場合の対応(空になる)
        ) {
            $entity_options = [];
            if (!empty($associated)) {
                $entity_options['associated'] = $associated;
            }
            if (!empty($validate)) {
                $entity_options['validate'] = $validate;
            }

            $entity = $this->{$this->modelName}->newEntity($this->request->getData(), $entity_options);

            if ($entity->getErrors()) {
                $data = $this->request->getData();
                if (!array_key_exists('id', $data)) {
                    $data['id'] = $id;
                }
                if (property_exists($this->{$this->modelName}, 'useHierarchization') && !empty($this->{$this->modelName}->useHierarchization)) {
                    $vals = $this->{$this->modelName}->useHierarchization;
                    $_model = $vals['sequence_model'];
                    if (!empty($entity[$vals['contents_table']])) {
                        // if (array_key_exists($vals['contents_table'], $entity)) {
                        foreach ($entity[$vals['contents_table']] as $k => $v) {
                            if (empty($v['id'])) {
                                $entity[$vals['contents_table']][$k]['id'] = null;
                            }
                            if ($v[$vals['sequence_id_name']]) {
                                $seq = $this->{$_model}->find()->where([$_model . '.id' => $v[$vals['sequence_id_name']]])->first();
                                $entity[$vals['contents_table']][$k][$vals['sequence_table']] = $seq;
                            }
                        }
                    }
                }
                // pr($entity);exit;

                // TODO::
                // $this->redirect($this->referer());

                $request = $this->getRequest()->withParsedBody($this->{$this->modelName}->toFormData($entity));
                $this->setRequest($request);
                $this->set('data', $data);
                $isValid = false;
            }

            // 追加項目バリデーション
            if ($append_validate) {
                $isValid = $append_validate($isValid, $entity);
            }

            if ($isValid) {
                $r = $this->{$this->modelName}->save($entity);
                if ($r) {
                    if ($success_message) {
                        $this->Flash->set($success_message);
                    }
                    if ($callback) {
                        $callback($entity->id, $entity);
                    }
                    // exit;
                    if ($redirect) {
                        return $this->redirect($redirect);
                    }
                }
            } else {
                $data = $this->request->getData();
                if (!array_key_exists('id', $data)) {
                    $data['id'] = $id;
                }
                $this->set('data', $data);
                $this->Flash->set('正しく入力されていない項目があります');
            }
        } else {
            $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primary_key => $id])->contain($contain);

            if ($create) {
                $request = $this->getRequest()->withParsedBody($create);
                $this->setRequest($request);
                $entity = $this->{$this->modelName}->newEntity($create);
            } elseif (!$query->isEmpty()) {
                $entity = $query->first();
                $request = $this->getRequest()->withParsedBody($this->{$this->modelName}->toFormData($entity));
                $this->setRequest($request);
            } else {
                $entity = $this->{$this->modelName}->newEntity();
                $entity->{$this->{$this->modelName}->getPrimaryKey()} = null;
                $request = $this->getRequest()->withParsedBody($this->{$this->modelName}->toFormData($entity));
                $this->setRequest($request);
                if (property_exists($this->{$this->modelName}, 'defaultValues')) {
                    $request = $this->getRequest()->withParsedBody(array_merge($this->request->getData(), $this->{$this->modelName}->defaultValues));
                    $this->setRequest($request);
                }
            }

            if ($get_callback) {
                $request = $this->getRequest()->withParsedBody($get_callback($this->request->getData()));
                $this->setRequest($request);
            }

            $this->set('data', $this->request->getData());
        }

        if (property_exists($this->{$this->modelName}, 'useHierarchization') && !empty($this->{$this->modelName}->useHierarchization)) {
            $block_waku_list = array_keys(Info::BLOCK_TYPE_WAKU_LIST);
            $contents = $this->toHierarchization($id, $entity, ['section_block_ids' => $block_waku_list]);
            $this->set(array_keys($contents), $contents);
            // pr($contents);exit;
        }

        $this->set('entity', $entity);

        return $isValid;
    }

    public function _detail($id, $option = [])
    {
        $option = array_merge(
            array(
                'callback' => null,
                'redirect' => array('action' => 'index'),
                'contain' => []
            ),
            $option
        );
        extract($option);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();

        $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primary_key => $id])->contain($contain);

        if (!$query->isEmpty()) {
            $entity = $query->first();
            $request = $this->getRequest()->withParsedBody($this->{$this->modelName}->toFormData($entity));
            $this->setRequest($request);
        } else {
            $entity = $this->{$this->modelName}->newEntity();
            $entity->{$this->{$this->modelName}->getPrimaryKey()} = null;
            $request = $this->getRequest()->withParsedBody($this->{$this->modelName}->toFormData($entity));
            $this->setRequest($request);
            if (property_exists($this->{$this->modelName}, 'defaultValues')) {
                $request = $this->getRequest()->withParsedBody(array_merge($this->request->data, $this->{$this->modelName}->defaultValues));
                $this->setRequest($request);
            }
        }

        $this->set('data', $this->request->getData());

        if (property_exists($this->{$this->modelName}, 'useHierarchization') && !empty($this->{$this->modelName}->useHierarchization)) {
            $block_waku_list = array_keys(Info::BLOCK_TYPE_WAKU_LIST);
            $contents = $this->toHierarchization($id, $entity, ['section_block_ids' => $block_waku_list]);
            $this->set(array_keys($contents), $contents);
        }

        $this->set('entity', $entity);
    }

    /**
     * 順番並び替え
     * */
    protected function _position($id, $pos, $options = array())
    {
        $options = array_merge(array(
            'redirect' => array('action' => 'index', '#' => 'content-' . $id)
        ), $options);
        extract($options);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();
        $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primary_key => $id]);

        if (!$query->isEmpty()) {
            // $entity = $this->{$this->modelName}->get($id);
            $this->{$this->modelName}->movePosition($id, $pos);
        }
        if ($redirect) {
            $this->redirect($redirect);
        }
    }

    /*
    順番　入れ替え　ドラッグ用

    保存されているのがこれ
    1 => position3, 2 => position2, 3 => position1

    リクエストされるIDがこれ
    2, 1, 3

    positionを入れ替えてアップデート
    2 => position3, 1 => position2, 3 => position1
    */
    protected function _drag_position()
    {
        $this->autoRender = false;

        $new_positions = $this->request->data['positions'] ?? array();
        if (!$new_positions) {
            return false;
        }

        $cond = array(
            $this->modelName . '.id IN' => $new_positions
        );
        $datas = $this->{$this->modelName}->find()->where($cond)->order([$this->modelName . '.position' => 'DESC'])->toArray();
        $datas = Hash::combine($datas, '{n}.id', '{n}.position');

        $index_ids = array_keys($datas);

        $new_data = array();
        foreach ($index_ids as $id) {
            $new_positions_id = $index_ids[array_search($id, $new_positions)];
            $new_data[] = array(
                'id' => $id,
                'position' => $datas[$new_positions_id]
            );
        }
        $entities = $this->{$this->modelName}->patchEntities($this->{$this->modelName}, $new_data);
        $data = $this->{$this->modelName}->saveMany($entities);

        return true;
    }

    /**
     * 掲載中/下書き トグル
     * */
    protected function _enable($id, $options = array())
    {
        $options = array_merge(array(
            'redirect' => array('action' => 'index', '#' => 'content-' . $id),
            'column' => 'status',
            'status_true' => 'publish',
            'status_false' => 'draft'
        ), $options);
        extract($options);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();
        $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primary_key => $id]);

        if (!$query->isEmpty()) {
            $entity = $query->first();
            $status = ($entity->get($column) == $status_true) ? $status_false : $status_true;
            $this->{$this->modelName}->updateAll(array($column => $status), array($this->{$this->modelName}->getPrimaryKey() => $id));
        }
        if ($redirect) {
            $this->redirect($redirect);
        }
    }

    /**
     * ファイル/記事削除
     *
     * */
    protected function _delete($id, $type, $columns = null, $option = array())
    {
        $option = array_merge(
            array('redirect' => null),
            $option
        );
        extract($option);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();
        $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primary_key => $id]);

        if (!$query->isEmpty() && in_array($type, array('image', 'file', 'content'))) {
            $entity = $query->first();
            $data = $entity->toArray();

            if ($type === 'image' && isset($this->{$this->modelName}->attaches['images'][$columns])) {
                if (!empty($data['attaches'][$columns])) {
                    foreach ($data['attaches'][$columns] as $_) {
                        $_file = WWW_ROOT . $_;
                        if (is_file($_file)) {
                            @unlink($_file);
                        }
                    }
                }
                $this->{$this->modelName}->updateAll(
                    array($columns => ''),
                    array($this->modelName . '.' . $this->{$this->modelName}->getPrimaryKey() => $id)
                );
            } elseif ($type === 'file' && isset($this->{$this->modelName}->attaches['files'][$columns])) {
                if (!empty($data['attaches'][$columns][0])) {
                    $_file = WWW_ROOT . $data['attaches'][$columns][0];
                    if (is_file($_file)) {
                        @unlink($_file);
                    }

                    $this->{$this->modelName}->updateAll(
                        array(
                            $columns => '',
                            $columns . '_name' => '',
                            $columns . '_size' => 0,
                        ),
                        array($this->modelName . '.' . $this->{$this->modelName}->getPrimaryKey() => $id)
                    );
                }
            } elseif ($type === 'content') {
                $image_index = array_keys($this->{$this->modelName}->attaches['images']);
                $file_index = array_keys($this->{$this->modelName}->attaches['files']);

                foreach ($image_index as $idx) {
                    foreach ($data['attaches'][$idx] as $_) {
                        $_file = WWW_ROOT . $_;
                        if (is_file($_file)) {
                            @unlink($_file);
                        }
                    }
                }

                foreach ($file_index as $idx) {
                    $_file = WWW_ROOT . $data['attaches'][$idx][0];
                    if (is_file($_file)) {
                        @unlink($_file);
                    }
                }

                $this->{$this->modelName}->delete($entity);

                $id = 0;
            }
        }

        if ($redirect) {
            $this->redirect($redirect);
        }

        if ($redirect !== false) {
            if ($id) {
                $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->redirect(array('action' => 'index'));
            }
        }

        return;
    }

    /**
     * 中身は各コントローラに書く
     * @param  [type] $info_id [description]
     * @return [type]          [description]
     */
    protected function _htmlUpdate($info_id)
    { }

    public function goLogin()
    {
        $query = ['req' => Router::url()];
        $this->redirectWithException(['controller' => 'Home', 'action' => 'login', '?' => $query, 'prefix' => 'userAdmin']);
    }

    public function checkLogin()
    {
        if (!$this->isLogin()) {
            $this->goLogin();
        }
    }

    public function checkAdmin()
    {
        if (!$this->isAdmin()) {
            $this->goLogin();
        }
    }

    public function array_asso_chunk($datas, $num)
    {
        $res = [];
        $max = count($datas);

        $count = 0;
        $i = 0;
        foreach ($datas as $k => $v) {
            $res[$i][$k] = $v;
            $count++;
            if (!($count % $num)) {
                $i++;
            }
        }

        return $res;
    }

    public function setCommon()
    {
        $user_site_list = $this->_getUserSite();
        $current_site_id = $this->Session->read('current_site_id');

        $this->set(compact('user_site_list', 'current_site_id'));
    }

    public function _getUserSite($user_id = 0)
    {
        // $user_sites = $this->UserSites->find()
        //                               ->where(['UserSites.user_id' => $user_id])
        //                               ->contain(['SiteConfigs'])
        //                               ->all();

        $user_site_list = $this->SiteConfigs->find('list', ['keyField' => 'id', 'valueField' => 'site_name'])->where(['SiteConfigs.id' => 1])->toArray();

        // $user_site_list = [];
        // if (!empty($user_sites)) {
        //     foreach ($user_sites as $site) {
        //         $user_site_list[$site->site_config->id] = $site->site_config->site_name;
        //     }
        // }
        // if (!$this->Session->read('current_site_id')) {
        //     foreach ($user_site_list as $site_id => $config) {
        //         $this->Session->write('current_site_id', $site_id);
        //         if (!$this->Session->read('current_site_slug')) {
        //             foreach ($user_sites as $site) {
        //                 if ($site->site_config_id == $site_id) {
        //                     $this->Session->write('current_site_slug', $site->site_config->slug);
        //                 }
        //             }
        //         }
        //         break;
        //     }
        // }

        return $user_site_list;
    }

    protected function isUserRole($role_key, $isOnly = false)
    {
        $role = $this->Session->read('user_role');

        if (intval($role) === 0) {
            $res = 'develop';
        } elseif ($role < 10) {
            $res = 'admin';
        }
        /** 必要に応じて追加 */
        else {
            $res = 'staff';
        }

        if (!$isOnly) {
            if ($role_key == 'develop') {
                $role_key = array('develop');
            }
            if ($role_key == 'admin') {
                $role_key = array('develop', 'admin');
            }
            if ($role_key == 'staff') {
                $role_key = array('develop', 'admin', 'staff');
            }

            if ($role_key == 'demo') {
                $role_key = array('develop', 'admin', 'staff', 'demo');
            }
        }

        if (in_array($res, (array) $role_key)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 端数処理
     * @param [type] $value [description]
     */
    protected function Round($number, $decimal = 0, $type = 1)
    {
        return Util::Round($number, $decimal, $type);
    }

    protected function wareki($date)
    {
        return Util::wareki($date);
    }

    public function getData()
    {
        $id = $this->request->getData('id');
        $columns = $this->request->getData('columns');
        $append_columns = $this->request->getData('append_columns');
        $contain = $this->request->getData('contain');

        $primaryKeyColumn = 'id';
        if ($this->request->getQuery('primaryKeyColumn')) {
            $primaryKeyColumn = $this->request->getQuery('primaryKeyColumn');
        }

        $columns = str_replace(' ', '', $columns);
        $cols = explode(',', $columns);
        if (!empty($contain)) {
            $contain = explode(',', $contain);
        }

        $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primaryKeyColumn => $id]);
        if (!empty($contain)) {
            $query->contain($contain);
        }
        $data = $query->select($cols)->first();

        if (!empty($append_columns)) {
            $append_columns = str_replace(' ', '', $append_columns);
            $cols = explode(',', $append_columns);
            foreach ($cols as $col) {
                $data[$col] = $data->{$col};
            }
        }

        $this->rest_success($data);
    }

    public function exUrl($url, $args = [])
    {
        if (empty($args)) {
            return $url;
        }

        $urls = parse_url($url);
        $dir_path = $urls['path'];

        $queries = $urls['query'] ?? [];
        if ($queries) {
            parse_str($queries, $queries);
        }

        $queries = array_merge($queries, $args);
        $query_url = http_build_query($queries);

        return $dir_path . '?' . $query_url;
    }
}
