<?php

namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class InfoTopsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->PageTemplates = $this->getTableLocator()->get('PageTemplates');
        $this->Infos = $this->getTableLocator()->get('Infos');
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

        $query['slug'] = $this->request->getQuery('slug');

        return $query;
    }

    protected function _getConditions($query) {
        $cond = [];

        return $cond;
    }

    public function index() {
        $this->checkLogin();

        $query = $this->_getQuery();

        $page_config = $this->PageConfigs->find()->where(['PageConfigs.slug' => $query['slug']])->first();
        $this->set(compact('query', 'page_config'));

        $cond = [
            'InfoTops.page_config_id' => $page_config->id
        ];

        $contain = [
            'Infos'
        ];

        $this->_lists($cond, ['order' => ['InfoTops.position' => 'ASC'],
            'contain' => $contain,
            'limit' => null]);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        $options = [];

        $data = $this->InfoTops->find()->where(['InfoTops.id' => $id])->contain(['PageConfigs'])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['slug' => $data->page_config->slug, '#' => 'content-' . $id]];

        return parent::_position($id, $pos, $options);
    }

    public function enable($id) {
        $this->checkLogin();

        $options = [];

        $data = $this->EventSchedules->find()->where(['EventSchedules.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['info_id' => $data->info_id, '#' => 'content-' . $id]];

        parent::_enable($id, $options);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();

        $data = $this->EventSchedules->find()->where(['EventSchedules.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['info_id' => $data->info_id, '#' => 'content-' . $id]];

        $result = parent::_delete($id, $type, $columns, $options);
    }

    public function setList() {
        $list = array();

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }
}
