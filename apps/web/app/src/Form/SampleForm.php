<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Mailer\Email;
use Cake\Core\Configure;

/**
 * Formでバリデーションを統一
 */
class SampleForm extends AppForm {
    public $mailSetting = [
        'test' => [
            'auto_line_break' => true, //メールの自動改行する
            'from' => 'test+from@caters.co.jp',
            'to_admin' => 'test+to@caters.co.jp',
            'name' => '送信元名',
            'subject_admin' => '【テスト】お問い合わせがありました。', //ない場合は管理者送信しない
            'subject_user' => '【テスト】お問い合わせありがとうございました。', //ない場合はユーザー送信しない
            'template_admin' => 'contact_admin',
            'template_user' => 'contact_user'
        ],
        'honban' => [
            'auto_line_break' => true, //メールの自動改行する
            'from' => 'test+from@caters.co.jp',
            'to_admin' => 'test+to@caters.co.jp',
            'name' => '送信元名',
            'subject_admin' => 'お問い合わせがありました。', //ない場合は管理者送信しない
            'subject_user' => 'お問い合わせありがとうございました。', //ない場合はユーザー送信しない
            'template_admin' => 'contact_admin',
            'template_user' => 'contact_user'
        ]
    ];

    //バリデ前の変換
    public function _beforeExecure($post_data) {
        // $post_data['test'] = 'test';
        return parent::_beforeExecure($post_data);
    }

    /**
     * DB保存
     * Modelを分けて保存できる
     */
    protected function saveDB() {
        $data = $this->post_data;

        $result = true;
        $Model1 = TableRegistry::getTableLocator()->get('Model1');
        $remake = [
            'id' => $data['id'],
            'name' => $data['name'],
            'status' => 'publish',
        ];
        $entity = $Model1->newEntity($remake);
        $errors = $entity->getErrors();
        if ($errors) {
            $this->setErrors($errors);
            return false;
        }
        return $Model1->save($entity);
    }
}
