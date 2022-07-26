<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ModelAwareTrait;
use Cake\Utility\Inflector;
use Cake\Network\Exception\NotFoundException;
use App\Model\Entity\Info;
use App\Utils\CustomUtility;
use Cake\I18n\Date;

/**
 * OutputHtml component
 */
class CmsComponent extends Component {
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    use ModelAwareTrait;

    public function initialize(array $config) {
        $this->Controller = $this->_registry->getController();
        $this->Session = $this->Controller->getRequest()->getSession();

        $this->loadModel('Infos');
        $this->loadModel('PageConfigs');
        $this->loadModel('InfoTops');
    }

    public function findAll($slug, $options = [], $paginate = []) {
        // page_config
        $page_config = $this->PageConfigs->find()->where(['PageConfigs.slug' => $slug])->first();
        if (empty($page_config)) {
            return null;
        }

        // デフォルトオプション
        $default_cond = [];

        //権限あればプレビュー可能
        $can_preview = ($this->isLogin() && $this->request->getQuery('preview') == 'on');
        if (!$can_preview) {
            $default_cond['Infos.status'] = 'publish';
        }

        $default_contain = [
            'PageConfigs',
            'InfoAppendItems' => function ($q) {
                return $q->contain(['AppendItems' => ['MstOptions']])->order(['AppendItems.position' => 'ASC']);
            },
        ];

        //
        if ($page_config->is_category == 'Y') {
            if ($page_config->is_category_multiple == 1) {
            } else {
                $default_contain = array_merge($default_contain, [
                    'Categories',
                ]);
                $default_cond['Categories.status'] = 'publish';
            }
        }

        //自動掲載期間を確認する。
        $validation_public_date = $page_config['is_public_date'] ?? false;
        if ($validation_public_date) {
            $today = new \DateTime();
            $default_cond[] = [
                [
                    'OR' => [
                        'Infos.start_datetime is' => null,
                        'Infos.start_datetime <=' => $today
                    ]
                ],
                [
                    'OR' => [
                        'Infos.end_datetime is' => null,
                        'Infos.end_datetime >=' => $today
                    ]
                ]
            ];
        }

        //並び順変更できないInfoなら、初期並び順を日付にする。
        $disable_position_order = $page_config->disable_position_order ?? false;
        if ($disable_position_order) {
            $order = ['Infos.start_datetime' => 'DESC'];
        } else {
            $order = ['Infos.position' => 'ASC'];
        }
        // if (!empty($page_config->ad_find_order_callback)) {
        //     $order = $this->{$page_config->ad_find_order_callback}();
        // }
        
        // オプション
        $options = array_merge([
            'limit' => null,
            'paginate' => false,
            'conditions' => $default_cond,
            'append_cond' => [],
            'contain' => $default_contain,
            'order' => $order,
            'join' => [],
            'join_cond' => [],
            'group' => '',
        ], $options);

        $options['contain'] = array_merge($default_contain, $options['contain']);

        // ページネーションオプション
        if ($options['paginate']) {
            $this->Controller->paginate = array_merge([
                'order' => $options['order'],
                'limit' => $options['limit'],
                'contain' => $options['contain'],
                'paramType' => 'querystring',
                'url' => [
                    'sort' => null,
                    'direction' => null
                ]
            ], $paginate);
        }

        // find設定
        $cond = ['PageConfigs.slug' => $slug];
        if (!empty($options['conditions'])) {
            $cond = array_merge(
                $cond,
                $options['conditions'],
            );
        }
        if (!empty($options['append_cond'])) {
            $cond = array_merge(
                $cond,
                $options['append_cond']
            );
        }

        if ($options['paginate']) {
            $query = $this->Infos->find()->where($cond);

            if($options['contain']){
                $query->contain($options['contain']);
            }

            if($options['join']) {
                $query->join($options['join']);
            }

            if ($options['join_cond']) {
                $query->where($options['join_cond']);
            }

            if ($options['group']) {
                $query->group($options['group']);
            }

            return $this->Controller->paginate($query);
        }

        $query = $this->Infos->find();

        $query = $query->where($cond)->contain($options['contain']);

        if($options['join']) {
            $query->join($options['join']);
        }

        if ($options['join_cond']) {
            $query->where($options['join_cond']);
        }

        if ($options['group']) {
            $query->group($options['group']);
        }

        if ($options['limit']) {
            $query->limit($options['limit']);
        }
        if ($options['order']) {
            $query->order($options['order']);
        }
        
        $query = $query->all();

        return $query;
    }

    public function findTop($slug, $options = []) {
        $options = array_merge([
            'limit' => null,
            'order' => ['InfoTops.position' => 'ASC']
        ], $options);

        $contain = [
            'Infos',
            'PageConfigs'
        ];

        $cond = [
            'Infos.status' => 'publish',
            'PageConfigs.slug' => $slug
        ];

        $query = $this->InfoTops->find()
                               ->where($cond)
                               ->contain($contain)
                               ->order($options['order']);
        if ($options['limit']) {
            $query->limit($options['limit']);
        }

        $data = $query->all();

        if ($data->isEmpty()) {
            return [];
        }
        return $data;
    }

    public function findFirst($slug, $info_id, $options = []) {
        $entity = $this->_detail($slug, $info_id, $options);
        if (empty($entity)) {
            return null;
        }

        $option['section_block_ids'] = array_keys(Info::BLOCK_TYPE_WAKU_LIST);
        $data = $this->toHierarchization($info_id, $entity, $option);

        //目次用
        if ($entity->view_table_content) {
            $is_view_h3 = true;//h2 > h3の順番で並べる。
            $table_contents = $this->getTableContent($entity, $is_view_h3);
        } else {
            $table_contents = [];
        }

        return [
            'table_contents' => $table_contents,
            'contents' => $data['contents']['contents'] ?? [],
            'content_count' => $data['content_count'],
            'info' => $entity
        ];
    }

    public function getTableContent($entity, $is_view_h3 = false) {
        $table_contents = [];
        if ($is_view_h3) {
            foreach (($entity->info_contents ?? []) as $_ => $content) {
                if ($content['block_type'] == Info::BLOCK_TYPE_TITLE_H2) {
                    $table_contents[]['h2'] = [
                        'id' => '#content-' . $content['id'],
                        'name' => $content['title']
                    ];
                }
                if ($content['block_type'] == Info::BLOCK_TYPE_TITLE) {
                    $keys = array_keys($table_contents);
                    if (!$keys) {
                        continue;
                    }
                    $last_h2 = end($keys);
                    $table_contents[$last_h2]['h3s'][] = [
                        'id' => '#content-' . $content['id'],
                        'name' => $content['title']
                    ];
                }
            }
        } else {
            foreach (($entity->info_contents ?? []) as $_ => $content) {
                if ($content['block_type'] == Info::BLOCK_TYPE_TITLE_H2) {
                    $table_contents[$content['title']] = '#content-' . $content['id'];
                }
            }
        }
        return $table_contents;
    }

    public function isLogin() {
        return CustomUtility::isLogin();
    }

    private function _detail($slug, $info_slug_id, $options = []) {
        // page_config
        $page_config = $this->PageConfigs->find()->where(['PageConfigs.slug' => $slug])->first();
        if (empty($page_config)) {
            return null;
        }

        // デフォルトオプション
        $id_cond = ['Infos.id' => 0];
        $info_id = intval($info_slug_id);
        if ($info_id) {
            $id_cond = ['Infos.id' => $info_id];
        } elseif (is_string($info_slug_id)) {
            $id_cond = ['Infos.slug' => h($info_slug_id)];
        }
        $default_cond = [
            'Infos.page_config_id' => $page_config->id,
        ] + $id_cond;

        //権限あればプレビュー可能
        $can_preview = ($this->isLogin() && $this->request->getQuery('preview') == 'on');
        if (!$can_preview) {
            $default_cond['Infos.status'] = 'publish';
        }

        $default_contain = [
            'PageConfigs',
            'InfoAppendItems' => function ($q) {
                return $q->contain(['AppendItems' => ['MstOptions']])->order(['AppendItems.position' => 'ASC']);
            },
            'InfoContents' => function ($q) {
                return $q->order(['InfoContents.position' => 'ASC'])->contain(['SectionSequences']);
            }
        ];

        //
        if ($page_config->is_category == 'Y') {
            if ($page_config->is_category_multiple == 1) {
            } else {
                $default_contain[] = 'Categories';
                $default_cond['Categories.status'] = 'publish';
            }
        }

        //自動掲載期間を確認する。
        $validation_public_date = $page_config['is_public_date'] ?? false;
        if ($validation_public_date && !$can_preview) {
            $today = new \DateTime();
            $default_cond[] = [
                [
                    'OR' => [
                        'Infos.start_datetime is' => null,
                        'Infos.start_datetime <=' => $today
                    ]
                ],
                [
                    'OR' => [
                        'Infos.end_datetime is' => null,
                        'Infos.end_datetime >=' => $today
                    ]
                ]
            ];
        }

        $options = array_merge([
            'conditions' => $default_cond,
            'contain' => $default_contain,
            'append_cond' => []
        ], $options);
        $options['contain'] = array_merge($default_contain, $options['contain']);

        $cond = $options['conditions'];
        if (!empty($options['append_cond'])) {
            $cond += $options['append_cond'];
        }

        $query = $this->Infos->find()->where($cond)->contain($options['contain']);

        return $query->first();
    }

    public function toHierarchization($id, $entity, $options = []) {
        // $options = array_merge([
        //     'section_block_ids' => [10]
        // ], $options);
        $data = $this->request->getData();
        $content_count = 0;
        $contents = [
            'contents' => []
        ];

        $contents_table = $this->Infos->useHierarchization['contents_table'];
        $contents_id_name = $this->Infos->useHierarchization['contents_id_name'];

        $sequence_table = $this->Infos->useHierarchization['sequence_table'];
        $sequence_id_name = $this->Infos->useHierarchization['sequence_id_name'];
        // if ($id && $entity->has($contents_table)) {
        if (!empty($entity->{$contents_table})) {
            $content_count = count($entity->{$contents_table});
            $block_count = 0;
            foreach ($entity->{$contents_table} as $k => $val) {
                $v = $val->toArray();

                // 枠ブロックの中にあるブロック以外　（枠ブロックも対象）
                if (!$v[$sequence_id_name] || ($v[$sequence_id_name] > 0 && in_array($v['block_type'], $options['section_block_ids']))) {
                    $contents['contents'][$block_count] = $v;
                    $contents['contents'][$block_count]['_block_no'] = $block_count;
                } else {
                    // 枠ブロックの中身
                    if (!array_key_exists($sequence_table, $v)) {
                        continue;
                    }
                    $sequence_id = $v[$sequence_id_name];
                    // if (!array_key_exists($block_count, $contents['contents'])) {
                    //     continue;
                    // }
                    $waku_number = false;
                    foreach ($contents['contents'] as $_no => $_v) {
                        if (in_array($_v['block_type'], $options['section_block_ids']) && $sequence_id == $_v[$sequence_id_name]) {
                            $waku_number = $_no;
                            break;
                        }
                    }
                    if ($waku_number === false) {
                        continue;
                    }

                    if (!array_key_exists('sub_contents', $contents['contents'][$waku_number])) {
                        $contents['contents'][$waku_number]['sub_contents'] = null;
                    }
                    $contents['contents'][$waku_number]['sub_contents'][$block_count] = $v;
                    $contents['contents'][$waku_number]['sub_contents'][$block_count]['_block_no'] = $block_count;
                }
                $block_count++;
            }
        }
        //  else {
        //     if (array_key_exists($contents_table, $data)) {
        //         $contents['contents'] = $data[$contents_table];
        //         $content_count = count($data[$contents_table]);
        //     }
        // }
        return [
            'contents' => $contents,
            'content_count' => $content_count
        ];
    }
}
