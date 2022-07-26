<?php

namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SkillsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->SkillCategories = $this->getTableLocator()->get('SkillCategories');
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

        $query['sch_category_id'] = $this->request->getQuery('sch_category_id');
        if (empty($query['sch_category_id'])) {
            $categories = $this->SkillCategories->find()->order(['SkillCategories.position' => 'ASC'])->all();
            if (!empty($categories)) {
                foreach ($categories as $cat) {
                    $query['sch_category_id'] = $cat->id;
                    break;
                }
            }
        }

        return $query;
    }

    protected function _getConditions($query) {
        $cond = [];

        if (!empty($query['sch_category_id'])) {
            $cond['Skills.skill_category_id'] = $query['sch_category_id'];
        }

        return $cond;
    }

    public function index() {
        $this->checkLogin();

        $query = $this->_getQuery();
        $this->set(compact('query'));

        $this->setList();

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

        if ($this->request->is(['post', 'put'])) {
            $this->request->data['skill_category_id'] = $query['sch_category_id'];
        } else {
            if (!$id) {
                $options['get_callback'] = function ($data) use ($query) {
                    $data['skill_category_id'] = $query['sch_category_id'];
                    return $data;
                };
            }
        }

        $options['redirect'] = $redirect;
        $options['callback'] = $callback;

        parent::_edit($id, $options);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        $options = [];

        $data = $this->Skills->find()->where(['Skills.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['sch_category_id' => $data->skill_category_id], '#' => 'content-' . $id];

        return parent::_position($id, $pos, $options);
    }

    public function enable($id) {
        $this->checkLogin();

        $options = [];

        $data = $this->Skills->find()->where(['Skills.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['sch_category_id' => $data->skill_category_id], '#' => 'content-' . $id];

        parent::_enable($id, $options);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();

        $data = $this->Skills->find()->where(['Skills.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options = ['redirect' => ['action' => 'index', '?' => ['sch_category_id' => $data->skill_category_id]]];

        parent::_delete($id, $type, $columns, $options);
    }

    public function setList() {
        $list = array();

        $list['category_list'] = $this->SkillCategories->find('list')->order(['SkillCategories.position' => 'ASC']);

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }
}
