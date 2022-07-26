<?php

namespace App\Model\Entity;

class PageConfigItem extends AppEntity {
    const TYPE_MAIN = 'main';
    const TYPE_BLOCK = 'block';
    const TYPE_SECTION = 'section';

    public static $type_list = [
        self::TYPE_MAIN => '基本項目',
        self::TYPE_BLOCK => 'コンテンツ',
        self::TYPE_SECTION => '枠'
    ];

    //項目設定用
    public static $item_keys = [
        'main' => [
            'date' => '掲載日',
            'slug' => 'スラッグ',
            'category' => 'カテゴリ',
            'title' => 'タイトル',
            'notes' => '概要',
            'image' => 'メイン画像',
            'image_title' => '画像注釈',
            'view_table_content' => '目次機能',
            'top_info' => 'トップ機能',
            'index_type' => 'ページ種類',
            'hash_tag' => 'ハッシュタグ',
            'meta' => 'meta',
            'status' => '記事表示の有効無効',
        ],
        'block' => [
            'all' => 'すべて',

            'title_h2' => '小見出し(H2)',
            'title' => '小見出し(H3)',
            'title_h4' => '小見出し(H4)',
            'title_h5' => '小見出し(H5)',

            'content' => '本文',
            'wysiwyg_old' => '本文(OLD)',
            'image' => '画像',
            'file' => 'ファイル添付',
            'button' => 'リンクボタン',
            'button2' => 'リンクボタン(丸)',
            'line' => '区切り線',
            'with_image' => '画像回り込み用',
            'dialogue' => '対話',
            'information' => 'インフォ',
        ],
        'section' => [
            'all' => 'すべて',
            'section_relation' => '関連記事',
            'section' => '枠',
            'section_file' => 'ファイル枠',
        ]
    ];
}
