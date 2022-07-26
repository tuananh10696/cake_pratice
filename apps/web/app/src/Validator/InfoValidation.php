<?php
namespace App\Validator;

use Cake\Validation\Validation;
use App\Model\Table\InfosContentsTable;
use App\Model\Entity\Info;

class InfoValidation {
    public static function checkFilename($value, $context) {
        // if (array_key_exists('block_type',$context['data'])) {
        //     if ($context['data']['block_type'] == UserInfo::BLOCK_TYPE_FILE) {
        //         pr($value);exit;
        //        if (preg_match('/^[a-zA-Z][a-zA-Z0-9\-_]{2,29}$/', $value)) {
        //             return true;
        //         } else {
        //             return false;
        //         }
        //     }
        // }

        return true;
    }

    public static function checkFileEmpty($value, $context) {
        if (array_key_exists('block_type', $context['data'])) {
            if ($context['data']['block_type'] == Info::BLOCK_TYPE_FILE) {
                if (empty($value)) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
}
