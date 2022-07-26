<?php

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Model\Entity\Useradmin;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UseradminsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');
        $this->UseradminSites = $this->getTableLocator()->get('UseradminSites');

        $this->modelName = $this->name;
        $this->set('ModelName', $this->modelName);
    }

    public function beforeFilter(Event $event) {
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('admin');
        $this->getEventManager()->off($this->Csrf);
    }

    public function index() {
        $this->checkLogin();

        $this->setList();

        $query = $this->_getQuery();

        $this->_setView($query);

        $cond = array();

        $cond = $this->_getConditions($query);

        parent::_lists($cond, array('order' => array($this->modelName . '.id' => 'ASC'),
            'limit' => null));

        $query = $this->viewVars['query'];
        if (!empty($query)) {
            foreach ($query as $e) {
                $user_sites = $this->UseradminSites->find()->where(['UseradminSites.useradmin_id' => $e->id])->contain(['SiteConfigs' => function ($q) {
                    return $q->select(['site_name']);
                }])->all();
                $sites = [];
                if (!empty($user_sites)) {
                    foreach ($user_sites as $s) {
                        $sites[] = $s->site_config->site_name;
                    }
                }
                $e->sites = implode('、', $sites);
            }
        }
        $datas = $query->toArray();
        $this->set(compact('datas', 'query'));
    }

    private function _getQuery() {
        $query = [];

        return $query;
    }

    private function _getConditions($query) {
        $cond = [];

        extract($query);

        return $cond;
    }

    public function edit($id = 0) {
        $this->checkLogin();

        $this->setList();

        $site_config_ids = [];
        $validate = null;

        if ($this->request->is(['post', 'put'])) {
            $site_config_ids = $this->request->getData('user_sites');
            // unset($this->request->data['user_sites'];

            if ($id) {
                if ($this->request->getData('_password')) {
                    $this->request->data['password'] = $this->request->getData('_password');
                    $this->request->data['temp_password'] = '';
                    $validate = 'modifyIsPass';
                } else {
                    $validate = 'modify';
                }
            } else {
                $validate = 'new';
            }
        } else {
            // $user_sites = $this->UserSites->find()->where(['UserSites.user_id' => $id])->extract('site_config_id');
            // if (!empty($user_sites)) {
            //     foreach ($user_sites as $val) {
            //         $site_config_ids[] = $val;
            //     }
            // }
            $site_config_ids = $this->Useradmins->getUserSite($id);
        }

        $callback = function ($id) use ($site_config_ids) {
            $save_ids = [];
            if (!empty($site_config_ids)) {
                foreach ($site_config_ids as $config_id) {
                    $user_site = $this->UseradminSites->find()
                                                 ->where(['UseradminSites.useradmin_id' => $id, 'UseradminSites.site_config_id' => $config_id])
                                                 ->first();
                    if (empty($user_site)) {
                        $user_site = $this->UseradminSites->newEntity();
                        $user_site->useradmin_id = $id;
                        $user_site->site_config_id = $config_id;
                        $this->UseradminSites->save($user_site);
                    }
                    $save_ids[] = $user_site->id;
                }
            }
            $delete_cond = [
                'UseradminSites.useradmin_id' => $id
            ];
            if (!empty($save_ids)) {
                $delete_cond['UseradminSites.id not in'] = $save_ids;
            }

            $this->UseradminSites->deleteAll($delete_cond);
        };

        $this->set(compact('site_config_ids'));

        return parent::_edit($id, ['callback' => $callback, 'validate' => $validate]);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();

        return parent::_delete($id, $type, $columns);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        return parent::_position($id, $pos);
    }

    public function enable($id) {
        $this->checkLogin();

        return parent::_enable($id);
    }

    public function setList() {
        $list = array();

        $list['site_list'] = $this->SiteConfigs->getList();

        $list['role_list'] = Useradmin::$role_list;

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }
}
