<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PageConfigExtensionsTable extends AppTable {
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
}
