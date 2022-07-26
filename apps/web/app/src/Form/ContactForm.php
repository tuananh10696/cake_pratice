<?php

namespace App\Form;

use Cake\Mailer\Email;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\TransportFactory;
use App\Utils\CustomUtility;

class ContactForm extends Form
{

    public $list_cat = [
        1 => '当社に関するお問い合わせ',
        2 => '取材に関するお問い合わせ',
        3 => '採用に関するお問い合わせ',
    ];

    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('name', 'string')
            ->addField('email', 'string')
            ->addField('detail', 'string')
            ->addField('category', 'int')
            ->addField('is_accept', 'int');
    }

    public function _buildValidator(Validator $validator)
    {

        $validator
            ->notBlank('name', 'お名前をご入力ください')
            ->notEmptyString('name', 'お名前をご入力ください')
            ->maxLength('name', 30, '30字以内でご入力ください');

        $validator
            ->notBlank('email', 'メールアドレスをご入力ください')
            ->notEmptyString('email', 'メールアドレスをご入力ください')
            ->maxLength('email', 100, '100字以内でご入力ください')
            ->add(
                'email',
                [
                    'custom' => [
                        'rule' => function ($value, $context) {
                            $v  = str_replace(['&nbsp;', ' ', ' '], '', $value);
                            if (!preg_match("/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/u", $v)) {
                                return 'メールアドレスを正しくご入力ください';
                            }
                            return true;
                        },
                    ],
                ],
            );
        $validator
            ->notBlank('detail', 'お問い合わせ内容をご入力ください')
            ->notEmptyString('detail', 'お問い合わせ内容をご入力ください')
            ->maxLength('detail', 1000, '1000字以内でご入力ください');

        $validator
            ->integer('category')
            ->allowEmpty('category', 'お問い合わせ種別をご選択ください')
            ->add(
                'category',
                [
                    'custom' => [
                        'rule' => function ($value, $context) {
                            if (intval($value) == 0) {
                                return 'お問い合わせ種別をご選択ください';
                            }
                            return true;
                        },
                    ],
                ],
            );

        $validator
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
            );

        return $validator;
    }


    public function checkEmail($value, $context)
    {

        return (bool) preg_match('/\A[a-zA-Z0-9_-]([a-zA-Z0-9_\!#\$%&~\*\+-\/\=\.]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,20})\z/', $value);
    }


    public function checkPostcode($value, $context)
    {

        return (bool) preg_match('/[0-9]{3}-[0-9]{4}/', $value);
    }


    protected function _execute(array $data)
    {
        // 文字化け対応
        $data['detail'] = CustomUtility::_preventGarbledCharacters($data['detail']);

        $cc =  isset($this->list_cat[$data['category']]) ? $this->list_cat[$data['category']] : '採用に関するお問い合わせ';

        // メールを送信する 
        $info_email = new Email();
        $info_email->setCharset('ISO-2022-JP-MS');
        $info_email
            ->template('admin_contact')
            ->emailFormat('text')
            ->setViewVars(['value' => $data])
            // ->setFrom(['grp-inquiry@blocksmithand.co.jp' => '株式会社BLOCKSMITH&Co.'])
            // ->setTo('grp-inquiry@blocksmithand.co.jp')
            ->setFrom(['develop+blocksmith@caters.co.jp' => '株式会社BLOCKSMITH&Co.'])
            ->setTo('develop+blocksmith@caters.co.jp')
            ->setSubject('【BLOCKSMITH&Co.】' . $cc)
            ->send();

        return true;
    }
}
