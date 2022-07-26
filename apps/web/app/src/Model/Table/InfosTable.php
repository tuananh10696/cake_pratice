<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

class InfosTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
        'position' => 0,
        'status' => 'draft'
    ];

    // 新CMSの枠ブロックを使う場合の設定
    public $useHierarchization = [
        'contents_table' => 'info_contents',
        'contents_id_name' => 'info_content_id',
        'sequence_model' => 'SectionSequences',
        'sequence_table' => 'section_sequence',
        'sequence_id_name' => 'section_sequence_id'
    ];

    public $attaches = array(
        'images' => array(
            'image' => array(
                'extensions' => array('jpg', 'jpeg', 'gif', 'png'),
                'width' => 1200,
                'height' => 1200,
                'file_name' => 'img_%d_%s',
                'thumbnails' => array(
                    's' => array(
                        'prefix' => 's_',
                        'width' => 320,
                        'height' => 240
                    )
                ),
            )
            //image_1
        ),
        'files' => array(),
    );

    // 推奨サイズ
    public $recommend_size_display = [
        // 'image' => true, //　編集画面に推奨サイズを常時する場合の指定
        // 'image' => ['width' => 300, 'height' => 300] // attaachesに書かれているサイズ以外の場合の指定
        // 'image' => false
        'image' => '横幅700以上を推奨。1200x1200以内に縮小されます。'
    ];

    //
    public function initialize(array $config) {
        // 並び順
        $this->addBehavior('Position', [
            //カテゴリーごとにソートする場合、position設定時のconditionにcategoryを含める。
            'group' => [true],
            'groupMove' => true,
            'custom_group' => function ($query) {
                $info = $query->contain(['PageConfigs'])->first();
                $is_need_category_sort = (($info->page_config->is_category_sort ?? 'N') == 'Y');
                $cond = [
                    'Infos.page_config_id' => $info->page_config_id ?? 0
                ];
                if ($is_need_category_sort) {
                    $cond['Infos.category_id'] = $info->category_id ?? 0;
                }
                return $cond;
            }
        ]);

        // 添付ファイル
        $this->addBehavior('BinaryFileAttache');

        $this->hasMany('InfoContents')->setForeignKey('info_id')->setDependent(true);
        $this->hasMany('InfoTags')->setForeignKey('info_id')->setDependent(true);
        $this->hasMany('InfoAppendItems')->setDependent(true);
        $this->hasMany('Schedules')->setDependent(true);

        $this->hasMany('InfoCategories');

        $this->belongsTo('PageConfigs');
        $this->belongsTo('Categories');

        parent::initialize($config);
    }

    public function checkSLug($value, $context) {

        $data = $context["data"] ?? [];
        $id = $data["id"] ?? 0;
        $page_config_id = $data["page_config_id"] ?? 0;

        $cond = ["slug" => $value, "id !=" => $id, "page_config_id" => $page_config_id];
        
        $isSet = (bool) $this->find()->where($cond)->first();
        return !$isSet;
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('title', '入力してください')
            ->add('title', 'maxLength', [
                'rule' => ['maxLength', 100],
                'message' => __('100字以内で入力してください')
            ])
            ->allowEmpty('slug')
            ->add('slug', 'checkSLug', ['rule' => [$this, 'checkSLug'], 'message' => '同じスラッグが登録されています'])
            
            ->notEmpty('start_datetime', '入力してください')
            ->add('start_datetime', 'checkDateTimeFormat', ['rule' => [$this, 'checkDateTimeFormat'], 'message' => '正しい日付を選択してください'])
            ->add('start_datetime', 'checkStartEndTime', ['rule' => [$this, 'checkStartEndTime'], 'message' => '正しい日付を選択してください']);

        return $validator;
    }

    public function validationIsCategory(Validator $validator) {
        $validator = $this->validationDefault($validator);

        $validator
            ->notEmpty('category_id', '選択してください')
            ->add('category_id', 'check', ['rule' => ['comparison', '>', 0], 'message' => '選択してください'])
            ->notEmpty('start_datetime', '入力してください')
            ->add('start_datetime', 'checkDateTimeFormat', ['rule' => [$this, 'checkDateTimeFormat'], 'message' => '正しい日付を選択してください'])
            ->add('start_datetime', 'checkStartEndTime', ['rule' => [$this, 'checkStartEndTime'], 'message' => '正しい日付を選択してください']);

        return $validator;
    }

    public function getRecommendImageSize($column) {
    }

    //開始時刻と終了時刻の確認
    public function checkStartEndTime($value, $context) {
        $data = $context['data'] ?? [];

        $start = $data['start_datetime'] ?? '';
        $end = $data['end_datetime'] ?? '';

        if ($start && $end) {
            $start = new \DateTime($start);
            $end = new \DateTime($end);

            return $start <= $end;
        }

        return true;
    }

    // 複数カテゴリの場合のカテゴリ取得
    public function getCategories($info_id, $result_type = 'entity', $options = []) {
        $options = array_merge([
            'status' => null,
            'where' => null,
            'separator' => '、',
            'empty_result' => '未設定'
        ], $options);

        $this->InfoCategories = TableRegistry::get('InfoCategories');

        $contain = [
            'Categories'
        ];
        if ($options['status'] === 'publish' || $options['status'] === 'draft') {
            $contain = [
                'Categories' => function ($q) use ($options) {
                    return $q->where(['Categories.status' => $options['status']])->order(['Categories.position' => 'ASC']);
                }
            ];
        }

        $query = $this->InfoCategories->find()->contain($contain);
        if ($options['where']) {
            $query->where($options['where']);
        }

        $categories = $query->all();

        if ($result_type == 'entity') {
            return $categories;
        } elseif ($result_type == 'names') {
            $names = [];
            foreach ($categories as $c) {
                if (empty($c->category->name)) {
                    continue;
                }
                $names[] = $c->category->name;
            }
            if (empty($names)) {
                return $options['empty_result'];
            }
            return implode($options['separator'], $names);
        }

        return '';
    }

    
    public function getType($slug, $info_id = 0, $options = []) {
        // page_config_idを取得
        $AppendItems = TableRegistry::getTableLocator()->get('AppendItems');
        $PageConfigs = TableRegistry::getTableLocator()->get('PageConfigs');
        $page_config = $PageConfigs->find()->where(['slug' => $slug])->extract('id')->first();

        // 設定
        $options = array_merge(
            [
                'join' => [
                    [
                        'table' => 'mst_lists',
                        'alias' => 'mst',
                        'type' => 'INNER',
                        'conditions' => 'AppendItems.use_option_list = mst.use_target_id'
                    ],
                ],
                'conditions' => [
                    'AppendItems.slug' => 'articletype',
                    'AppendItems.page_config_id' => $page_config,
                ],
                'select' => [
                    'type_name' => 'mst.ltrl_nm',
                    'color' => 'mst.option_value1',
                    'display' => 'mst.option_value2',
                    'ltrl_val' => 'mst.ltrl_val',
                    'position' => 'mst.position'
                ],
                'order' => ['position' => 'ASC'],
            ],
            $options
        );

        // 指定の記事種類を取得
        if($info_id > 0) {
            $options['join'][] = [
                'table' => 'info_append_items',
                'alias' => 'i_a_item',
                'type' => 'INNER',
                'conditions' => [
                    "AppendItems.id = i_a_item.append_item_id",
                    "mst.ltrl_val = i_a_item.value_int",
                ]
            ];

            $options['conditions']['i_a_item.info_id'] = intval($info_id);
            $options['select']['info_id'] = "i_a_item.info_id";
            $options['select']['append_item_id'] = "i_a_item.append_item_id";
            $options['select']['append_item_id'] = "i_a_item.append_item_id";
        }
        
        extract($options);

        // 記事種類持ってくる
        $type = $AppendItems->find()->join($join)->where($conditions)
            ->select($select)->order($order)->toArray();

        // dd($type->sql(), $type->getValueBinder()->bindings());

        return $type;
    }


    public function getTags($slug, $info_id = null, $options = []) {
        $PageConfigs = TableRegistry::getTableLocator()->get('PageConfigs');
        $Tags = TableRegistry::getTableLocator()->get('Tags');
        $page_config = $PageConfigs->find()->where(['slug' => $slug])->extract('id')->first();

        //オプション
        $options = array_merge(
            [
                'order' => ['position' => 'ASC'],
                'conditions' => [
                    'Tags.page_config_id' => $page_config,
                    'Tags.status' => 'publish'
                ],
            ],
            $options
        );

        // 記事id指定時はその記事のタグ取得
        if (!(is_null($info_id))) {
            $options = array_merge(
                $options,
                [
                    'join' => [
                        'table' => 'info_tags',
                        'alias' => 'i_tag',
                        'type' => 'INNER',
                        'conditions' => 'Tags.id = i_tag.tag_id'
                    ],
                    'group' => 'Tags.id',
                    'order' => [
                        'i_tag.created' => 'ASC',
                        'i_tag.id' => 'ASC',
                    ],
                ]
            );

            $options['conditions']['i_tag.info_id'] = $info_id;
        }

        extract($options);

        //全カテゴリー
        $data = $Tags->find();
        
        if(!(is_null($info_id))) {
            $data = $data->join($join)->group($group);
        }

        $data = $data->where($conditions)->order($order)->toArray();

        return $data;
    }
}
