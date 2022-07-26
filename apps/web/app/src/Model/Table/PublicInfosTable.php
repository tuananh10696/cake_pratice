<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

class PublicInfosTable extends AppTable {
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
        // 添付ファイル
        $this->addBehavior('BinaryFileAttache');

        $this->hasMany('InfoContents')->setForeignKey('info_id')->setDependent(true);
        $this->hasMany('InfoTags')->setForeignKey('info_id')->setDependent(true);
        $this->hasMany('InfoAppendItems')->setDependent(true);

        $this->hasMany('InfoCategories');

        $this->belongsTo('PageConfigs');
        $this->belongsTo('Categories');

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('title', '入力してください')
            ->add('title', 'maxLength', [
                'rule' => ['maxLength', 100],
                'message' => __('100字以内で入力してください')
            ])
            ->notEmpty('start_datetime', '入力してください')
            ->add('start_datetime', 'checkDateTimeFormat', ['rule' => [$this, 'checkDateTimeFormat'], 'message' => '正しい日付を選択してください']);

        return $validator;
    }

    public function validationIsCategory(Validator $validator) {
        $validator = $this->validationDefault($validator);

        $validator
            ->notEmpty('category_id', '選択してください')
            ->add('category_id', 'check', ['rule' => ['comparison', '>', 0], 'message' => '選択してください'])
            ->notEmpty('start_datetime', '入力してください')
            ->add('start_datetime', 'checkDateTimeFormat', ['rule' => [$this, 'checkDateTimeFormat'], 'message' => '正しい日付を選択してください']);

        return $validator;
    }

    public function getRecommendImageSize($column) {
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
}
