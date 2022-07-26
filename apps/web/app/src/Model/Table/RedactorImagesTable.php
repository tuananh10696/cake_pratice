<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RedactorImagesTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
    ];

    public $attaches = array('images' => array(
        'image' => array('extensions' => array('jpg', 'jpeg', 'gif', 'png'),
            'width' => 1920,
            'height' => 1920,
            'file_name' => 'img_%d_%s',
            'thumbnails' => array(
                's' => array(
                    'prefix' => 's_',
                    'width' => 320,
                    'height' => 240
                )
            ),
        )
    ),
        'files' => array(
        )
    );

    //
    public function initialize(array $config) {
        $this->addBehavior('BinaryFileAttache');

        parent::initialize($config);
    }

    // Validation
    public function validationDefault(Validator $validator) {
        return $validator;
    }
}
