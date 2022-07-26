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
use App\Utils\CustomUtility;
use Cake\Utility\Hash;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CategoriesController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->PageTemplates = $this->getTableLocator()->get('PageTemplates');
        $this->Infos = $this->getTableLocator()->get('Infos');
        $this->PageConfigs = $this->getTableLocator()->get('PageConfigs');
        $this->UseradminSites = $this->getTableLocator()->get('UseradminSites');
        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');

        $this->Categories = $this->getTableLocator()->get('Categories');

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

        //サイト情報
        $current_site_id = $this->Session->read('current_site_id');
        $site_config = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $current_site_id])->first();
        $this->set(compact('site_config'));

        //ページconfig
        $sch_page_config_id = $this->request->getQuery('sch_page_id');
        $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $sch_page_config_id])->first();
        $this->page_config = $page_config;
        $this->set(compact('page_config'));
    }

    protected function _getQuery() {
        $query = [];

        $query['sch_page_id'] = $this->request->getQuery('sch_page_id');
        $query['parent_id'] = $this->request->getQuery('parent_id');
        if (empty($query['parent_id'])) {
            $query['parent_id'] = 0;
        }
        $query['redirect'] = $this->request->getQuery('redirect');

        $query['relation_info_id'] = $this->request->getQuery('relation_info_id');

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

        if (!$this->isOwnPageByUser($query['sch_page_id'])) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $cond = [
            'Categories.page_config_id' => $query['sch_page_id']
        ];

        $parent_category = [];
        if ($this->page_config->is_category == 'Y') {
            if ($this->page_config->is_category_multilevel == 1) {
                $cond['Categories.parent_category_id'] = $query['parent_id'];
                $_parent_id = $query['parent_id'];
                do {
                    $tmp = $this->Categories->find()->where(
                        [
                            'Categories.page_config_id' => $query['sch_page_id'],
                            'Categories.id' => $_parent_id,
                            // 'Categories.parent_category_id >' => 0
                        ]
                    )->first();
                    if (!empty($tmp)) {
                        $_parent_id = $tmp->parent_category_id;
                        $parent_category[] = $tmp;
                    }
                } while (!empty($tmp));
            }
        }
        $category_multiple_level = intval(count($parent_category)) + 1;
        $this->set(compact('parent_category', 'category_multiple_level'));

        $this->_lists($cond, ['order' => ['position' => 'ASC'],
            'limit' => null]);
    }

    public function edit($id = 0) {
        $this->checkLogin();

        $query = $this->_getQuery();
        $this->set(compact('query'));
        $this->setList();

        if ($id && !$this->isOwnCategoryByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        //
        $target = $this->Categories->find()->where(['Categories.id' => $id, ])->first();
        $depth = $target->multiple_level ?? 1;

        $category_name = $this->page_config->{'category_name_' . $depth} ?? '';
        // if ($category_name == '都道府県') {
        //     $cond = [
        //         'PageConfigs.slug' => $this->page_config->slug,
        //         'Categories.multiple_level' => $depth
        //     ];
        //     $have_prefs = $this->Categories->find()->where($cond)->contain(['PageConfigs'])->toArray();
        //     $have_prefs = Hash::extract($have_prefs, '{n}.name');
        //     $areapref_list = CustomUtility::getAreaPrefOptgroupList();

        //     $pref_list = [];
        //     foreach ($areapref_list as $area_name => $prefs) {
        //         foreach ($prefs as $pref_slug => $pref_name) {
        //             if (($target->name ?? '') == $pref_slug || !in_array($pref_slug, $have_prefs)) {
        //                 $pref_list[$area_name][$pref_slug] = $pref_name;
        //             }
        //         }
        //     }
        //     $this->set(compact('pref_list'));
        // }

        $callback = function ($id) {
            $redirect = ['action' => 'index', '?' => $query];

            if ($this->request->getQuery('redirect') == 'infos') {
                $redirect = [
                    'controller' => 'infos',
                    'action' => 'index',
                    '?' => ['sch_page_id' => $this->request->getData('page_config_id'), 'sch_category_id' => $id, 'relation_info_id' => $this->request->getQuery('relation_info_id'), ]
                ];
            } else {
                $redirect = ['action' => 'index', '?' => ['sch_page_id' => $this->request->getData('page_config_id'), 'parent_id' => $this->request->getData('parent_category_id'), 'relation_info_id' => $this->request->getQuery('relation_info_id'), ]];
            }

            return $this->redirect($redirect);
        };

        $parent_category = [];
        if ($this->page_config->is_category == 'Y') {
            if ($this->page_config->is_category_multilevel == 1) {
                $cond['Categories.parent_category_id'] = $query['parent_id'];
                $_parent_id = $query['parent_id'];
                do {
                    $tmp = $this->Categories->find()->where(
                        [
                            'Categories.page_config_id' => $query['sch_page_id'],
                            'Categories.id' => $_parent_id,
                            // 'Categories.parent_category_id >' => 0
                        ]
                    )->first();
                    if (!empty($tmp)) {
                        $_parent_id = $tmp->parent_category_id;
                        $parent_category[] = $tmp;
                    }
                } while (!empty($tmp));
            }
        }
        $category_multiple_level = intval(count($parent_category)) + 1;
        $parent_category = $parent_category[0] ?? [];
        $this->set(compact('parent_category', 'category_multiple_level'));

        $options['callback'] = $callback;

        parent::_edit($id, $options);
    }

    public function position($id, $pos) {
        $this->checkLogin();

        if ($id && !$this->isOwnCategoryByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $options = [];

        $data = $this->Categories->find()->where(['Categories.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['sch_page_id' => $data->page_config_id, 'parent_id' => $data->parent_category_id], '#' => 'content-' . $id];

        return parent::_position($id, $pos, $options);
    }

    public function enable($id) {
        $this->checkLogin();

        if ($id && !$this->isOwnCategoryByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $options = [];

        $data = $this->Categories->find()->where(['Categories.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        $options['redirect'] = ['action' => 'index', '?' => ['sch_page_id' => $data->page_config_id, 'parent_id' => $data->parent_category_id], '#' => 'content-' . $id];

        parent::_enable($id, $options);
    }

    public function delete($id, $type, $columns = null) {
        $this->checkLogin();
        $query = $this->_getQuery();

        if ($id && !$this->isOwnCategoryByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $data = $this->Categories->find()->where(['Categories.id' => $id])->first();
        if (empty($data)) {
            $this->redirect('/user_admin/');
            return;
        }

        if ($type == 'content') {
            $redirect = ['action' => 'index', '?' => ['sch_page_id' => $data->page_config_id]];
            if ($this->request->getQuery('redirect') == 'infos') {
                $redirect = [
                    'controller' => 'infos',
                    'action' => 'index',
                    '?' => ['sch_page_id' => $data->page_config_id, 'sch_category_id' => $data->parent_category_id, 'relation_info_id' => $this->request->getQuery('relation_info_id'), ]
                ];
            }
        } else {
            $redirect = ['action' => 'edit', $id, '?' => ['sch_page_id' => $data->page_config_id, 'parent_id' => ($query['parent_id'] ?? ''), 'relation_info_id' => $this->request->getQuery('relation_info_id'), ], ];
        }

        $options = ['redirect' => $redirect];
        $result = parent::_delete($id, $type, $columns, $options);

        if ($type == 'content') {
            if (!$result) {
                $this->Infos->updateAll(['category_id' => 0, 'status' => 'draft'], ['Infos.category_id' => $data->id]);
            }
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
