<?php

namespace App\Model\Entity;

use Cake\Datasource\ModelAwareTrait;
use App\Model\Entity\AppendItem;
use Cake\I18n\Date;
use App\Utils\CustomUtility;
use Cake\ORM\TableRegistry;

class Info extends AppEntity {
    use ModelAwareTrait;

    const BLOCK_TYPE_TITLE = 1;
    const BLOCK_TYPE_TITLE_H4 = 5;
    const BLOCK_TYPE_TITLE_H5 = 16;
    const BLOCK_TYPE_TITLE_H2 = 18;
    const BLOCK_TYPE_CONTENT = 2;
    const BLOCK_TYPE_IMAGE = 3;
    const BLOCK_TYPE_FILE = 4;
    const BLOCK_TYPE_BUTTON = 8;
    const BLOCK_TYPE_BUTTON2 = 20;
    const BLOCK_TYPE_LINE = 9;
    const BLOCK_TYPE_SECTION = 10;
    const BLOCK_TYPE_SECTION_WITH_IMAGE = 11;
    const BLOCK_TYPE_SECTION_FILE = 12;
    const BLOCK_TYPE_SECTION_RELATION = 13;
    const BLOCK_TYPE_RELATION = 14;
    const BLOCK_TYPE_WYSIWYG_OLD = 15;
    const BLOCK_TYPE_DIALOGUE = 17;
    const BLOCK_TYPE_INFORMATION = 19;

    const BLOCK_TYPE_LIST = [
        self::BLOCK_TYPE_TITLE_H2 => '小見出し(H2)',
        self::BLOCK_TYPE_TITLE => '小見出し(H3)',
        self::BLOCK_TYPE_TITLE_H4 => '小見出し(H4)',
        self::BLOCK_TYPE_TITLE_H5 => '小見出し(H5)',
        self::BLOCK_TYPE_CONTENT => '本文',
        self::BLOCK_TYPE_WYSIWYG_OLD => '本文(OLD)',
        self::BLOCK_TYPE_IMAGE => '画像',
        self::BLOCK_TYPE_FILE => 'ファイル添付',
        self::BLOCK_TYPE_BUTTON => 'リンクボタン',
        self::BLOCK_TYPE_LINE => '区切り線',
        self::BLOCK_TYPE_SECTION_WITH_IMAGE => '画像回り込み用',
        self::BLOCK_TYPE_DIALOGUE => '対話',
        self::BLOCK_TYPE_INFORMATION => 'インフォ',
        self::BLOCK_TYPE_BUTTON2 => 'リンクボタン(丸)',
    ];

    // 枠属性リスト
    const BLOCK_TYPE_WAKU_LIST = [
        self::BLOCK_TYPE_SECTION => '枠',
        self::BLOCK_TYPE_SECTION_FILE => 'ファイル枠',
        self::BLOCK_TYPE_SECTION_RELATION => '関連記事',
    ];

    public static $block_name2number_list = [
        'BLOCK_TYPE_TITLE_H2' => self::BLOCK_TYPE_TITLE_H2,
        'BLOCK_TYPE_TITLE' => self::BLOCK_TYPE_TITLE,
        'BLOCK_TYPE_TITLE_H4' => self::BLOCK_TYPE_TITLE_H4,
        'BLOCK_TYPE_TITLE_H5' => self::BLOCK_TYPE_TITLE_H5,
        'BLOCK_TYPE_CONTENT' => self::BLOCK_TYPE_CONTENT,
        'BLOCK_TYPE_WYSIWYG_OLD' => self::BLOCK_TYPE_WYSIWYG_OLD,
        'BLOCK_TYPE_IMAGE' => self::BLOCK_TYPE_IMAGE,
        'BLOCK_TYPE_FILE' => self::BLOCK_TYPE_FILE,
        'BLOCK_TYPE_BUTTON' => self::BLOCK_TYPE_BUTTON,
        'BLOCK_TYPE_BUTTON2' => self::BLOCK_TYPE_BUTTON2,
        'BLOCK_TYPE_LINE' => self::BLOCK_TYPE_LINE,
        'BLOCK_TYPE_SECTION' => self::BLOCK_TYPE_SECTION,
        'BLOCK_TYPE_SECTION_WITH_IMAGE' => self::BLOCK_TYPE_SECTION_WITH_IMAGE,
        'BLOCK_TYPE_SECTION_FILE' => self::BLOCK_TYPE_SECTION_FILE,
        'BLOCK_TYPE_SECTION_RELATION' => self::BLOCK_TYPE_SECTION_RELATION,
        'BLOCK_TYPE_RELATION' => self::BLOCK_TYPE_RELATION,
        'BLOCK_TYPE_DIALOGUE' => self::BLOCK_TYPE_DIALOGUE,
        'BLOCK_TYPE_INFORMATION' => self::BLOCK_TYPE_INFORMATION,
    ];


    public static $block_number2key_list = [
        self::BLOCK_TYPE_TITLE_H2 => 'TITLE_H2',
        self::BLOCK_TYPE_TITLE => 'TITLE',
        self::BLOCK_TYPE_TITLE_H4 => 'TITLE_H4',
        self::BLOCK_TYPE_TITLE_H5 => 'TITLE_H5',
        self::BLOCK_TYPE_CONTENT => 'CONTENT',
        self::BLOCK_TYPE_WYSIWYG_OLD => 'WYSIWYG_OLD',
        self::BLOCK_TYPE_IMAGE => 'IMAGE',
        self::BLOCK_TYPE_FILE => 'FILE',
        self::BLOCK_TYPE_BUTTON => 'BUTTON',
        self::BLOCK_TYPE_BUTTON2 => 'BUTTON2',
        self::BLOCK_TYPE_LINE => 'LINE',
        self::BLOCK_TYPE_SECTION => 'SECTION',
        self::BLOCK_TYPE_SECTION_WITH_IMAGE => 'WITH_IMAGE',
        self::BLOCK_TYPE_SECTION_FILE => 'SECTION_FILE',
        self::BLOCK_TYPE_SECTION_RELATION => 'SECTION_RELATION',
        self::BLOCK_TYPE_RELATION => 'RELATION',
        self::BLOCK_TYPE_DIALOGUE => 'DIALOGUE',
        self::BLOCK_TYPE_INFORMATION => 'INFORMATION',
    ];

    public static $option_default_values = [
        // self::BLOCK_TYPE_SECTION_WITH_IMAGE => ''
    ];

    public $append_fields = [];

    // 枠属性への侵入を除外するブロック
    public static $out_waku_list = [
        self::BLOCK_TYPE_SECTION => [
            self::BLOCK_TYPE_RELATION,

            self::BLOCK_TYPE_SECTION,
            // self::BLOCK_TYPE_SECTION_WITH_IMAGE,
            self::BLOCK_TYPE_SECTION_FILE,
            self::BLOCK_TYPE_SECTION_RELATION,
        ],
        // self::BLOCK_TYPE_SECTION_WITH_IMAGE => [
        //     self::BLOCK_TYPE_IMAGE,

        //     self::BLOCK_TYPE_SECTION,
        //     self::BLOCK_TYPE_SECTION_WITH_IMAGE,
        //     self::BLOCK_TYPE_SECTION_FILE,
        //     self::BLOCK_TYPE_SECTION_RELATION
        // ],
        self::BLOCK_TYPE_SECTION_FILE => [
            self::BLOCK_TYPE_TITLE_H2,
            self::BLOCK_TYPE_TITLE,
            self::BLOCK_TYPE_TITLE_H4,
            self::BLOCK_TYPE_TITLE_H5,
            self::BLOCK_TYPE_CONTENT,
            self::BLOCK_TYPE_WYSIWYG_OLD,
            self::BLOCK_TYPE_IMAGE,
            self::BLOCK_TYPE_BUTTON,
            self::BLOCK_TYPE_BUTTON2,
            self::BLOCK_TYPE_LINE,

            self::BLOCK_TYPE_SECTION,
            self::BLOCK_TYPE_SECTION_WITH_IMAGE,
            self::BLOCK_TYPE_SECTION_FILE,
            self::BLOCK_TYPE_SECTION_RELATION,
    
            self::BLOCK_TYPE_DIALOGUE,
            self::BLOCK_TYPE_INFORMATION,
        ],
        self::BLOCK_TYPE_SECTION_RELATION => [
            self::BLOCK_TYPE_TITLE_H2,
            self::BLOCK_TYPE_TITLE,
            self::BLOCK_TYPE_TITLE_H4,
            self::BLOCK_TYPE_TITLE_H5,
            self::BLOCK_TYPE_CONTENT,
            self::BLOCK_TYPE_WYSIWYG_OLD,

            self::BLOCK_TYPE_IMAGE,
            self::BLOCK_TYPE_FILE,
            self::BLOCK_TYPE_BUTTON,
            self::BLOCK_TYPE_BUTTON2,
            self::BLOCK_TYPE_LINE,

            self::BLOCK_TYPE_SECTION,
            self::BLOCK_TYPE_SECTION_WITH_IMAGE,
            self::BLOCK_TYPE_SECTION_FILE,
            self::BLOCK_TYPE_SECTION_RELATION,

            self::BLOCK_TYPE_DIALOGUE,
            self::BLOCK_TYPE_INFORMATION,
        ]
    ];

    public static function getBlockTypeList($type = 'normal') {
        if ($type == 'normal') {
            return self::BLOCK_TYPE_LIST;
        } elseif ($type == 'waku') {
            return self::BLOCK_TYPE_WAKU_LIST;
        }
    }

    public static $font_list = [
        'font_style_1' => 'Noto Serif JP(明朝)',
        'font_style_2' => 'Noto Sans JP(ゴシック)',
        'font_style_3' => 'Kosugi Maru(丸ゴシック)'
    ];

    public static $line_style_list = [
        'line_style_1' => '線',
        'line_style_2' => '二重線',
        'line_style_3' => '破線',
        'line_style_4' => '点線'
    ];

    public static $line_color_list = [
        'line_color_1' => '赤',
        'line_color_2' => '緑',
        'line_color_3' => 'オレンジ',
        'line_color_4' => '青',
        'line_color_5' => '黒',
        'line_color_6' => 'グレー'
    ];

    public static $line_width_list = [
        '1' => '1px',
        '2' => '2px',
        '3' => '3px',
        '4' => '4px',
        '5' => '5px',
        '6' => '6px',
        '7' => '7px',
        '8' => '8px',
        '9' => '9px',
        '10' => '10px'
    ];

    public static $waku_color_list = [
        'waku_color_1' => '赤',
        'waku_color_2' => '緑',
        'waku_color_3' => 'オレンジ',
        'waku_color_4' => '青',
        'waku_color_5' => '黒',
        'waku_color_6' => 'グレー',
    ];

    public static $waku_bgcolor_list = [
        'waku_bgcolor_1' => '赤',
        'waku_bgcolor_2' => '緑',
        'waku_bgcolor_3' => 'オレンジ',
        'waku_bgcolor_4' => '青',
        'waku_bgcolor_5' => '黒',
        'waku_bgcolor_6' => 'グレー',
    ];

    public static $waku_style_list = [
        'waku_style_1' => '線',
        'waku_style_2' => '破線',
        'waku_style_3' => '点線',
        'waku_style_4' => '二重線',
        'waku_style_5' => '上下のみ',
        'waku_style_6' => '影付き'
    ];

    public static $button_color_list = [
        'button_color_1' => '赤',
        'button_color_2' => '緑',
        'button_color_3' => 'オレンジ',
        'button_color_4' => '青',
        'button_color_5' => 'グレー',
    ];

    public static $content_liststyle_list = [
        'liststyle_1' => '中点',
        'liststyle_2' => 'チェック',
        'liststyle_3' => '＞',
    ];

    public static $link_target_list = [
        '_self' => '現在のウインドウ',
        '_blank' => '新しいウインドウ'
    ];

    public static $week_strings = [
        '0' => 'SUN',
        '1' => 'MON',
        '2' => 'TUE',
        '3' => 'WED',
        '4' => 'THU',
        '5' => 'FRI',
        '6' => 'SAT'
    ];

    //記事詳細画面のURL
    public function getArticleUrl(){
        $link_config = $this->info_append_items[1] ?? null;
        $link_option = [
          'href' => $this->detailUrl(),
          'target' => "",
        ];
        if (!is_null($link_config)){
          switch($link_config['value_decimal']):
            case null:
            case 1:
              break;
            case 2;
              $link_option['href'] = $link_config['value_text'] ?? '';
              break;
            case 3;
              $link_option['href'] = $link_config['value_text2'] ?? '';
              $link_option['target'] = '_blank';
              break;
          endswitch;
        }

        return $link_option;
    }

    // 一般の場合
    public function detailUrl($addQuery = [], $link=null) {
        $url = '/';
        $id = $this->id ?? 0;
        $slug = $this->page_config->slug ?? '';

        //urlの指定があれば、書き換え
        $base_url = $this->page_config->detail_url ?? '';
        if ($base_url) {
            if ($base_url != '/') {
                $url .= $base_url;
            }
            $url = preg_replace("/\{id\}/u", $id, $url);

            $slug = $this->slug ? $this->slug : $id;
            $url = preg_replace("/\{slug\}/u", $slug, $url);
        } else {
            if ($slug) {
                $url .= "{$slug}/";
            }
            if ($id) {
                $url .= "{$id}";
            }
        }

        $query = http_build_query($addQuery);
        if ($query) {
            $url .= "?{$query}";
        }

        return $url;
    }

    public function indexUrl($addQuery = []) {
        $url = '/';

        $slug = $this->page_config->slug ?? '';

        //urlの指定があれば、書き換え
        $base_url = $this->page_config->index_url ?? '';
        if ($base_url) {
            $url .= $base_url;
        // $url = preg_replace("/\{id\}/u", $id, $url);
        } else {
            if ($slug) {
                $url .= "{$slug}/";
            }
        }

        $query = http_build_query($addQuery);
        if ($query) {
            $url .= "?{$query}";
        }

        return $url;
    }

    protected function _setMetaDescription($value) {
        return strip_tags(str_replace("\n", '', $value));
    }

    // protected function _setMetaKeywords($value) {
    //     if (array_key_exists('keywords', $this->_properties)) {
    //         $value = implode(",", array_values($this->properties['keywords']));

    //     }

    //     return $value;
    // }

    protected $_virtual = ['keywords', 'article_type', 'article_tags', ];

    protected function _getKeywords($value) {
        if (!array_key_exists('meta_keywords', $this->_properties)) {
            return '';
        }
        $values = explode(',', $this->_properties['meta_keywords']);

        return $values;
    }

    // 記事で記事種類を渡す
    protected function _getArticleType() {
        $this->loadModel('Infos');

        $id = $this->id ?? 0;
        $slug = $this->page_config->slug ?? '';

        // 記事のみ値を返す
        if ($slug != "article") {
            return null;
        }

        $type = $this->Infos->getType($slug, $id);

        return $type ? $type[0] : null;
    }

    // 記事で記事タグを渡す
    protected function _getArticleTags() {
        $this->loadModel('Infos');

        $id = $this->id ?? 0;
        $slug = $this->page_config->slug ?? '';

        // 記事のみ値を返す
        if ($slug != "article") {
            return null;
        }

        return $this->Infos->getTags($slug, $id);
    }

    public static function getWeekStr($w) {
        if (array_key_exists($w, self::$week_strings)) {
            return self::$week_strings[$w];
        }

        return '';
    }

    public function appendInit() {
        $this->loadModel('InfoAppendItems');
        $info = $this->_properties;

        $contain = [
            'AppendItems'
        ];
        $data = $this->InfoAppendItems->find()->where(['InfoAppendItems.info_id' => $info['id']])->contain($contain)->all();

        if (!$data->isEmpty()) {
            foreach ($data as $cd) {
                $this->append_fields[$cd->append_item->slug] = $cd;
            }
        }

        return $this->append_fields;
    }

    public function getAppend($field) {
        $info_append_items = $this->info_append_items;

        foreach ($info_append_items as $_ => $info_append_item) {
            $append_item = $info_append_item['append_item'] ?? [];
            if (!$append_item) {
                continue;
            }
            if ($append_item['slug'] != $field) {
                continue;
            }
            $value_type = $append_item['value_type'];

            return self::getConvertAppendValues($value_type, $info_append_item, $this);
        }

        return '';
    }

    public function getInfo($cond) {
        $this->loadModel('Infos');

        $today = new \DateTime();
        $cond = array_merge(
            [
                'Infos.status' => 'publish',
                [
                    'OR' => [
                        'Infos.start_datetime is' => null,
                        'Infos.start_datetime <=' => $today
                    ]
                ],
                [
                    'OR' => [
                        'Infos.end_datetime is' => null,
                        'Infos.end_datetime >=' => $today
                    ]
                ]
            ],
            $cond
        );
        return $this->Infos->find()->contain(['InfoAppendItems' => ['AppendItems'], 'PageConfigs'])->where($cond);
    }

    public function getConvertAppendValues($value_type, $entity, $info = []) {
        switch ($value_type) {
            case AppendItem::TYPE_NUMBER:
                return $entity->value_int;

            case AppendItem::TYPE_TEXT:
                return h($entity->value_text);
            case AppendItem::TYPE_ZIP:
                $address_init = $entity->append_item['value_default'] ?? '';
                $address_init = preg_replace("/\{title\}/u", ($info->title ?? ''), $address_init);

                $map_address = $entity->value_textarea2 ? $entity->value_textarea2 : $address_init;
                $map_address = h($map_address);
                $google_map_url = 'https://maps.google.co.jp/maps?output=embed&q=' . $map_address;
                $visit_google_map_url = CustomUtility::deleteUrlQuery($google_map_url, ['output']);

                return [
                    'zip' => h($entity->value_text),
                    'address' => h($entity->value_textarea),
                    'address_text' => '〒' . h($entity->value_text) . ' ' . h($entity->value_textarea),
                    'google_map_url' => $google_map_url,
                    'visit_google_map_url' => $visit_google_map_url
                ];

            case AppendItem::TYPE_TEXTAREA:
                return $entity->value_textarea;

            case AppendItem::TYPE_IMAGE:
                return $entity['attaches']['image'][0] ?? '';

            case AppendItem::TYPE_RADIO:
                $this->loadModel('MstLists');
                $ltrl_val = $entity->value_decimal;
                if (($options['result_type'] ?? '') == 'key') {
                    return $ltrl_val;
                } else {
                    $mstlist = $this->MstLists->find()->where(['use_target_id' => $entity->append_item->use_option_list, 'ltrl_val' => $ltrl_val])->first();
                    if (!empty($mstlist)) {
                        return $mstlist->ltrl_nm;
                    }
                }

                // no break
            case AppendItem::TYPE_RELATION:
                $ids = explode(',', ($entity->value_text ?? ''));
                if (!$ids) {
                    return [];
                }
                $cond = ['Infos.id IN' => $ids];
                return $this->getInfo($cond)->toArray();

            case AppendItem::TYPE_RELATION_ONE:
                $cond = ['Infos.id' => $entity->value_int ?? 0];
                return $this->getInfo($cond)->first();

            case AppendItem::TYPE_MST_CUSTOM:
                return $this->getCustomAppendItem($entity);

            case AppendItem::TYPE_IMAGE_POSI:
                $retun = [
                    'image' => $entity['attaches']['image'][0] ?? '',
                    'image_pos' => $entity->value_text,
                    'content' => $entity->value_textarea
                ];
                if (empty($retun['image']) || empty($retun['image_pos']) || empty($retun['content'])) {
                    return [];
                }
                return $retun;

            case AppendItem::TYPE_WYSIWYG_OLD:
                return $entity->value_textarea;

            case AppendItem::TYPE_WYSIWYG:
                return $entity->value_textarea;

            case AppendItem::TYPE_CELEB_MESSAGE:
                if (!$entity->value_text) {
                    return [];
                }
                return [
                    'name' => h($entity->value_text),
                    'groupname' => h($entity->value_text2),
                    'link_url' => h($entity->value_textarea),
                    'image' => $entity['attaches']['image'][0] ?? '',
                ];

            case AppendItem::TYPE_STARPlAYER:
                if (!$entity->value_text) {
                    return [];
                }
                return [
                    'name' => h($entity->value_text),
                    // "groupname" => $entity->value_text2,
                    'link_url' => h($entity->value_textarea),
                    'image' => $entity['attaches']['image'][0] ?? '',
                ];

            default:
            return $entity;
        }
    }

    //定数管理カスタムの取得
    public function getCustomAppendItem($entity) {
        $options = $entity['append_item']['mst_options'] ?? [];
        $key_list = AppendItem::$custom_key_list;
        $retun = [];
        foreach ($options as $k => $option) {
            $types = $option['option_value1'] ?? '';
            $key = $key_list[$types]['key'] ?? '';
            $type = $key_list[$types]['type'] ?? '';
            $slug = $option['ltrl_slug'] ?? '';
            $value = $entity[$key] ?? '';

            if ($type == 'image') {
                $value = $entity['attaches'][$slug][0] ?? '';
            }
            $retun[$slug] = $value;
        }
        return $retun;
    }

    public function append($field, $options = []) {
        $value = '';
        $entity = null;

        $options = array_merge([
            'result_type' => null
        ], $options);

        if (array_key_exists($field, $this->append_fields)) {
            $entity = $this->append_fields[$field];
        }

        if (!empty($entity)) {
            switch ($entity->append_item->value_type) {
                case AppendItem::TYPE_TEXT:
                    $value = $entity->value_text;
                    break;
                case AppendItem::TYPE_ZIP:
                    $value = [
                        'zip' => $entity->value_text,
                        'address' => $entity->value_textarea,
                        'address_text' => '〒' . $entity->value_text . ' ' . $entity->value_textarea,
                        'google_map_url' => $entity->value_text2,
                    ];
                    break;
                case AppendItem::TYPE_TEXTAREA:
                    $value = $entity->value_textarea;
                    break;
                case AppendItem::TYPE_IMAGE:
                    $value = $entity['attaches']['image'][0] ?? '';
                    break;
                case AppendItem::TYPE_RADIO:
                    $this->loadModel('MstLists');
                    $ltrl_val = $entity->value_decimal;
                    if ($options['result_type'] == 'key') {
                        $value = $ltrl_val;
                    } else {
                        $mstlist = $this->MstLists->find()->where(['use_target_id' => $entity->append_item->use_option_list, 'ltrl_val' => $ltrl_val])->first();
                        if (!empty($mstlist)) {
                            $value = $mstlist->ltrl_nm;
                        }
                    }
                    break;
                case AppendItem::TYPE_RELATION:
                    $ids = explode(',', ($entity->value_text ?? ''));
                    if (!$ids) {
                        $value = [];
                        break;
                    }
                    $this->loadModel('Infos');
                    $infos = $this->Infos->find()->where(['Infos.id IN' => $ids])->toArray();
                    $value = $infos;
                    break;

                case AppendItem::TYPE_WYSIWYG:
                    $value = $entity->value_textarea;
                    break;

                case AppendItem::TYPE_WYSIWYG:
                    return $entity->value_textarea;

                case AppendItem::TYPE_CELEB_MESSAGE:
                    $value = [
                        'name' => $entity->value_text,
                        'groupname' => $entity->value_text2,
                        'link_url' => $entity->value_textarea,
                        'image' => $entity['attaches']['image'][0] ?? '',
                    ];
                    break;

                case AppendItem::TYPE_STARPlAYER:
                    $value = [
                        'name' => $entity->value_text,
                        // "groupname" => $entity->value_text2,
                        'link_url' => $entity->value_textarea,
                        'image' => $entity['attaches']['image'][0] ?? '',
                    ];
                    break;

                default:
                $value = $entity;
                    break;
            }
        }

        return $value;
    }

    protected function _getSummary($data) {
        $this->loadModel('InfoContents');
        $info = $this->_properties;

        $cond = [
            'InfoContents.info_id' => $info['id'],
            'InfoContents.block_type in' => [self::BLOCK_TYPE_CONTENT, self::BLOCK_TYPE_WYSIWYG_OLD, self::BLOCK_TYPE_SECTION_WITH_IMAGE, self::BLOCK_TYPE_TITLE, self::BLOCK_TYPE_TITLE_H4, self::BLOCK_TYPE_TITLE_H5, ],
        ];

        $contents = $this->InfoContents->find()->where($cond)->order(['InfoContents.position' => 'ASC'])->all();

        $summary = '';
        if (!$contents->isEmpty()) {
            foreach ($contents as $content) {
                $summary .= (strip_tags(trim($content->title)) ?: strip_tags(trim($content->content)));
            }
        }

        return $summary;
    }

    public function setPageConfig($slug) {
        $this->loadModel('PageConfigs');
        $this->page_config = $this->PageConfigs
            ->find()
            ->where(['PageConfigs.slug' => $slug])
            ->first();
        unset($this->PageConfigs);
    }

}
