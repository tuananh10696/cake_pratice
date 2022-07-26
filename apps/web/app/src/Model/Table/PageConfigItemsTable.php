<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\PageConfigItem;

class PageConfigItemsTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
        'position' => 0
    ];

    public $attaches = array('images' => array(),
        'files' => array(),
    );

    // 推奨サイズ
    // public $recommend_size_display = [
    //     'image' => true, //　編集画面に推奨サイズを常時する場合の指定
    //     // 'image' => ['width' => 300, 'height' => 300] // attaachesに書かれているサイズ以外の場合の指定
    //     // 'image' => false
    // ];
    //
    public function initialize(array $config) {
        $this->setDisplayField('name');

        parent::initialize($config);

        // 添付ファイル
        // $this->addBehavior('BinaryFileAttache');
        $this->addBehavior('Position', [
            'group' => ['page_config_id'],
            'order' => 'DESC'
        ]);

        // アソシエーション
        $this->belongsTo('PageConfigs');

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator
            ->allowEmpty('name');

        return $validator;
    }

    public function enabled($page_id, $type, $key) {
        $key1 = strtoupper($key);
        $key2 = strtolower($key);

        $cond = [
            'PageConfigItems.page_config_id' => $page_id,
            'PageConfigItems.parts_type' => $type,
            'OR' => [
                ['PageConfigItems.item_key' => $key1],
                ['PageConfigItems.item_key' => $key2]
            ]
        ];
        $entity = $this->find()->where($cond)->first();
        if (empty($entity)) {
            return true;
        }

        return ($entity->status == 'Y' ? true : false);
    }

    public function getTitle($page_id, $type, $key, $default = '') {
        $key1 = strtoupper($key);
        $key2 = strtolower($key);
        $title = $default;

        $cond = [
            'PageConfigItems.page_config_id' => $page_id,
            'PageConfigItems.parts_type' => $type,
            'OR' => [
                ['PageConfigItems.item_key' => $key1],
                ['PageConfigItems.item_key' => $key2]
            ]
        ];
        $entity = $this->find()->where($cond)->first();
        if (!empty($entity)) {
            $title = $entity->title;
        }

        return $title;
    }

    public function getSubTitle($page_id, $type, $key, $default = '') {
        $key1 = strtoupper($key);
        $key2 = strtolower($key);
        $title = $default;
        $before = '<div>';
        $after = '</div>';

        $cond = [
            'PageConfigItems.page_config_id' => $page_id,
            'PageConfigItems.parts_type' => $type,
            'OR' => [
                ['PageConfigItems.item_key' => $key1],
                ['PageConfigItems.item_key' => $key2]
            ]
        ];
        $entity = $this->find()->where($cond)->first();
        if (!empty($entity)) {
            $title = $entity->sub_title;
        }

        if (!empty($entity)) {
            $title = $before . $title . $after;
        }

        return $title;
    }
}
