<?php

namespace App\Controller\Admin;

use App\Controller\AppController as BaseController;
use Cake\ORM\TableRegistry;

class AppController extends BaseController {
    // public $helpers = [
    //     'Paginator' => ['templates' => 'paginator-templates']];

    protected function _lists($cond = array(), $options = array()) {
        $primary_key = $this->{$this->modelName}->getPrimaryKey();

        $this->paginate = array_merge(
            array('order' => $this->modelName . '.' . $primary_key . ' DESC',
                'limit' => 10,
                'paramType' => 'querystring',
                'url' => [
                    'sort' => null,
                    'direction' => null
                ]
            ),
            $options
        );

        try {
            if ($this->paginate['limit'] === null) {
                unset($options['limit'],
                        $options['paramType']);
                if ($cond) {
                    $options['conditions'] = $cond;
                }
                // $datas = $this->{$this->modelName}->find('all', $options);
                $query = $this->{$this->modelName}->find()->where($cond)->order($options['order'])->all();
            } else {
                $query = $this->paginate($this->{$this->modelName}->find()->where($cond));
            }
            $datas = $query->toArray();
            $count['total'] = $query->count();
        } catch (NotFoundException $e) {
            if (!empty($this->request->query['page'])
                && 1 < $this->request->query['page']) {
                $this->redirect(array('action' => $this->request->action));
            }
        }

        $this->set(compact('datas', 'query'));
    }

    protected function _edit($id = 0, $option = array()) {
        $option = array_merge(
            array('create' => null,
                'callback' => null,
                'error_callback' => null,
                'redirect' => array('action' => 'index'),
                'contain' => [],
                'success_message' => '保存しました',
                'validate' => 'default',
                'associated' => null
            ),
            $option
        );
        extract($option);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();

        if ($this->request->is(array('post', 'put'))
            && $this->request->getData() //post_max_sizeを越えた場合の対応(空になる)
            ) {
            $isValid = true;

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
                    foreach ($entity[$vals['contents_table']] as $k => $v) {
                        if ($v[$vals['sequence_id_name']]) {
                            $seq = $this->{$_model}->find()->where([$_model . '.id' => $v[$vals['sequence_id_name']]])->first();
                            $entity[$vals['contents_table']][$k][$vals['sequence_table']] = $seq;
                        }
                    }
                }
                // TODO::
                // $this->redirect($this->referer());

                $this->set('data', $data);
                $isValid = false;

                $data = $this->request->getData();
                if ($error_callback) {
                    $data = $error_callback($data);
                }
            } else {
            }

            if ($isValid) {
                $tableColumns = $this->{$this->modelName}->getSchema()->columns();

                $r = $this->{$this->modelName}->save($entity);
                if ($r) {
                    if ($success_message) {
                        $this->Flash->set($success_message);
                    }
                    if ($callback) {
                        $callback($entity->id);
                    }
                    // exit;
                    if ($redirect) {
                        $this->redirect($redirect);
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

            $this->set('data', $this->request->getData());
        }

        if (property_exists($this->{$this->modelName}, 'useHierarchization') && !empty($this->{$this->modelName}->useHierarchization)) {
            $contents = $this->toHierarchization($id, $entity);
            $this->set(array_keys($contents), $contents);
            // pr($contents);exit;
        }

        $this->set('entity', $entity);
    }

    public function _detail($id, $option = []) {
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
                $request = $this->getRequest()->withParsedBody(array_merge($this->request->getData(), $this->{$this->modelName}->defaultValues));
                $this->setRequest($request);
            }
        }

        $this->set('data', $this->request->getData());

        if (property_exists($this->{$this->modelName}, 'useHierarchization') && !empty($this->{$this->modelName}->useHierarchization)) {
            $contents = $this->toHierarchization($id, $entity);
            $this->set(array_keys($contents), $contents);
        }

        $this->set('entity', $entity);
    }

    public function isLogin() {
        $id = $this->Session->read('uid');
        return $id;
    }

    public function checkLogin() {
        if (!$this->isLogin()) {
            $this->redirect('/user_admin/');
        }
    }

    /**
     * 順番並び替え
     * */
    protected function _position($id, $pos, $options = array()) {
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

        // $this->OutputHtml->index($this->getUsername());
    }

    /**
     * 掲載中/下書き トグル
     * */
    protected function _enable($id, $options = array()) {
        $options = array_merge(array(
            'redirect' => array('action' => 'index', '#' => 'content-' . $id)
        ), $options);
        extract($options);

        $primary_key = $this->{$this->modelName}->getPrimaryKey();
        $query = $this->{$this->modelName}->find()->where([$this->modelName . '.' . $primary_key => $id]);

        if (!$query->isEmpty()) {
            $entity = $query->first();
            $status = ($entity->get('status') == 'publish') ? 'draft' : 'publish';
            $this->{$this->modelName}->updateAll(array('status' => $status), array($this->{$this->modelName}->getPrimaryKey() => $id));
        }
        if ($redirect) {
            $this->redirect($redirect);
        }
    }

    /**
     * ファイル/記事削除
     *
     * */
    protected function _delete($id, $type, $columns = null, $option = array()) {
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
                        array($columns => '',
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
    protected function _htmlUpdate($info_id) {
    }

    /**
     * ログインユーザーの記事かチェック
     * @param  [type] $info_id [description]
     * @return [type]          [description]
     */
    protected function checkOwner($info_id) {
        $result = false;

        $cond = [
            'UserInfos.id' => $info_id,
            'UserInfos.user_id' => $this->isLogin()
        ];
        $info = $this->UserInfos->find()->where($cond);
        if (!$info->isEmpty()) {
            $result = true;
        }

        return $result;
    }

    protected function getUsername() {
        return $this->Session->read('data.username');
    }

    public function getUserId($role = 'admin') {
        return $this->isLogin();
    }
}
