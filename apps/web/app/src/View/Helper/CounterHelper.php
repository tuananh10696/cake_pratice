<?php
namespace App\View\Helper;

class CounterHelper extends AppHelper {
    public $count = 0;
    public $parent = '';

    public function set($parent = '') {
        $this->parent = $parent;
    }

    public function countUP($count = 0) {
        if ($count) {
            $this->count = $count;
        } else {
            $this->count += 1;
        }
    }

    public function name($name) {
        return $this->parent . '.' . $this->count . '.' . $name;
    }
}
