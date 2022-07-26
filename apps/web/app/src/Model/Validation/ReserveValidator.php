<?php
namespace App\Model\Validation;

use Cake\Validation\Validator;

class ReserveValidator extends Validator {
    public function __construct() {
        parent::__construct();
        // バリデーションのルールを加える

        $this->setProvider('custom', 'App\Model\Validation\CustomValidation');
        $this

        //性別
        ->notEmpty('consult_history', '選択してください')

        //名前
        ->notEmptyString('name', 'お名前を入力してください')
        ->add('name', [
            'length' => [
                'rule' => ['maxLength', 30],
                'message' => '30字以内で入力してください',
                'last' => true
            ],
        ])

        //フリガナ
        ->notEmptyString('furi', 'フリガナを入力してください')
        ->add('furi', [
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

        //性別
        ->notEmpty('gender', '性別を選択してください')

        //生年月日
        ->notEmptyString('birthday', '生年月日を入力してください')
        ->add('birthday', [
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

        //住所
        //郵便番号
        ->notEmpty('zip', '郵便番号を半角数字で入力してください')
        ->add('zip', [
            'length' => [
                'rule' => ['maxLength', 8],
                'message' => '郵便番号を8字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkPostcode',
                'message' => '郵便番号を半角数字で入力してください',
                'last' => true
            ]
        ])
        // 住所
        ->notEmptyString('address1', '住所を入力してください')
        ->add('address1', [
            'length' => [
                'rule' => ['maxLength', 100],
                'message' => '住所を100字以内で入力してください',
                'last' => true
            ],
        ])

        //電話番号
        ->notEmpty('tel', '電話番号を入力してください')
        ->add('tel', [
            'length' => [
                'rule' => ['maxLength', 13],
                'message' => '電話番号を13字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkTel',
                'message' => '電話番号を半角数字で正しく入力してください',
                'last' => true
            ]
        ])

        //メールアドレス
        ->notEmpty('email', 'メールアドレスを入力してください')
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

        //職業
        ->notEmpty('job', '選択してください')
        ->notEquals('job', '0', '選択してください')
                
        //家族
        ->notEmpty('spouse', '選択してください')
        ->add('spouse', [
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkSpouse',
                'message' => '子供の人数を数字で入力してください',
                'last' => true
            ]
        ])
                
        //農家との関わり
        ->notEmpty('relation', '選択してください')
        ->notEquals('relation', '0', '選択してください')
                
        //農業経験
        ->notEmpty('experience', '選択してください')
        ->notEquals('experience', '0', '選択してください')
                
        //農業経験年数
        ->notEmpty('experience_year', '入力してください')
        ->nonNegativeInteger('experience_year', '数字で入力してください')
                
        //希望する農業形態
        // ->notEmpty('variety', '選択してください')
        // ->notEquals('job', 0)

        //家族の同意
        ->notEmpty('family_consent', '選択してください')

        //就農希望地
        ->notEmpty('desired_place', '選択してください')
        ->add('desired_place', [
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkDesiredPlace',
                'message' => '希望地を入力してください',
                'last' => true
            ],
        ])

        //希望作物
        ->notEmpty('desired_crop', '選択してください')
        ->add('desired_crop', [
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkDesiredCrop',
                'message' => '希望作物を入力してください',
                'last' => true
            ]
        ])

        //知りたい情報
        ->notEmptyArray('requested_info', '選択してください')

        //相談内容
        ->allowEmptyString('content')
        ->maxLength('content', 1000, '100字以内で入力してください')

        //プラポリ
        ->notEmpty('is_privacy', '同意してください')
        ->add('is_privacy', [
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkPolicy',
                'message' => '同意してください',
                'last' => true
            ]
        ])
;
    }
}
