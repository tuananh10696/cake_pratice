<?php

namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Routing\RequestActionTrait;
use App\Model\Entity\PageConfigExtension;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PageConfigExtensionsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->Infos = $this->getTableLocator()->get('Infos');
        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');
        $this->PageConfigs = $this->getTableLocator()->get('PageConfigs');
        $this->UseradminSites = $this->getTableLocator()->get('UseradminSites');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('user');

        $this->setCommon();
        $this->getEventManager()->off($this->Csrf);

        $this->modelName = $this->name;
        $this->set('ModelName', $this->modelName);
    }

    protected function _getQuery() {
        $query = [];

        $query['page_id'] = $this->request->getQuery('page_id');
        $query['page_slug'] = $this->request->getQuery('page_slug');

        return $query;
    }

    protected function _getConditions($query) {
        $cond = [];

        return $cond;
    }

    public function index() {
        $this->checkLogin();

        $query = $this->_getQuery();
        $this->set(compact('query'));

        $this->setList();

        if (!empty($query['page_id'])) {
            $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $query['page_id']])->first();
        } elseif (!empty($query['page_slug'])) {
            $page_config = $this->PageConfigs->find()->where(['PageConfigs.slug' => $query['page_slug']])->first();
            if (!empty($page_config)) {
                $query['page_id'] = $page_config->id;
            }
        }
        if (empty($page_config)) {
            return $this->redirect('/user_admin/');
        }

        $current_site_id = $this->Session->read('current_site_id');
        $site_config = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $current_site_id])->first();

        $this->set(compact('site_config', 'page_config'));

        $cond = ['PageConfigExtensions.page_config_id' => $page_config->id];

        $this->_lists($cond, ['order' => ['PageConfigExtensions.position' => 'ASC'],
            'limit' => null]);
    }

    public function edit($id = 0) {
        $this->checkLogin();

        $query = $this->_getQuery();

        if (!empty($query['page_id'])) {
            $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $query['page_id']])->first();
        } elseif (!empty($query['page_slug'])) {
            $page_config = $this->PageConfigs->find()->where(['PageConfigs.slug' => $query['page_slug']])->first();
            if (!empty($page_config)) {
                $query['page_id'] = $page_config->id;
            }
        }
        if (empty($page_config)) {
            return $this->redirect('/user_admin/');
        }

        $view = 'edit';
        $this->setList();

        $current_site_id = $this->Session->read('current_site_id');
        $site_config = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $current_site_id])->first();

        $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $query['page_id']])->first();

        $options['redirect'] = ['action' => 'index', '?' => $query];
        $this->set(compact('page_config', 'query'));

        parent::_edit($id, $options);

        $this->render($view);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();

        $query = $this->_getQuery();

        if (!$this->isOwnPageByUser($query['page_id'])) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        // $options = [];
        $options['redirect'] = ['action' => 'index', '?' => $query];
        parent::_delete($id, $type, $columns, $options);
    }

    public function position($id, $pos) {
        $this->checkLogin();
        $query = $this->_getQuery();

        if (!$this->isOwnPageByUser($query['page_id'])) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $options = [];

        $data = $this->{$this->modelName}->find()->where([$this->modelName . '.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['page_id' => $data->page_config_id], ];

        return parent::_position($id, $pos, $options);
    }

    public function enable($id) {
        $query = $this->_getQuery();

        $options = [
            'status_true' => 'Y',
            'status_false' => 'N',
            'redirect' => ['action' => 'index', '?' => $query]
        ];

        parent::_enable($id, $options);
    }

    public function setList() {
        $list['type_list'] = PageConfigExtension::$type_list;

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }
}
