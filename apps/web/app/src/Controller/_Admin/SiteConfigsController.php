<?php

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Routing\RequestActionTrait;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SiteConfigsController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->PageConfigs = $this->getTableLocator()->get('PageConfigs');
        $this->Infos = $this->getTableLocator()->get('Infos');

        $this->modelName = $this->name;
        $this->set('ModelName', $this->modelName);

        $this->loadComponent('OutputHtml');
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

        return parent::_lists($cond, array('order' => array($this->modelName . '.id' => 'ASC'),
            'limit' => null));
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
        $validate = 'default';

        $this->setList();

        $old_data = null;

        if ($this->request->is(['post', 'put'])) {
            $old_data = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $id])->first();
            if ($this->request->getData('is_root') == 1) {
                $validate = 'isRoot';
            }
        }

        $options['callback'] = function ($id) use ($old_data) {
            $config = $this->SiteConfigs->find()->where(['SiteConfigs.id' => $id])->first();
            $content_dir = $config->slug;
            $dir = SITE_PAGES_DIR . $content_dir;
            $dir = str_replace('__', '/', $dir);

            if (!empty($old_data) && $old_data->slug != $config->slug) {
                $source = SITE_PAGES_DIR . $old_data->slug;
                $source = str_replace('__', '/', $source);
                $Folder = new Folder($source);
                if (!$Folder->move($dir)) {
                    throw new Exception('移動に失敗しました', 1);
                }
                // 各コンテンツの一覧と詳細HTML再作成
                $this->reCreate($config->id, $config->slug);
            } else {
                if (!$config->is_root) {
                    if (empty($config->slug)) {
                        throw new Exception('ディレクトリ名がありません', 1);
                    }

                    $Folder = new Folder();
                    // フォルダの作成
                    if (!is_dir($dir)) {
                        if (!$Folder->create($dir, 0777)) {
                            throw new Exception('フォルダを作成できませんでした', 1);
                        }
                    }
                }
            }

            $dir = SITE_PAGES_DIR . DS . SITE_DATA_NAME;
            $Folder = new Folder();
            // フォルダの作成
            if (!is_dir($dir)) {
                if (!$Folder->create($dir, 0777)) {
                    throw new Exception('フォルダを作成できませんでした', 1);
                }
            }

            $dir = $dir . DS . USER_JSON_URL;
            $Folder = new Folder();
            // フォルダの作成
            if (!is_dir($dir)) {
                if (!$Folder->create($dir, 0777)) {
                    throw new Exception('フォルダを作成できませんでした', 1);
                }
            }
        };

        $options['validate'] = $validate;

        parent::_edit($id, $options);
        $this->render('edit');
    }

    public function reCreate($id, $site_slug) {
        $site_slug = str_replace('__', '/', $site_slug);
        $page_configs = $this->PageConfigs->find()->where(['PageConfigs.site_config_id' => $id])->all();

        if (empty($page_configs)) {
            return;
        }

        foreach ($page_configs as $page_config) {
            $dir = $site_slug;
            if ($page_config->slug) {
                $dir .= DS . $page_config->slug;
            }
            $this->OutputHtml->index($dir);

            $infos = $this->Infos->find()->where(['Infos.page_config_id' => $page_config->id])->all();
            if (empty($infos)) {
                continue;
            }
            foreach ($infos as $info) {
                $this->OutputHtml->detail('Infos', $info->id, $dir);
            }
        }
        return;
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

        parent::_enable($id);
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
