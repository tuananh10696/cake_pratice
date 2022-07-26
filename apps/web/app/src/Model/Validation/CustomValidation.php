<?php
namespace App\Model\Validation;

use Cake\Validation\Validation;

class CustomValidation extends Validation {
    public function checkEmail($value, $context) {
        return (bool) preg_match('/\A[a-zA-Z0-9_-]([a-zA-Z0-9_\!#\$%&~\*\+-\/\=\.]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,20})\z/', $value);
    }

    public function checkPostcode($value, $context) {
        return (bool) preg_match('/^([0-9]{7})|([0-9]{3}-[0-9]{4}\z)/', $value);
    }

    public function checkTel($value, $context) {
        return (bool) preg_match("/^(0\d{6,11})|(0[0-9\-]{6,7}-[0-9]{3,4}\z)/", $value);
    }

    public function checkBirthday($value, $context) {
        return (bool) preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}\z/', $value);
    }

    public function checkPolicy($value, $context) {
        return (bool) $value == 1;
    }

    //活動日を確認
    public function selectedDesiredActivityDate($value, $context) {
        $data = $context['data'];

        return (bool) ($data['volunteer_schedules']['_ids'] ?? []);
    }

    //targetのうちどれかが入力されていたらどっちも確認。
    public function isset2allMatches($value, $rule, $target, $context) {
        $data = $context['data'];
        $targets = explode(',', $target);
        foreach ($targets as $target) {
            //セットされてないならOK
            if (!array_key_exists($target, $data)) {
                return true;
            }
            $val = $data[$target] ?? '';
            if ((!$val || !preg_match($rule, $val))) {
                return false;
            }
        }
        return true;
    }

    //全て空、または全てマッチしているか
    public function allowEmpty_or_allMatches($value, $rule, $target, $context) {
        $data = $context['data'];
        $targets = explode(',', $target);
        $text = '';
        foreach ($targets as $value) {
            $text .= $data[$value];
        }
        if ($text == '') {
            return true;
        } else {
            return $this->notEmpty_allMatches(null, $rule, $target, $context);
        }
        return false;
    }

    //全て入力されていて、全てがマッチしているか
    public function notEmpty_allMatches($value, $rule, $target, $context) {
        $data = $context['data'];
        $targets = explode(',', $target);
        foreach ($targets as $value) {
            $val = $data[$value];
            if (empty($val)) {
                return false;
            }
            if ((!preg_match($rule, $val))) {
                return false;
            }
        }
        return true;
    }

    //同じ値かどうか
    public function compare($value, $target, $context) {
        $data = $context['data'][$target] ?? '';
        return (bool) ($data == $value);
    }

    public function getExtension($filename) {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }

    //指定されている拡張子かどうか
    public function checkFile($value, $extentions, $context) {
        $name = $value['name'] ?? '';
        //$name = $context['data'][$attachesname]['name'] ?? '';
        $extention = self::getExtension($name);

        //ファイルがない場合
        if (!$extention) {
            return true;
        }

        return (bool) in_array($extention, $extentions);
    }

    //サイズ
    public function checkFileSize($value, $maxSize) {
        $size = $value['size'] ?? 0;

        //ファイルがない場合
        if (!$size) {
            return true;
        }

        return intval($size) < intval($maxSize);
    }

    public function checkSpouse($value, $context) {
        // 1 == 配偶者あり 
        if($value == 1) {
            if (
                $context['data']['spouse_text'] == ''
                || mb_strlen($context['data']['spouse_text']) > 3
                || !preg_match("/^[0-9]+$/", $context['data']['spouse_text'])
            ) {
                return false;
            }
        }

        return true;
    }

    public function checkDesiredPlace($value, $context) {
        // dd($value, $context);
        // 2 == 県内
        if($value == 2) {
            if (
                $context['data']['desired_place_text1'] == ''
                || mb_strlen($context['data']['desired_place_text1']) > 10
            ) {
                return false;
            }
        } elseif ($value == 3) {
            if (
                $context['data']['desired_place_text2'] == ''
                || mb_strlen($context['data']['desired_place_text2']) > 10
            ) {
                return false;
            }
        }

        return true;
    }

    // public function checkDesiredPlaceOut($value, $context) {
    //     // 3 == 県外
        

    //     return true;
    // }

    public function checkDesiredCrop($value, $context) {
        // 99 == その他　
        if ($value == 99) {
            if (
                $context['data']['desired_crop_text'] == ''
                || mb_strlen($context['data']['desired_crop_text']) > 20
            ) {
                return false;
            }
        }

        return true;
    }
}
