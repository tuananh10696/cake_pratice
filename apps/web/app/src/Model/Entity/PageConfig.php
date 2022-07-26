<?php

namespace App\Model\Entity;

class PageConfig extends AppEntity {
    const LIST_STYLE_THUMBNAIL = 1;
    const LIST_STYLE_ONE_COLUMN = 2;

    public static $list_styles = [
        self::LIST_STYLE_THUMBNAIL => 'サムネイル',
        self::LIST_STYLE_ONE_COLUMN => '１カラム'
    ];

    protected $_virtual = ['admin_url'];

    public function _getAdminUrl() {
        $slug = $this->slug ?? '';
        if (!$slug) {
            return '';
        }
        return "/user_admin/infos/?page_slug={$slug}";
    }
}
