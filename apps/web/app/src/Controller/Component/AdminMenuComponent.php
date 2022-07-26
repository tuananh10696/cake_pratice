<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ModelAwareTrait;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use App\Model\Entity\User;
use Cake\Utility\Hash;

/**
 * OutputHtml component
 */
class AdminMenuComponent extends Component
{
    public $menu_list = [];

    public function initialize(array $config)
    {
        $this->Controller = $this->_registry->getController();
        $this->Session = $this->Controller->getRequest()->getSession();

        $this->PageConfigs = TableRegistry::getTableLocator()->get('PageConfigs');

        $this->init();
    }

    public function init()
    {
        //ログインしていない
        if (!$this->Session->check('user_role')) {
            return [];
        }

        //すでに保存済み
        if ($this->Session->check('admin_menu.menu_list')) {
            $this->menu_list = $this->Session->read('admin_menu.menu_list');
            return true;
        }

        //処理
        $menu_list = [
            //グループ1つ目
            [
                'group_name' => 'コンテンツ',
                'viewable_role' => 'staff', //基本はコンテンツ設定からページごとに設定する
                'func_under_button' => null, //ボタンの下で出力する
                'buttons' => [
                    //1列目 //サイドメニューでは平坦
                    [
                        // オープンキャンパス
                        [
                            'page_config_slug' => 'news', //コンテンツスラッグを入力するだけ
                            // 'viewable_role' => 'staff', //基本はコンテンツ設定からページごとに設定する
                            // 'name' => 'コンテンツ名を上書きします', //存在しない場合はコンテンツ名を出力する
                            // 'link' => '/override_link', //存在しない場合はコンテンツまでのurlを出力する
                        ],
                    ],
                    //2列目
                    // [
                    //     // ['page_config_slug' => 'news1', ],//例
                    //     // ['page_config_slug' => 'news2', ],//例
                    //     // ['page_config_slug' => 'news3', ],//例
                    //     [
                    //         // 'viewable_role' => 'staff',
                    //         'name' => 'カスタムページ',
                    //         'link' => '/user_admin/custompage',
                    //     ],
                    // ],
                ],
            ],
            //グループ2つ目
            [
                'group_name' => '各種設定',
                'viewable_role' => 'develop',
                'func_under_button' => null,
                'buttons' => [
                    [
                        [
                            // 'page_config_slug' => 'news',
                            'viewable_role' => 'develop',
                            'name' => 'コンテンツ設定',
                            'link' => '/user_admin/page-configs',
                        ],
                        [
                            // 'page_config_slug' => 'news',
                            'viewable_role' => 'develop',
                            'name' => '定数管理',
                            'link' => '/user_admin/mst-lists',
                        ],
                    ],
                ],
            ],
        ];

        //page_config_slugから情報を取得する
        $page_configs = $this->PageConfigs->find()->toArray();
        $page_configs = Hash::combine($page_configs, '{n}.slug', '{n}');

        $main_menu_list = [];
        $side_menu_list = [];
        foreach ($menu_list as $k => $group) {
            $role = $group['viewable_role'] ?? 'demo';
            if (!$this->isUserRole($role)) {
                continue;
            }

            $new_buttons = [];
            $new_buttons_side = [];
            $buttons = $group['buttons'] ?? [];
            foreach ($buttons as $retu_m => $retu1) {
                $new_buttons_r = [];
                foreach ($retu1 as $_ => $button) {
                    $new_button = [];

                    $page_data = $page_configs[$button['page_config_slug'] ?? ''] ?? [];
                    if ($page_data) {
                        $new_button = [
                            'role' => $page_data->editable_role ?? 'demo',
                            'name' => $page_data->page_title ?? '',
                            'link' => $page_data->admin_url ?? '',
                        ];
                    }

                    //page_dataを上書きする。
                    $new_button = array_merge($new_button, $button);
                    if (!$this->isUserRole(($new_button['role'] ?? 'demo'))) {
                        continue;
                    }
                    $new_buttons_r[] = $new_button;
                }
                $new_buttons[$retu_m] = $new_buttons_r;
                $new_buttons_side = array_merge($new_buttons_side, $new_buttons_r);
            }

            if (empty($new_buttons)) {
                continue;
            }

            $group['buttons'] = $new_buttons;
            $main_menu_list[] = $group;

            $group['buttons'] = $new_buttons_side;
            $side_menu_list[] = $group;
        }

        $this->menu_list = [
            'main' => $main_menu_list,
            'side' => $side_menu_list
        ];
        $this->Session->write('admin_menu.menu_list', $this->menu_list);
        return true;
    }

    protected function isUserRole($role_key, $isOnly = false)
    {
        $role = $this->Session->read('user_role');

        if (intval($role) === 0) {
            $res = 'develop';
        } elseif ($role < 10) {
            $res = 'admin';
        }
        /** 必要に応じて追加 */
        else {
            $res = 'staff';
        }

        if (!$isOnly) {
            if ($role_key == 'develop') {
                $role_key = array('develop');
            }
            if ($role_key == 'admin') {
                $role_key = array('develop', 'admin');
            }
            if ($role_key == 'staff') {
                $role_key = array('develop', 'admin', 'staff');
            }

            if ($role_key == 'demo') {
                $role_key = array('develop', 'admin', 'staff', 'demo');
            }
        }

        if (in_array($res, (array)$role_key)) {
            return true;
        } else {
            return false;
        }
    }
}
