<?php

namespace App\Model\Entity;

class Contact extends AppEntity {
    protected $_virtual = ['contact_type_array', 'zip1', 'zip2'];

    // protected function _getBirthdays() {
    //     $data = $this->_properties;

    //     $birthday = $data['birthday'] ?? '';
    //     if (!$birthday) {
    //         return [];
    //     }

    //     return [
    //         'year' => $birthday->format('Y'),
    //         'month' => $birthday->format('m'),
    //         'day' => $birthday->format('d'),
    //     ];
    // }

    protected function _getContactTypeArray() {
        $data = $this->_properties;
        return explode(',', ($data['contact_type_ids'] ?? ''));
    }

    protected function _getZip1() {
        $data = $this->_properties;
        $zip = $data['zip'] ?? '';
        if (!$zip) {
            return '';
        }
        $zips = explode('-', ($zip));
        return $zips[0] ?? '';
    }

    protected function _getZip2() {
        $data = $this->_properties;
        $zip = $data['zip'] ?? '';
        if (!$zip) {
            return '';
        }
        $zips = explode('-', ($zip));
        return $zips[1] ?? '';
    }
}
