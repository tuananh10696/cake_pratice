<?php

namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use App\Model\Entity\Category;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SkillCategoriesController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->Skills = $this->getTableLocator()->get('Skills');
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

        $query = $this->_getQuery();
        $this->set(compact('query'));

        $cond = $this->_getConditions($query);

        $this->_lists($cond, ['order' => ['position' => 'ASC'],
            'limit' => null]);
    }

    public function edit($id = 0) {
        $this->checkLogin();

        $query = $this->_getQuery();
        $this->set(compact('query'));

        $this->setList();

        $redirect = ['action' => 'index', '?' => $query];

        $callback = null;

        $options['redirect'] = $redirect;
        $options['callback'] = $callback;

        parent::_edit($id, $options);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        $options = [];

        $data = $this->SkillCategories->find()->where(['SkillCategories.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '#' => 'content-' . $id];

        return parent::_position($id, $pos, $options);
    }

    public function enable($id) {
        $this->checkLogin();

        $options = [];

        $data = $this->SkillCategories->find()->where(['SkillCategories.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '#' => 'content-' . $id];

        parent::_enable($id, $options);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();

        $data = $this->SkillCategories->find()->where(['SkillCategories.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options = ['redirect' => ['action' => 'index']];

        $result = parent::_delete($id, $type, $columns, $options);
        if (!$result) {
            $this->Skills->updateAll(['skill_category_id' => 0, 'status' => 'draft'], ['Skills.skill_category_id' => $data->id]);
        }
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
