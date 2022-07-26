<?php

namespace App\Model\Entity;

class AppendItem extends AppEntity {
    const TYPE_NUMBER = 1;
    const TYPE_TEXT = 2;
    const TYPE_TEXTAREA = 3;
    const TYPE_DATE = 4;
    const TYPE_DATETIME = 5;
    const TYPE_LIST = 10;
    const TYPE_CHECK = 11;
    const TYPE_RADIO = 12;
    const TYPE_DECIMAL = 21;
    const TYPE_FILE = 31;
    const TYPE_IMAGE = 32;
    const TYPE_IMAGE_WP = 33;
    const TYPE_IMAGE_POSI = 39;

    const TYPE_MST_CUSTOM = 40;

    const TYPE_WYSIWYG_OLD = 36;
    const TYPE_WYSIWYG = 38;

    const TYPE_ZIP = 34;
    const TYPE_LINK = 41;

    const TYPE_RELATION = 35;
    const TYPE_RELATION_ONE = 37;

    const TYPE_INFO_LINK = 42;

    public static $value_type_list = [
        self::TYPE_NUMBER => '数字型',
        self::TYPE_TEXT => 'テキスト型',
        self::TYPE_TEXTAREA => 'テキストエリア型',
        self::TYPE_DATE => '日付型',
        self::TYPE_DATETIME => '日付時間型',
        self::TYPE_LIST => 'list型',
        self::TYPE_CHECK => 'checkbox型',
        self::TYPE_RADIO => 'radio型',
        self::TYPE_DECIMAL => 'deceimal3型',
        self::TYPE_FILE => 'file型',
        self::TYPE_IMAGE => '画像型',
        self::TYPE_IMAGE_WP => '画像型(WP)',
        self::TYPE_IMAGE_POSI => '画像型(回り込み)',

        self::TYPE_MST_CUSTOM => '定数管理カスタム',

        self::TYPE_WYSIWYG_OLD => '旧ウィジウィグ',
        self::TYPE_WYSIWYG => 'ウィジウィグ',

        self::TYPE_ZIP => '住所',
        self::TYPE_LINK => 'リンク',

        self::TYPE_RELATION => '関連記事(複数)',
        self::TYPE_RELATION_ONE => '関連記事(選択)',

        self::TYPE_INFO_LINK => '記事リンク',

    ];

    //追加項目・定数管理カスタム用
    // 定数管理のオプション名 => [
    //     "key" => info_append_itemのキー,
    //     "type" => 管理画面用のinputタイプ
    // ]
    public static $custom_key_list = [
        'text' => [
            'key' => 'value_text',
            'type' => 'text'
        ],
        'text2' => [
            'key' => 'value_text2',
            'type' => 'text'
        ],
        'text3' => [
            'key' => 'value_text3',
            'type' => 'text'
        ],
        'text4' => [
            'key' => 'value_textarea',
            'type' => 'text'
        ],

        'textarea' => [
            'key' => 'value_textarea',
            'type' => 'textarea'
        ],
        'textarea2' => [
            'key' => 'value_textarea2',
            'type' => 'textarea'
        ],

        'image' => [
            'key' => 'image',
            'type' => 'image'
        ],
    ];

    // TYPE_TEXTにて表示するplaceholderがあれば
    // slug => placeholder で設定
    public static $placeholder_list = [
        'link_url' => 'https://...',
        'ticket_link' => 'https://...',
        'news_link' => 'https://...',
        'special_link' => 'https://...',
    ];

    // TYPE_TEXTの※部分に表示するリスト、
    // $placeholder_list同様
    // slug => text で指定
    public static $notes_list = [
        'stadium_kana' => '括弧内に表示されます',
    ];
}
