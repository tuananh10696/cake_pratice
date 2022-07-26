<?php
namespace App\View\Helper;

class UserAdminHelper extends AppHelper {
    public static $adminMenu = [
        'main' => [
            'マスタ管理' => [
                // １行目
                [
                    'ユーザー管理' => '/admin/useradmins',
                    'サイト管理' => '/admin/site-configs'
                ],
                // 2行目
                // [
                // ]
            ],
            // 'マスタ管理' => [
            //     [
            //         '状況マスタ' => '/admin/mst_statuses'
            //     ]
            // ]
        ],
        'side' => [
            // '担当者管理' => [
            // '一覧' => '/admin/staffs/',
            // '新規登録' => '/admin/staffs/edit/0'
            // ]
        ]
    ];

    public static $userMenu = [
        'main' => [
            'コンテンツ' => [
                // １行目
                [
                    '登録一覧' => '/user/infos',
                ],
                // 2行目
                // [
                // ]
            ],
            '設定' => [
                [
                    'コンテンツ設定' => '/user/page-configs']
            ]
        ],
        'side' => [
            'お知らせ' => [
                '新規登録' => '/user/infos/edit/',
                '登録一覧' => '/user/infos'
            ],
            // '設定' => [
            //     'コンテンツ設定' => '/user/user-pages/'
            // ]
        ]
    ];

    public function getUserMenu($type = 'main') {
        return self::$userMenu[$type];
    }

    public function getAdminMenu($type = 'main') {
        return self::$adminMenu[$type];
    }

    public function getUsername() {
        $session = $this->request->getSession();

        return $session->read('data.username');
    }
}
