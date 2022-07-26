<?php
namespace App\Model\Validation;

use Cake\Validation\Validator;

class ContactValidator extends Validator {
    public function __construct() {
        parent::__construct();
        // バリデーションのルールを加える

        $this->setProvider('custom', 'App\Model\Validation\CustomValidation');
        $this

        // 名前
        ->notBlank('name', 'お名前を入力してください')
        ->notEmptyString('name', 'お名前を入力してください')
        ->maxLength('name', 30, '30字以内でご入力ください')

        // フリガナ
        ->notBlank('kana', 'フリガナを入力してください')
        ->notEmptyString('kana', 'フリガナを入力してください')
        ->add('kana', [
            'length' => [
                'rule' => ['maxLength', 30],
                'message' => '30字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'rule' => ['custom', '/^[ァ-ヶ゛゜ 　ー-]*$/u'],
                'message' => 'フリガナを入力してください',
                'last' => true
            ],
        ])

        // 生年月日
        ->notBlank('date_of_birth', '生年月日を入力してください')
        ->notEmptyString('date_of_birth', '生年月日を入力してください')
        ->add('date_of_birth', [
            'length' => [
                'rule' => ['maxLength', 10],
                'message' => '生年月日を10字以内(スラッシュあり)で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkBirthday',
                'message' => '生年月日を半角数字(スラッシュあり)で入力してください',
                'last' => true
            ]
        ])

        // メールアドレス
        ->notBlank('email', 'メールアドレスを入力してください')
        ->notEmptyString('email', 'メールアドレスを入力してください')
        ->add('email', [
            'length' => [
                'rule' => ['maxLength', 100],
                'message' => '100字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkEmail',
                'message' => 'メールアドレスを正しく入力してください',
                'last' => true
            ]
        ])

        // 性別
        ->integer('sex', '選択してください')
        ->allowEmpty('sex', '選択してください')
        ->add(
            'sex',
            [
                'custom' => [
                    'rule' => function ($value, $context) {
                        if (intval($value) == 0) {
                            return '選択してください';
                        }
                        return true;
                    },
                ],
            ],
        )

        // プラポリ
        ->integer('is_accept')
        ->add(
            'is_accept',
            [
                'custom' => [
                    'rule' => function ($value, $context) {
                        if (intval($value) == 0) {
                            return '同意してください';
                        }
                        return true;
                    },
                ],
            ],
        )
;
    }
}
