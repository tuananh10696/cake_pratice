<?php

namespace App\Model\Table;

use Cake\Validation\Validator;

class SchedulesTable extends AppTable
{
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null
    ];

    public $attaches = array(
        'images' => array(),
        'files' => array(),
    );

    // Validation
    public function validationDefault(Validator $validator)
    {
        $validator->dateTime('start', ['ymd'], '※正しい日時フォーマットを入力してください。')
            ->notEmptyDateTime('start', '※開始を選択してください。');

        $validator->dateTime('end', ['ymd'], '※正しい日時フォーマットを入力してください。')
            ->allowEmptyDateTime('end')
            ->add(
                'end',
                [
                    'custom' => [
                        'rule' => function ($value, $context) {
                            if (new \DateTime($value) < new \DateTime($context['data']['start'])) return '※終了を選択してください。';
                            return true;
                        }
                    ]
                ]
            );


        return $validator;
    }
}
