<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;

class Useradmin extends AppEntity {
    const ROLE_DEVELOP = 0;
    const ROLE_ADMIN = 1;
    const ROLE_STAFF = 10;
    const ROLE_DEMO = 90;

    const ROLE_DEVELOP_SLUG = 'develop';
    const ROLE_ADMIN_SLUG = 'admin';
    const ROLE_STAFF_SLUG = 'staff';
    const ROLE_DEMO_SLUG = 'demo';

    public static $role_list = [
        self::ROLE_DEVELOP => '開発者',
        self::ROLE_ADMIN => '管理者',
        self::ROLE_STAFF => 'スタッフ',
        self::ROLE_DEMO => 'デモ',
    ];

    public static $editable_role_list = [
        self::ROLE_DEVELOP_SLUG => '開発者のみ',
        self::ROLE_ADMIN_SLUG => '開発者+管理者',
        self::ROLE_STAFF_SLUG => '開発者+管理者+スタッフ',
        self::ROLE_DEMO_SLUG => '開発者+管理者+スタッフ+デモ',
        // self::ROLE_DEMO => '開発者+管理者+スタッフ+デモ',
    ];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }

    protected function _getListName() {
        return "{$this->_properties['name']}({$this->_properties['username']})";
    }
}
