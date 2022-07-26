<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use App\Utils\CustomUtility;

class CategoriesTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
        'position' => 0
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

    //
    public function initialize(array $config) {
        // 添付ファイル
        $this->addBehavior('BinaryFileAttache');
        $this->addBehavior('Position', [
            'group' => ['page_config_id', 'parent_category_id'],
            'order' => 'DESC'
        ]);

        // アソシエーション
        $this->hasMany('Infos', [
            'dependent' => true
        ]);
        $this->belongsTo('PageConfigs');

        //親カテゴリー
        $this->belongsTo('ParentCategory', array(
            'className' => 'Categories',
            // 'propertyName' => 'page_config_items',
            'foreignKey' => 'parent_category_id',
            // 'sort' => array('EnabledPageConfigItems.position' => 'DESC'),
            // 'conditions' => ['EnabledPageConfigItems.status' => 'Y']
            // 'conditions' => $this->getPublicPageConditions(),
        ));

        //子カテゴリー
        $this->hasMany('Childs', array(
            'className' => 'Categories',
            // 'propertyName' => 'childs',
            'foreignKey' => 'parent_category_id',
            // 'sort' => array('EnabledPageConfigItems.position' => 'DESC'),
            // 'conditions' => ['EnabledPageConfigItems.status' => 'Y']
            // 'conditions' => $this->getPublicPageConditions(),
        ));

        //infoの数を数えるため。
        $this->belongsTo('InfoCoutup', array(
            'className' => 'CategoryGroupbyInfos',
            // 'propertyName' => 'category_id',
            // 'foreignKey' => 'Categories.id',
            'foreignKey' => false,
            // 'sort' => array('EnabledPageConfigItems.position' => 'DESC'),
            'conditions' => ['InfoCoutup.id = Categories.id']
            // 'conditions' => $this->getPublicPageConditions(),
        ));

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('name', '入力してください')
            ->add('name', 'maxLength', [
                'rule' => ['maxLength', 40],
                'message' => __('40字以内で入力してください')
            ])

            ->allowEmpty('identifier', '入力してください')
            ->add('identifier', 'checkAlphabetNum', ['rule' => [$this, 'checkAlphabetNum'], 'message' => '英数字で入力してください']);

        return $validator;
    }

    //カテゴリーリストを取得する。　全ての親と子カテゴリー
    //選択しているcategory category
    //選択できるlist id => title,
    //empty  false or [""=>"未選択"]
    public function getCategoryList($id = 0, $page_id = 0, $child = []) {
        $data = [];

        $is_selected_category = (bool) $id;

        //自分
        if ($id) {
            $cond = ['Categories.id' => $id, ];
        } else {
            $cond = ['Categories.page_config_id' => $page_id, ];
        }
        $target_category = $this->find()->where($cond)->contain(['PageConfigs'])->first();
        if (!$target_category) {
            return false;
        }
        $id = $target_category->id ?? 0;
        $parent_categoyr_id = $target_category->parent_category_id ?? 0;
        $page_config = $target_category->page_config ?? [];

        //兄弟を取得する。 自分も込み
        $cond = [
            'Categories.parent_category_id' => $parent_categoyr_id,
            'Categories.page_config_id' => $target_category->page_config_id,
        ];
        $brothers = $this->find()->where($cond)->order(['Categories.position' => 'ASC'])->toArray();

        //親がない + すべてを表示する設定　の場合はemptyにすべてを追加する
        $empty = false;
        if ($parent_categoyr_id === 0 && $page_config->need_all_category_select) {
            $empty = ['' => 'すべて'];
        }

        //結果
        $category_list = Hash::combine($brothers, '{n}.id', '{n}.name');

        //選択肢を都道府県に上書き
        $category_names = $target_category->page_config->{'category_name_' . $target_category->multiple_level} ?? '';
        if ($category_names == '都道府県') {
            $category_list = CustomUtility::getAreaPrefOptgroupList($category_list);
        }

        $data = [
            [
                'category' => $is_selected_category ? $target_category : [],
                'list' => $category_list,
                'empty' => $empty,
            ]
        ];

        //前回の結果がなければ、子カテゴリーを取得しておく。
        if (empty($child)) {
            $cond = [
                'Categories.parent_category_id' => $id,
                'Categories.page_config_id' => $target_category->page_config_id,
            ];
            $new_childs = $this->find()->where($cond)->order(['Categories.position' => 'ASC'])->toArray();
            if ($new_childs) {
                $data[] = [
                    'category' => (object)['id' => 0],
                    'list' => Hash::combine($new_childs, '{n}.id', '{n}.name'),
                    'empty' => ['' => '選択してください']
                ];
            }
        }

        //先頭に前回の結果(子供分)を挿入する。
        $retun = array_merge($data, $child);

        //親があれば、繰り返す。
        if ($parent_categoyr_id) {
            return $this->getCategoryList($parent_categoyr_id, $page_id, $retun);
        }
        return $retun;
    }
}
