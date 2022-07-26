<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SkillCategoriesTable extends AppTable {
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
        // $this->addBehavior('BinaryFileAttache');
        $this->addBehavior('Position', [
            'order' => 'DESC'
        ]);

        // アソシエーション
        $this->hasMany('Skills')->setDependent(true);

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        $validator
            ->notEmpty('name', '入力してください')
            ->add('name', 'maxLength', [
                'rule' => ['maxLength', 60],
                'message' => __('60字以内で入力してください')
            ]);

        return $validator;
    }
}
