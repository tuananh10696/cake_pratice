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
use App\Model\Entity\Useradmin;
use App\Model\Entity\PageConfig;
use App\Model\Entity\PageConfigItem;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PageConfigsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->PageTemplates = $this->getTableLocator()->get('PageTemplates');
        $this->Infos = $this->getTableLocator()->get('Infos');
        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');
        $this->UseradminSites = $this->getTableLocator()->get('UseradminSites');

        $this->loadComponent('OutputHtml');
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

        return $query;
    }

    protected function _getConditions($query) {
        $cond = [];

        return $cond;
    }

    public function index() {
        $this->checkLogin();
        $this->setList();

        $current_site_id = $this->Session->read('current_site_id');
        $site_config = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $current_site_id])->first();
        $this->set(compact('site_config'));

        //権限
        if (!$this->isUserRole($site_config->page_editable_role)) {
            return $this->redirect('/user_admin/');
        }

        $cond = ['PageConfigs.site_config_id' => $current_site_id];

        $this->_lists($cond, ['order' => ['PageConfigs.position' => 'ASC'],
            'limit' => null]);
    }

    public function edit($id = 0) {
        $this->checkLogin();

        if ($id && !$this->isOwnPageByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        //サイト情報
        $current_site_id = $this->Session->read('current_site_id');
        $site_config = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $current_site_id])->first();
        $this->set(compact('site_config'));

        $this->setList();

        if ($this->request->is(['post', 'put'])) {
            if ($this->request->getData('is_category') == 'N') {
                // $this->request->withData('is_category_sort','N');
                $this->request->data['is_category_sort'] = 'N';
            }
        }

        //追加項目の初期設定
        if (!$id) {
            $page_config_items = [];
            foreach ($this->list['item_keys'] as $type => $keys) {
                foreach ($keys as $key => $key_title) {
                    $page_config_items[] = ['parts_type' => $type, 'item_key' => $key, 'status' => 'N'];
                }
            }
            $this->request->data['page_config_items'] = $page_config_items;
        }

        $old_data = null;
        if ($id) {
            $old_data = $this->PageConfigs->find()->where(['PageConfigs.id' => $id])->first();
        }

        //権限
        if (!$this->isUserRole($site_config->page_editable_role)) {
            $slug = $old_data['slug'] ?? '';
            return $this->redirect("/user_admin/infos/?page_slug={$slug}");
        }

        $options = [];
        $options['contain'] = ['PageConfigItems'];
        parent::_edit($id, $options);
    }

    public function reCreateDetail($page_config_id, $dir) {
        $infos = $this->Infos->find()->where(['Infos.page_config_id' => $page_config_id])->all();
        if (empty($infos)) {
            return;
        }

        foreach ($infos as $info) {
            $this->OutputHtml->detail('Infos', $info->id, $dir);
        }
        return;
    }

    public function writeIndex($slug) {
        $dir = USER_PAGES_DIR . $slug;
        $file = $dir . DS . 'index.html';

        $params = explode('/', $slug); // [0]=site_name [1]=page_name

        if (count($params) < 2) {
            $params[] = '';
        }
        $html = $this->requestAction(
            ['controller' => 'Contents', 'action' => 'index', 'pass' => ['site_slug' => $params[0], 'slug' => $params[1]]],
            ['return', 'bare' => false]
        );

        file_put_contents($file, $html);

        chmod($file, 0666);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();

        if (!$this->isOwnPageByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $options = [];
        // $options['redirect'] = ['action' => 'index'];

        parent::_delete($id, $type, $columns, $options);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        if (!$this->isOwnPageByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        return parent::_position($id, $pos);
    }

    public function setList() {
        $list = array();

        //項目設定用
        $list['item_keys'] = PageConfigItem::$item_keys;

        $list['template_list'] = $this->PageTemplates->find('list')->where(['PageTemplates.status' => 'publish'])->order('PageTemplates.position ASC')->toArray();

        $list['list_style_list'] = PageConfig::$list_styles;

        $list['role_type_list'] = Useradmin::$role_list;

        $list['editable_role_list'] = Useradmin::$editable_role_list;

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }
}
