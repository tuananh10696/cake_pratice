<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InfoTagsTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null
    ];

    public $attaches = array('images' => array(),
        'files' => array(),
    );

    //
    public function initialize(array $config) {
        $this->belongsTo('Infos')->setForeignKey('info_id');
        $this->belongsTo('Tags');

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        // $validator
        //     ->notEmpty('tag', '入力してください')
        //     // ->add('title', 'maxLength', [
        //     //     'rule' => ['maxLength', 100],
        //     //     'message' => __('100字以内で入力してください')
        //     // ])
        //     ;

        return $validator;
    }
}
