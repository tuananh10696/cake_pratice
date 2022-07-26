<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InfoTopsTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
    ];

    public $attaches = array('images' => array(),
        'files' => array(),
    );

    //
    public function initialize(array $config) {
        // $this->addBehavior('BinaryFileAttache');

        $this->addBehavior('Position', [
            'group' => ['page_config_id'],
            'groupMove' => false
        ]);

        $this->belongsTo('Infos')
             ->setDependent(true);

        $this->belongsTo('PageConfigs');

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        // $validator
        //     ->allowEmpty('is_required');

        return $validator;
    }
}
