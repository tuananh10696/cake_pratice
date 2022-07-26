<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AppendItemsTable extends AppTable {
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
        $this->hasMany('InfoAppendItems')->setDependent(true);

        //付属するオプション
        $this->hasMany('MstOptions', array(
            'className' => 'MstLists',
            // 'propertyName' => 'page_config_items',
            'foreignKey' => 'use_target_id',
            'bindingKey' => 'use_option_list',
            'sort' => array('MstOptions.position' => 'ASC'),
            // 'conditions' => ['MstOptions.status' => 'Y']
            // 'conditions' => $this->getPublicPageConditions(),
        ));

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator
            ->allowEmpty('name');

        return $validator;
    }
}
