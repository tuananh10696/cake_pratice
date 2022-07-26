<?php

namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Model\Entity\HogeHoge;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;
use App\Model\Entity\AppendItem;
use App\Model\Entity\MstList;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class MstListsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->MstLists = $this->getTableLocator()->get('MstLists');
        $this->AppendItems = $this->getTableLocator()->get('AppendItems');
        $this->PageConfigs = $this->getTableLocator()->get('PageConfigs');

        $this->modelName = 'MstLists';
        $this->set('ModelName', $this->modelName);
    }

    public function beforeFilter(Event $event) {
        $this->viewBuilder()->setLayout('user');

        $this->setCommon();
        $this->getEventManager()->off($this->Csrf);
    }

    public function index() {
        $this->checkLogin();
        if (!$this->isUserRole('admin')) {
            $this->Flash->set('不正なアクセスです');
            return $this->redirect('/');
        }

        $this->setList();

        $query = $this->_getQuery();
        if (!$this->isUserRole('develop')) {
            $query['list_code'] = MstList::LIST_FOR_USER;
        }

        $this->_setView($query);

        $this->set(compact('query'));

        // if(!empty($query['target_id'])){
        //     $target_data = $this->AppendItems->find()->where(['id' => $query['target_id']])->first();
        //     if(empty($target_data)){
        //         $this->Flash->set('対象項目エラー');
        //         $this->redirect('/');
        //     }
        //     $target = $target_data['name'];
        //     $this->set(compact('target'));
        // }
        $target_list = $this->getTargetList($query);
        $target_list = Hash::combine($target_list, '{n}.use_target_id', '{n}.list_name');
        $this->set(compact('target_list'));

        $cond = array();
        $cond = $this->_getConditions($query);

        $contain = [
        ];

        $this->_lists($cond, array('order' => array($this->modelName . '.position' => 'ASC'),
            'limit' => 10,
            'contain' => $contain
        ));
    }

    public function edit($id = 0) {
        $this->checkLogin();

        $this->setList();
        $query = $this->_getQuery();
        $this->set(compact('query'));
        $redirect = null;

        $target_list = $this->getTargetList($query);
        $target_list = Hash::combine($target_list, '{n}.use_target_id', '{n}');
        $this->set(compact('target_list'));

        if ($this->request->is(['post', 'put'])) {
            $redirect = ['action' => 'index', '?' => $query];
            if (empty($this->request->getData('ltrl_val'))) {
                $this->request->data['ltrl_val'] = $this->getMaxVals($query);
            }
        }

        $callback = function ($id, $entity) {
            //同じリストの、リスト名とリストスラッグを更新する。
            $to = ['list_name' => $entity->list_name, 'list_slug' => $entity->list_slug];
            $cond = ['use_target_id' => $entity->use_target_id];
            $this->MstLists->updateAll($to, $cond);
        };

        $options['redirect'] = $redirect;
        $options['callback'] = $callback;

        parent::_edit($id, $options);

        //新規登録時は、新しいリスト番号や、リスト名等を取得しておく。
        if (!$this->request->is(['post', 'put'])) {
            $data = $this->viewVars['data'];
            $init_data = [];
            $is_new = empty($data['id'] ?? 0);
            $use_target_id = $query['target_id'] ?? '';
            if ($is_new) {
                $all_target_list = $this->getTargetList($query, true);
                $all_target_list = Hash::combine($all_target_list, '{n}.ltrl_val', '{n}', '{n}.use_target_id');

                if (!$use_target_id) {
                    $last_id = $all_target_list ? (max(array_keys($all_target_list))) : 0;
                    $init_data = [
                        'use_target_id' => intval($last_id) + 1,
                        'ltrl_val' => 1,
                    ];
                } else {
                    $target_list = $all_target_list[$use_target_id] ?? [];
                    $max_val = $target_list ? (max(array_keys($target_list))) : 0;

                    $init_data = [
                        'use_target_id' => $use_target_id,
                        'list_name' => $target_list[$max_val]['list_name'] ?? '',
                        'list_slug' => $target_list[$max_val]['list_slug'] ?? '',
                        'ltrl_val' => intval($max_val) + 1,
                    ];
                }
            }
            $data = array_merge($data, $init_data);
            $request = $this->getRequest()->withParsedBody($data);
            $this->setRequest($request);
            $this->set('data', $data);
        }
    }

    public function delete($id = 0, $type, $columns = null) {
        $this->checkLogin();

        if (empty($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user/');
            return;
        }

        $data = $this->{$this->modelName}->find()->where([$this->modelName . '.id' => $id])->first();

        if (empty($data)) {
            $this->redirect('/user/');
            return;
        }

        $options = ['redirect' => ['action' => 'index']];

        $result = parent::_delete($id, $type, $columns, $options);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        $query = $this->_getQuery();

        $options = [];

        $data = $this->{$this->modelName}->find()->where([$this->modelName . '.id' => $id])->first();

        if (empty($data)) {
            $this->redirect(['action' => 'index']);
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => $query];

        return parent::_position($id, $pos, $options);
    }

    // -------------------------------------------------------------------------------

    public function _getQuery() {
        $query = [];
        $query['list_code'] = $this->request->getQuery('list_code');
        if (empty($query['list_code'])) {
            $query['list_code'] = MstList::LIST_FOR_USER;
        }
        $query['target_id'] = $this->request->getQuery('target_id');

        return $query;
    }

    public function _getConditions($query) {
        $cond = [];

        if (!empty($query['target_id'])) {
            $cond['MstLists.use_target_id'] = $query['target_id'];
        } else {
            $cond['MstLists.use_target_id'] = 0;
        }

        if (!empty($query['list_code'])) {
            $cond['MstLists.sys_cd'] = $query['list_code'];
        }

        return $cond;
    }

    public function setList() {
        $list = array(
        );

        $list['sys_list'] = MstList::$sys_list;
        if (!$this->isUserRole('admin')) {
            unset($list['sys_list'][MstList::LIST_FOR_ADMIN]);
        }

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }
        $this->list = $list;
        return $list;
    }

    public function getTargetList($query = [], $no_cond = false) {
        $list = [];

        $cond = [];

        if (!$no_cond) {
            if (!empty($query['list_code'])) {
                $cond['MstLists.sys_cd'] = $query['list_code'];
            } else {
            }
        }

        $datas = $this->MstLists->find()->order(['use_target_id' => 'ASC', 'ltrl_val' => 'ASC'])->where($cond)->toArray();
        return $datas ? $datas : [];
    }

    protected function getMaxVals($query) {
        $num = 1;
        $cond['MstLists.use_target_id'] = $query['target_id'];
        // $cond['MstLists.sys_cd'] = $query['list_code'];

        $datas = $this->MstLists->find()
                               ->where($cond)
                               ->all();

        if (empty($datas)) {
            return $num;
        }

        foreach ($datas as $data) {
            if ($num <= intval($data['ltrl_val'])) {
                $num = intval($data['ltrl_val']) + 1;
            }
        }

        return $num;
    }
}
