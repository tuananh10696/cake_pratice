<?php

namespace App\Model\Entity;

class PageConfigExtension extends AppEntity {
    const TYPE_LIST_BUTTON = '1';
    const TYPE_PAGE_BUTTON = '2';
    const TYPE_DETAIL_BUTTON = '3';

    public static $type_list = [
        self::TYPE_LIST_BUTTON => '一覧画面(テーブル内)に追加するボタン',
        self::TYPE_PAGE_BUTTON => '一覧画面(テーブル外)に追加するボタン',
        self::TYPE_DETAIL_BUTTON => '詳細画面に追加するボタン'
    ];
}
