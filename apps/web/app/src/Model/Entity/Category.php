<?php

namespace App\Model\Entity;

use App\Utils\CustomUtility;

class Category extends AppEntity {
    const IDENTIFIER = 'col'; // css style clsss名

    protected $_virtual = ['jp_name'];

    public function _getJpName() {
        $name = $this->name;
        if (!$name) {
            return '';
        }

        $list = CustomUtility::getPrefList();
        $name = $list[$name] ?? $name;

        return $name;
    }
}
