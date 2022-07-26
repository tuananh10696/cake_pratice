<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;

class PreviewInfosTable extends InfosTable {
    // テーブルの初期値を設定する
    public $defaultValues = [
        'id' => null,
        'position' => 0,
        'status' => 'draft'
    ];

    // 新CMSの枠ブロックを使う場合の設定
    public $useHierarchization = [
        'contents_table' => 'preview_info_contents',
        'contents_id_name' => 'info_content_id',
        'sequence_model' => 'SectionSequences',
        'sequence_table' => 'section_sequence',
        'sequence_id_name' => 'section_sequence_id'
    ];
}
