<?php
namespace App\Validator;

use Cake\Validation\Validation;
use App\Model\Table\UsersTable;

/**
 * テーブル「Users」用のバリデーション
 */
class MemberValidation {
    public static function isUnique($value, $context) {
        $field = $context['field'];
        $table = $context['providers']['table'];

        $id = 0;
        if (array_key_exists('id', $context['data'])) {
            $id = $context['data']['id'];
        }
        $cond = [
            'Users.id !=' => $id,
            "Users.{$field}" => $value
        ];

        $count = $table->find()->where($cond)->count();

        if ($count == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkPasswordRule($value, $context) {
        $pattern = '/\A(?=\d{0,99}+[a-zA-Z\-_#\.])(?=[\-_#\.]{0,99}+[a-zA-Z\d])(?=[a-zA-Z]{0,99}+[\d\-_#\.])[a-zA-Z\d\-_#\.]{1,100}+\z/i';
        if (preg_match($pattern, $value)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkUsername($value, $context) {
        if (preg_match('/^[a-zA-Z][a-zA-Z0-9\-_@#]{2,29}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }
}
