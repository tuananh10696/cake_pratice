<?php

namespace App\Model\Entity;

class InfoAppendItem extends AppEntity {
    protected $_virtual = ['checkbox_array'];

    protected function _getCheckboxArray() {
        $data = $this->_properties;
        return explode(',', ($data['value_text'] ?? ''));
    }
}
