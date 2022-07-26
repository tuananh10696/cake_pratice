<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InfoCategoriesTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
    ];

    public $attaches = array('images' => array(),
        'files' => array(),
    );
    // 推奨サイズ
    public $recommend_size_display = [
        // 'image' => true, //　編集画面に推奨サイズを常時する場合の指定
        // 'image' => ['width' => 700, 'height' => 300] // attaachesに書かれているサイズ以外の場合の指定
        // 'image' => false
        // 'image' => '横幅700以上を推奨。1200x1200以内に縮小されます。'
    ];

    //
    public function initialize(array $config) {
        // // 並び順
        // $this->addBehavior('Position', [
        //     'group' => ['user_info_id'],
        //     'groupMove' => false,
        //     'order' => 'DESC'
        // ]);
        // 添付ファイル
        // $this->addBehavior('BinaryFileAttache');

        $this->belongsTo('Infos');
        $this->belongsTo('Categories');

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator->setProvider('Info', 'App\Validator\InfoValidation');

        // ->allowEmpty('title', true)
        // ->add('title', 'maxLength', [
        //     'rule' => ['maxLength', 100],
        //     'message' => __('100字以内で入力してください')
        // ])

        // $validator
        //     ->add('file_name', 'maxLength', [
        //         'rule' => ['maxLength', 50],
        //         'message' => __('50文字以内で入力してください')
        //     ])
        //     ->add('file_name', 'checkFilename', [
        //         'rule' => ['checkFileEmpty'],
        //         'provider' => 'UserInfo',
        //         'message' => 'ファイル名を入力してください'])
        //     ->add('file_name', 'checkFilename', [
        //         'rule' => ['checkFilename'],
        //         'provider' => 'UserInfo',
        //         'message' => '使えない文字が含まれています']);

        return $validator;
    }
}
