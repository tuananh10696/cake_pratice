<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class MstListsTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
        'position' => 0
    ];

    public $attaches = array('images' => array(),
        'files' => array(),
    );

    //
    public function initialize(array $config) {
        // 添付ファイル
        $this->addBehavior('BinaryFileAttache');

        $this->addBehavior('Position', [
            'group' => ['use_target_id'],
            'groupMove' => true,
            'order' => 'DESC'
        ]);

        // アソシエーション
        $this->belongsTo('AppendItems')->setForeignKey('use_target_id')->setDependent(true);

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator->setProvider('MstList', 'App\Validator\MstListValidation');
        $validator
        ->notEmpty('use_target_id', '番号を入力してください')
        ->notEquals('use_target_id', 0, '0以外の数字を入力してください')
        ->notEmpty('list_name', 'リスト名を入力してください')
        ->allowEmpty('ltrl_slug')
        ->add('ltrl_slug', 'isUnique', ['rule' => ['isUnique'], 'provider' => 'MstList', 'message' => 'この単語は既に使用されています'])
        ->notEmpty('ltrl_val', '入力してください')
        ->add('ltrl_val', 'numeric', ['rule' => ['numeric'], 'message' => '半角数字で入力してください'])
        ->add('ltrl_val', 'isUnique', ['rule' => ['isUnique'], 'provider' => 'MstList', 'message' => 'この値は既に使用されています'])
        ->notEmpty('ltrl_nm', '入力してください')
            // ->notEmpty('image', '入力してください');
;
        return $validator;
    }
}
