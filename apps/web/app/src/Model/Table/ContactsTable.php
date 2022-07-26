<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ContactsTable extends AppTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
        'position' => 0,
        'status' => 'draft'
    ];

    public $attaches = array(
        'images' => array(
            // 'image_1' => array(
            //     'extensions' => array('jpg', 'jpeg', 'gif', 'png'),
            //     'width' => 1200,
            //     'height' => 1200,
            //     'file_name' => 'img1_%d_%s',
            //     'thumbnails' => array(
            //         's' => array(
            //             'prefix' => 's_',
            //             'width' => 320,
            //             'height' => 240
            //         )
            //     ),
            // ),
            //image_1
        ),
        'files' => array(),
    );

    //
    public function initialize(array $config) {
        // 添付ファイル
        // $this->addBehavior('BinaryFileAttache');

        parent::initialize($config);
    }

    public function putLabelOther($post_data) {
        $this->MstLists = TableRegistry::getTableLocator()->get('MstLists');
        $list = $this->MstLists->find('all')->order(['position' => 'ASC', ])->toArray();
        $list = Hash::combine($list, '{n}.id', '{n}.ltrl_nm', '{n}.list_slug');
        $this->list = $list;

        $contact_type_array = $post_data['contact_type_array'] ?? [];
        $relationship_id = $post_data['relationship_id'] ?? 0;
        if ($contact_type_array) {
            $c_type_list = array_intersect_key($this->list['c_type_list'], array_combine($contact_type_array, $contact_type_array));
            $post_data['contact_type_label'] = implode(', ', array_values($c_type_list));
        }
        $post_data['relationship_label'] = $this->list['c_relationship_list'][$relationship_id] ?? '';

        return $post_data;
    }
}
