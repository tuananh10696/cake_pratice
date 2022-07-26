<?php
namespace App\View\Helper;

use Cake\View\Helper\FormHelper;
use Cake\Datasource\ModelAwareTrait;

class MyFormHelper extends FormHelper {
    use ModelAwareTrait;

    /**
     * 確認画面でのhidden保存用
     */
    public function output_hidden($data = null, $parent = '') {
        $data = $data ? $data : $this->request->data;
        foreach ($data as $column => $val) {
            $column = $parent ? $parent . '.' . $column : $column;
            if (is_array($val)) {
                $this->output_hidden($val, $column);
            } else {
                echo parent::hidden($column);
            }
        }
    }

    /**
     * 値取得
     */
    public function get_value($key) {
        return $this->request->getData($key);
    }

    /**
     * リダイレクトすることでリセットする
     */
    public function resetbutton($getQuery, $anti) {
        $url = $this->Url->build(['?' => array_diff_key($getQuery, array_combine($anti, $anti))]);
        return "<a class='btnDefault' href='" . $url . "'>条件クリア</a>";
    }

    public function get_form($fieldName, $getQuery, $options = array()) {
        $options = array_merge([
            'type' => 'get',
            'label' => false,
            'error' => null,
            'required' => null,
            'options' => null,
            'templates' => [],
            'templateVars' => [],
            'labelOptions' => true,
            'validator' => 'default'
        ], $options);

        echo parent::create($fieldName, $options);

        foreach ($getQuery as $key => $value) {
            if ($value) {
                echo $this->input($key, ['value' => $value, 'type' => 'hidden']);
            }
        }
    }

    public function input($fieldName, array $options = []) {
        $options = array_merge([
            'type' => null,
            'label' => false,
            'error' => null,
            'required' => null,
            'options' => null,
            'templates' => [],
            'templateVars' => [],
            'labelOptions' => true
        ], $options);

        return parent::control($fieldName, $options);
    }

    /**
     * Tableから画像の推奨サイズを取得
     * @param  [type] $model     [description]
     * @param  [type] $column    [description]
     * @param  string $prefix    [description]
     * @param  string $separator [description]
     * @param  array  $options   [description]
     * @return [type]            [description]
     */
    public function getRecommendSize($model, $column, $options = []) {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel($model);

        $options = array_merge([
            'prefix' => '',
            'separator' => ' x ',
            'before' => '',
            'after' => ''
        ], $options);
        extract($options);

        $strSize = '';

        if (!empty($this->{$model}->recommend_size_display)) {
            $config = $this->{$model}->recommend_size_display;
            $attaches = $this->{$model}->attaches['images'];

            if (array_key_exists($column, $config) && array_key_exists($column, $attaches)) {
                if ($config[$column] === true) {
                    if ($prefix == '') {
                        $strSize = "{$attaches[$column]['width']}{$separator}{$attaches[$column]['height']}";
                    } elseif (array_key_exists($prefix, $attaches[$column]['thumbnails'])) {
                        $tmp = $attaches[$column]['thumbnails'][$prefix];
                        $strSize = "{$tmp['width']}{$separator}{$tmp['height']}";
                    }
                } elseif (is_array($config[$column])) {
                    $strSize = "{$config[$column]['width']}{$separator}{$config[$column]['height']}";
                } elseif ($config[$column] !== false) {
                    $strSize = $config[$column];
                }
                $strSize = $before . $strSize . $after;
            }
        }
        return $strSize;
    }
}
