<?php
namespace App\Validator;

use Cake\Validation\Validation;
use App\Model\Table\MstListsTable;

class MstListValidation {
    public static function isUnique($value, $context) {
        $field = $context['field'];
        $table = $context['providers']['table'];

        $id = 0;
        if (array_key_exists('id', $context['data'])) {
            $id = $context['data']['id'];
        }
        $cond = [
            "MstLists.{$field}" => $value,
        ];

        if ($field == 'ltrl_val') {
            $cond['MstLists.use_target_id'] = $context['data']['use_target_id'];
        }

        if ($id) {
            $cond['MstLists.id !='] = $id;
        }

        $count = $table->find()->where($cond)->count();

        if ($count == 0) {
            return true;
        } else {
            return false;
        }
    }
}
