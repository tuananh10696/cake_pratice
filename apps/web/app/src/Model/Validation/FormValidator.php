<?php
namespace App\Model\Validation;

use Cake\Validation\Validator;

class FormValidator extends Validator {
    public function __construct() {
        parent::__construct();
        // バリデーションのルールを加える

        $this->setProvider('custom', 'App\Model\Validation\CustomValidation');
        $this

        //ふりがな
        ->notEmpty('furi', 'ふりがなを入力してください')
        ->add('furi', [
            'length' => [
                'rule' => ['maxLength', 100],
                'message' => '100字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'rule' => ['custom', '/^[ぁ-ん 　-ー-]+$/u'],
                'message' => 'ふりがなを入力してください',
                'last' => true
            ],
            //sei、meiのどちらかが入力されていたらどっちも確認。
            'notMatches' => [
                'rule' => ['isset2allMatches', '/^[ぁ-ん-ー-]+$/u', 'hira_sei,hira_mei'],
                'provider' => 'custom',
                'message' => 'ふりがなを入力してください',
                'last' => true
            ]
        ])

        //生年月日
        ->notEmpty('birthday', '生年月日を選択してください')

        //住所
        ->notEmpty('address1', '住所を入力してください')

        //性別
        ->notEmpty('gender_id', '性別を選択してください')

        //運営ボランティア希望分野
        ->notEmpty('desired_type_ids', '選択してください')

        //活動希望日
        ->add('confirm_schedule', [
            'notMatches' => [
                'rule' => ['selectedDesiredActivityDate'],
                'provider' => 'custom',
                'message' => '活動日を選択してください',
                'last' => true
            ]
        ])

        //ユニフォームサイズ
        ->notEmpty('uniform_id', 'ユニフォームサイズを選択してください')

        //希望移動手段
        ->notEmpty('transportation_ids', '選択してください')

        //リストバンドの希望色
        ->notEmpty('wristband_color_id', '選択してください')

        //希望の分野
        // ->notEmpty('suppoter_desired_type_ids', '選択してください')

        //県国体事務局への情報提供について
        // ->notEmpty('availability_info_id', '選択してください')

        //名前
        ->notEmpty('name', 'お名前を入力してください')
        ->add('name', [
            'length' => [
                'rule' => ['maxLength', 30],
                'message' => '30字以内で入力してください',
                'last' => true
            ],
            //sei、meiのどちらかが入力されていたらどっちも確認。
            'notMatches' => [
                'rule' => ['isset2allMatches', '/^.*$/u', 'sei,mei'],
                'provider' => 'custom',
                'message' => 'お名前を入力してください',
                'last' => true
            ]
        ])

        //フリガナ
        ->notEmpty('kata_name', 'フリガナを入力してください')
        ->add('kata_name', [
            'length' => [
                'rule' => ['maxLength', 100],
                'message' => '100字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'rule' => ['custom', '/^[ァ-ヶ゛゜ 　ー-]*$/u'],
                'message' => 'フリガナを入力してください',
                'last' => true
            ],
            //sei、meiのどちらかが入力されていたらどっちも確認。
            'notMatches' => [
                'rule' => ['isset2allMatches', '/^[ァ-ヶ゛゜ー-]*$/u', 'kata_sei,kata_mei'],
                'provider' => 'custom',
                'message' => 'フリガナを入力してください',
                'last' => true
            ]
        ])

        //ふりがな
        ->notEmpty('hira_name', 'ふりがなを入力してください')
        ->add('hira_name', [
            'length' => [
                'rule' => ['maxLength', 100],
                'message' => '100字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'rule' => ['custom', '/^[ぁ-ん 　-ー-]+$/u'],
                'message' => 'ふりがなを入力してください',
                'last' => true
            ],
            //sei、meiのどちらかが入力されていたらどっちも確認。
            'notMatches' => [
                'rule' => ['isset2allMatches', '/^[ぁ-ん-ー-]+$/u', 'hira_sei,hira_mei'],
                'provider' => 'custom',
                'message' => 'ふりがなを入力してください',
                'last' => true
            ]
        ])

        //電話番号
        ->notEmpty('tel', '電話番号を入力してください')
        ->add('tel', [
            'length' => [
                'rule' => ['maxLength', 13],
                'message' => '13字以内で入力してください',
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

        //プラポリ
        ->notEmpty('is_privacy', 'プライバシーポリシーに同意してください')
        ->add('is_privacy', [
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkPolicy',
                'message' => 'プライバシーポリシーに同意してください',
                'last' => true
            ]
        ])

        //郵便番号
        ->notEmpty('zip', '郵便番号を半角数字(ハイフンなし)で入力してください')
        ->add('zip', [
            'length' => [
                'rule' => ['maxLength', 7],
                'message' => '7字以内(ハイフンなし)で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'provider' => 'custom',
                'rule' => 'checkPostcode',
                'message' => '郵便番号を半角数字(ハイフンなし)で入力してください',
                'last' => true
            ]
        ])

        //確認メールアドレス
        ->notEmpty('email_confirm', '同じメールアドレスを入力してください')
        ->add('email_confirm', [
            'notMatch' => [
                'rule' => ['compare', 'email'],
                'provider' => 'custom',
                'message' => '同じメールアドレスを入力してください',
                'last' => true
            ]
        ])

        //FAX
        ->notEmpty('fax', 'FAX番号を入力してください')
        ->add('fax', [
            'length' => [
                'rule' => ['maxLength', 11],
                'message' => '11字以内で入力してください',
                'last' => true
            ],
            'notMatch' => [
                'rule' => ['custom', "/^(0\d{6,11})|(0[0-9\-]{6,7}-[0-9]{3,4}\z)/"],
                'message' => 'FAX番号を正しく入力してください',
                'last' => true
            ]
        ])

        //数の確認
        ->notEmpty('count', '数が足りません')
        ->add('count', [
            'length' => [
                'rule' => ['range', 0, 10000],
                'message' => '数が足りません',
                'last' => true
            ]
        ])

        //ファイル
        ->notEmpty('file', 'ファイルが選択されていません')
        ->add('image', 'chkUserName', [
            'rule' => ['checkImage', '_image', array('jpg', 'jpeg', 'gif', 'png')],
            'provider' => 'custom',
            'message' => 'アップロードできないファイルです'])

        //
;
    }
}
