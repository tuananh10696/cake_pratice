<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class HomeController extends AppController {
    public function initialize() {
        parent::initialize();

        $this->PageConfigs = $this->getTableLocator()->get('PageConfigs');
        $this->Useradmins = $this->getTableLocator()->get('Useradmins');
        $this->UseradminSites = $this->getTableLocator()->get('UseradminSites');
    }

    public function beforeFilter(Event $event) {
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('user');

        $this->setCommon();
        $this->getEventManager()->off($this->Csrf);
    }

    public function runLogin() {
        $data = $this->request->getData();

        $login = false;

        $posted_id = $data['username'] ?? '';
        $posted_password = $data['password'] ?? '';

        if ($posted_id && $posted_password) {
            //管理者ログイン
            $user = $this->Useradmins->find()->where(['username' => $posted_id, 'status' => 'publish'])->first();
            if ($user) {
                $hasher = new DefaultPasswordHasher();
                if ($hasher->check($posted_password, $user->password) || $user->temp_password == $posted_password) {
                    // $this->Flash->set('ログイン権限がありません');
                    // $login = 'error';
                    return $user;
                }
            }

            // //校舎ログイン
            // $user = $this->SchoolAccounts->find()->where(['account_id' => $posted_id, 'account_password' => $posted_password])->first();
            // if ($user) {
            //     return [
            //         'id' => $user->id,
            //         'role' => 11,
            //         'school_id' => $user->id
            //     ];
            // }
        }

        return false;
    }

    public function login() {
        $this->viewBuilder()->setLayout('plain');
        if ($this->request->is('post') || $this->request->is('put')) {
            $login = $this->runLogin();

            //保存
            if ($login && $login != 'error') {
                $this->Session->write(
                    array(
                        'uid' => $login['id'],
                        'users' => $login,
                        'user_role' => $login['role']
                    )
                );
            }

            if ($login === false) {
                $this->Flash->set('アカウント名またはパスワードが違います');
            }
        }

        if (0 < $this->Session->read('uid')) {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function index() {
        $this->checkLogin();//ログインしているか
        $this->viewBuilder()->setLayout('user');

        $this->setCommon();

        $this->setList();

        // if (!$this->isUserRole('admin')) {
        //     $this->redirect($this->school_edit_url ?? '/');
        // }
    }

    public function logout() {
        $this->Session->delete('uid');
        $this->Session->delete('role');
        $this->Session->destroy();
        return $this->redirect(['action' => 'index']);
    }

    public function setList() {
        $current_site_id = $this->Session->read('current_site_id');
        if (!$current_site_id) {
            $this->Flash->set('サイト権限がありません');
            $this->logout();
        }

        $list = array();

        $page_configs = $this->PageConfigs->find()
                                          ->where(['PageConfigs.site_config_id' => $current_site_id])
                                          ->order(['PageConfigs.position' => 'ASC'])
                                          ->all()
                                          ->toArray();
        $list['user_menu_list'] = [
            'コンテンツ' => []
        ];
        if ($this->isUserRole('admin')) {
            $list['user_menu_list']['設定'] = [['コンテンツ設定' => '/user_admin/page-configs']];
        }
        if (!empty($page_configs)) {
            $configs = array_chunk($page_configs, 3);

            foreach ($configs as $_) {
                $menu = [];
                foreach ($_ as $config) {
                    $menu[$config->page_title] = '/user_admin/infos/?sch_page_id=' . $config->id;
                }
                $list['user_menu_list']['コンテンツ'][] = $menu;
            }
        }

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }

    public function siteChange() {
        $site_id = $this->request->getQuery('site');

        $user_id = $this->isLogin();

        $config = $this->UserSites->find()->where(['UseradminSites.useradmin_id' => $user_id, 'UseradminSites.site_config_id' => $site_id])
                                    ->contain(['SiteConfigs' => function ($q) {
                                        return $q->select(['slug']);
                                    }])
                                    ->first();
        if (!empty($config)) {
            $this->Session->write('current_site_id', $site_id);
            $this->Session->write('current_site_slug', $config->site_config->slug);
        }

        $this->redirect(['prefix' => 'user', 'controller' => 'users', 'action' => 'index']);
    }
}
