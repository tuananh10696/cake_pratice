<?php

namespace App\Controller\UserAdmin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;
use App\Model\Entity\Info;
use App\Model\Entity\PageConfig;
use App\Model\Entity\PageConfigItem;
use App\Model\Entity\AppendItem;
use App\Model\Entity\PageConfigExtension;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class InfosController extends AppController
{
    private $list = [];
    private $GQuery = [];

    public function initialize()
    {
        parent::initialize();

        $this->Infos = $this->getTableLocator()->get('Infos');
        $this->InfoContents = $this->getTableLocator()->get('InfoContents');
        $this->SectionSequences = $this->getTableLocator()->get('SectionSequences');
        $this->PageConfigs = $this->getTableLocator()->get('PageConfigs');
        $this->PageConfigItems = $this->getTableLocator()->get('PageConfigItems');
        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');
        $this->Categories = $this->getTableLocator()->get('Categories');
        $this->Tags = $this->getTableLocator()->get('Tags');
        $this->InfoTags = $this->getTableLocator()->get('InfoTags');
        $this->InfoAppendItems = $this->getTableLocator()->get('InfoAppendItems');
        $this->AppendItems = $this->getTableLocator()->get('AppendItems');
        $this->MstLists = $this->getTableLocator()->get('MstLists');
        $this->UseradminSites = $this->getTableLocator()->get('UseradminSites');
        $this->InfoCategories = $this->getTableLocator()->get('InfoCategories');
        $this->InfoTops = $this->getTableLocator()->get('InfoTops');

        $this->loadComponent('OutputHtml');

        $this->modelName = 'Infos';
        $this->set('ModelName', $this->modelName);
    }

    public function beforeFilter(Event $event)
    {
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('user');

        $this->setCommon();
        $this->getEventManager()->off($this->Csrf);
    }

    //関連記事もとの取得
    public function getRelatedInfo($page_config)
    {
        $related_info_id = $this->request->getQuery('relation_info_id') ?? 0;
        $related_base_info = $this->Infos->find()->where(['Infos.id' => $related_info_id])->contain(['PageConfigs'])->first();
        $related_base = [];
        if ($related_base_info) {
            $related_base_url = $this->exUrl('/user_admin/infos/edit/' . $related_info_id, ['sch_page_id' => $related_base_info->page_config_id, 'sch_category_id' => $related_base_info->category_id]);
            $related_base = ['url' => $related_base_url, 'name' => $related_base_info->page_config->page_title];
        }
        $is_related_info = (in_array($page_config['slug'], ['exam_pref'])); //記事関連のページ
        $this->is_related_info = $is_related_info;
        $this->set(compact('is_related_info', 'related_base'));
    }

    public function index()
    {
        $this->checkLogin();

        $this->setList();

        $query = $this->_getQuery();

        $this->_setView($query);

        // slug
        $page_config_id = $query['sch_page_id'];

        // if (!$this->isOwnPageByUser($page_config_id)) {
        //     $this->Flash->set('不正なアクセスです');
        //     $this->redirect('/user_admin/');
        //     return;
        // }

        $page_config = $this->PageConfigs->find()
            ->where(['PageConfigs.id' => $page_config_id])
            ->contain([
                'SiteConfigs' => function ($q) {
                    return $q->select('slug');
                },
                'PageConfigItems',
                'PageConfigExtensions' => function ($q2) {
                    return $q2->where(['PageConfigExtensions.status' => 'publish'])->order(['PageConfigExtensions.position' => 'ASC']);
                }
            ])
            ->first();
        $preview_slug_dir = '';
        $page_title = '';
        if (!empty($page_config)) {
            $preview_slug_dir = $page_config->site_config->slug . DS . ($page_config->slug ? $page_config->slug . DS : '');
            $page_title = $page_config->page_title;
        } else {
            $preview_slug_dir = '';
        }

        //関連記事もとの取得
        $this->getRelatedInfo($page_config);

        $preview_slug_dir = str_replace('__', '/', $preview_slug_dir);

        //選択しているカテゴリーと、その子、全ての親を取得する。
        $getCategoryList_relations = function ($page_config, $query) {
            if ($page_config->is_category != 'Y') {
                return ['allowed_view_data' => true];
            }

            //カテゴリーリスト
            $category_list = $this->Categories->getCategoryList($query['sch_category_id'], $query['sch_page_id']);

            //すべてを許可する。(選択されていなくていい)
            $allowed_no_selected = $page_config->need_all_category_select;
            if ($allowed_no_selected) {
                return ['allowed_view_data' => true, 'category_list' => $category_list];
            }

            //カテゴリーが登録されていない
            $isset_category_list = (bool) $category_list;
            if (!$isset_category_list) {
                return ['allowed_view_data' => false, 'category_list' => $category_list];
            }

            //全部の親子カテゴリーが表示されていないなら、データを表示しない
            $isnot_viewallcateogry = ((count($category_list) < $page_config->max_multilevel));
            if ($isnot_viewallcateogry) {
                return ['allowed_view_data' => false, 'category_list' => $category_list];
            }

            //選択されていない子カテゴリーがあればデータを表示しない
            $has_empty_content = $category_list[array_key_last($category_list)]['empty'] ?? false;
            if ($has_empty_content) {
                return ['allowed_view_data' => false, 'category_list' => $category_list];
            }

            return ['allowed_view_data' => true, 'category_list' => $category_list];
        };
        $result = $getCategoryList_relations($page_config, $query);
        $allowed_view_data = $result['allowed_view_data'] ?? false;
        $category_list = $result['category_list'] ?? [];
        $this->set(compact('category_list', 'allowed_view_data'));

        // ページ設定拡張
        $list_buttons = [];
        $page_buttons = [
            'left' => [],
            'right' => []
        ];
        if (!empty($page_config->page_config_extensions)) {
            foreach ($page_config->page_config_extensions as $ex) {
                if ($ex->type == PageConfigExtension::TYPE_LIST_BUTTON) {
                    $list_buttons[] = $ex;
                } elseif ($ex->type == PageConfigExtension::TYPE_PAGE_BUTTON) {
                    $page_buttons[$ex->option_value][] = $ex;
                }
            }
        }

        $this->set(compact('list_buttons', 'page_buttons'));
        $this->set(compact('preview_slug_dir', 'page_title', 'query', 'page_config'));

        $cond = array();
        $cond = $this->_getConditions($query, $page_config);

        $contain = [
            'PageConfigs',
            'Categories',
            'InfoAppendItems' => function ($q) {
                return $q->contain(['AppendItems'])->order(['AppendItems.position' => 'ASC']);
            }
        ];
        //並び順変更できないInfoなら、初期並び順を日付にする。
        $disable_position_order = $page_config->disable_position_order ?? false;
        if ($disable_position_order) {
            $order = [$this->modelName . '.start_datetime' => 'DESC'];
        } else {
            $order = [$this->modelName . '.position' => 'ASC'];
        }
        if (!empty($page_config->ad_find_order_callback)) {
            $order = $this->{$page_config->ad_find_order_callback}();
        }


        //
        if ($this->is_related_info) {
            $relation_info_id = $query['relation_info_id'] ?? 0;

            $cond['Infos.relation_info_id'] = $relation_info_id;
        }

        parent::_lists($cond, array(
            'order' => $order,
            'limit' => 20,
            'contain' => $contain
        ));
    }

    protected function _getQueryIndex()
    {
        return $this->_getQuery();
    }

    protected function _getQuery()
    {
        $query = [];

        //page_configの取得
        $sch_page_id = $this->request->getQuery('sch_page_id');
        $sch_page_slug = $this->request->getQuery('page_slug');
        $cond = $sch_page_id ? ['PageConfigs.id' => $sch_page_id] : ['PageConfigs.slug' => $sch_page_slug];
        $page_config = $this->PageConfigs->find()->where($cond)->first();
        if (!$page_config) {
            return $this->redirect('/user_admin/');
        }
        $sch_page_id = $page_config->id;
        $query['sch_page_id'] = $sch_page_id;

        //カテゴリーidの取得
        $sch_category_id = $this->request->getQuery('sch_category_id');
        if (!$sch_category_id) {
            //選択されていない場合に初期カテゴリーを算出する。
            if (!$page_config->need_all_category_select) {
                $category = $this->Categories->find()->where(['Categories.page_config_id' => $sch_page_id, 'Categories.multiple_level' => 1])->order(['Categories.position' => 'ASC'])->first();
                if (!empty($category)) {
                    $sch_category_id = $category->id;
                }
            } else {
                $sch_category_id = 0;
            }
        }
        $query['sch_category_id'] = $sch_category_id;

        //他
        $query['pos'] = $this->request->getQuery('pos');
        if (empty($query['pos'])) {
            $query['pos'] = 0;
        }

        $query['page'] = $this->request->getQuery('page');
        if (empty($query['page'])) {
            unset($query['page']);
        }


        if ($this->request->getQuery('relation_info_id')) {
            $query['relation_info_id'] = $this->request->getQuery('relation_info_id');
        }

        $this->GQuery = $query;

        return $query;
    }

    private function _getConditions($query, $page_config = null)
    {
        $cond = [];

        $cond['Infos.page_config_id'] = $query['sch_page_id'];

        if ($query['sch_category_id']) {
            if (!empty($page_config->is_category_multiple) && $page_config->is_category_multiple == 1) {
                $info_categories = $this->InfoCategories->find()->where(['InfoCategories.category_id' => $query['sch_category_id']])->extract('info_id');
                $info_ids = [0];
                if (!$info_categories->isEmpty()) {
                    $info_ids = $info_categories->toArray();
                }
                $cond['Infos.id in'] = $info_ids;
            } else {
                $cond['Infos.category_id'] = $query['sch_category_id'];
            }
        }

        extract($query);

        return $cond;
    }

    public function preview($id = 0)
    {
        // プレビューで保存した画像とファイルを削除
        $this->deletePreviewAttachment();

        // 画像とファイルをプレビューフォルダへコピー
        if ($id) {
            $this->distAttachmentCopy($id);
        }

        // Previewテーブルのセット
        $this->Infos->setTable('preview_infos');
        $this->InfoContents->setTable('preview_info_contents');
        $this->InfoTags->setTable('preview_info_tags');
        if ($this->InfoContents->behaviors()->has('FileAttache')) {
            $this->InfoContents->behaviors()->get('FileAttache')->config([
                'uploadDir' => UPLOAD_DIR . 'PreviewInfoContents',
                'wwwUploadDir' => '/' . UPLOAD_BASE_URL . '/' . 'PreviewInfoContents'
            ]);
        }

        $this->checkLogin();

        if (!$this->request->is(['post', 'put'])) {
            $is_valid = false;
            goto EDIT_RENDER;
        }

        $id = 0;
        $this->request->data['id'] = null;

        $is_valid = true;
        $validate = 'default';

        $query = $this->_getQuery();
        $sch_page_id = $query['sch_page_id'];

        $redirect = ['action' => 'index', '?' => $query];

        // 過去のプレビュー削除
        $this->deletePreviewSource($sch_page_id);

        $this->setList();

        $options = [
            // 'saveAll' => ['associated' => ['InfoContents']], // save時使用
            'contain' => [
                'InfoContents' => function ($q) {
                    return $q->order('InfoContents.position ASC')->contain(['SectionSequences']);
                },
                'InfoTags' => function ($q) {
                    return $q->contain(['Tags'])->order(['Tags.position' => 'ASC']);
                }
            ] // find時使用
        ];

        $page_title = 'コンテンツ';
        $page_config = null;
        if ($sch_page_id) {
            $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $sch_page_id])->contain(['SiteConfigs'])->first();
            if (!empty($page_config)) {
                $page_title = $page_config->page_title;
            }
        }

        // カテゴリリスト
        $category_list = [];
        if ($sch_page_id) {
            $category_list = $this->Categories->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                ->where(['Categories.page_config_id' => $sch_page_id])
                ->order(['Categories.position' => 'ASC'])
                ->toArray();
        }

        $this->set(compact('page_title', 'query', 'page_config', 'category_list'));

        if ($this->request->is(['post', 'put'])) {
            if (!empty($this->request->getData('title')) && $page_config->slug == 'column') {
                $title = $this->request->getData('title');
                $this->request->data['title'] = strip_tags($title);
            }

            if (empty($this->request->getData('end_datetime'))) {
                $this->request->data['end_datetime'] = DATE_ZERO;
            }

            if (empty($this->request->getData())) {
                $this->Flash->error('アップロード出来る容量を超えました');
                $is_valid = false;
                goto EDIT_RENDER;
            }

            $this->request->data['page_config_id'] = $sch_page_id;

            // カテゴリ　バリデーション
            if ($this->isCategoryEnabled($page_config)) {
                $validate = 'isCategory';
            }
            // 並び順
            if (array_key_exists('info_contents', $this->request->getData())) {
                $position = 0;

                foreach ($this->request->getData('info_contents') as $k => $v) {
                    $this->request->data['info_contents'][$k]['position'] = ++$position;
                }
            }

            // 登録者
            if (!$id) {
                $this->request->data['regist_user_id'] = $this->isLogin();
            }

            // メタキーワード
            $meta_keywords = $this->request->getData('keywords');
            if (!empty($meta_keywords)) {
                $this->request->data['meta_keywords'] = '';
                $pre = '';
                foreach ($meta_keywords as $k => $v) {
                    $v = strip_tags(trim($v));
                    if (!empty($v)) {
                        $this->request->data['meta_keywords'] .= $pre . $v;
                        $pre = ',';
                    }
                }
            } else {
                $this->request->data['meta_keywords'] = '';
            }

            $delete_ids = $this->request->getData('delete_ids');
            unset($this->request->data['delete_ids']);

            $tags = $this->request->getData('tags');
            unset($this->request->data['tags']);

            // $contents = $this->request->getData('info_contents');
            // foreach ($contents as $k => $v) {
            //     if (array_key_exists('_serialize_values', $v) && !empty($v['_serialize_values'])) {
            //         $this->request->data["info_contents"][$k]['content'] = serialize($v['_serialize_values']);
            //     }
            // }

            $options['callback'] = function ($id) use ($delete_ids, $tags, $page_config) {
                // コンテンツ削除

                // 枠の紐付け
                $q = $this->InfoContents->find()->where(['InfoContents.info_id' => $id])->order(['position' => 'ASC']);
                if (!$q->isEmpty()) {
                    $info_contents = $q->all();
                    foreach ($info_contents as $v) {
                        if (array_key_exists((int)$v['block_type'], Info::BLOCK_TYPE_WAKU_LIST)) {
                            $section_query = $this->SectionSequences->find()->where(['SectionSequences.id' => $v['section_sequence_id']]);
                            if ($section_query->isEmpty()) {
                                continue;
                            }
                            $section_entity = $section_query->first();
                            $section_entity->info_content_id = $v['id'];
                            $this->SectionSequences->save($section_entity);
                        }
                    }
                }

                // タグ
                $tag_ids = $this->saveTags($page_config->id, $tags); // マスターの登録
                if (!empty($tag_ids)) {
                    foreach ($tag_ids as $tag_id) {
                        $info_tag = $this->InfoTags->find()->where(['InfoTags.tag_id' => $tag_id, 'InfoTags.info_id' => $id])->first();
                        if (empty($info_tag)) {
                            $info_tag = $this->InfoTags->newEntity();
                            $info_tag->info_id = $id;
                            $info_tag->tag_id = $tag_id;
                            $this->InfoTags->save($info_tag);
                        }
                    }
                }
                // タグの削除

                $url = ($page_config->site_config->slug ? '/' . $page_config->site_config->slug : '');
                $url .= ($page_config->slug ? '/' . $page_config->slug : '');
                $url .= '/';
                return $this->redirect($url . 'pre-' . $id . '.html?preview=on');
            };
        } else {
            if (!$id) {
                $options['get_callback'] = function ($data) use ($query) {
                    $data['category_id'] = $query['sch_category_id'];
                    return $data;
                };
            }
        }

        $options['redirect'] = $redirect;
        $options['validate'] = $validate;

        $result = parent::_edit($id, $options);
        $this->Session->delete('Flash');

        if ($result === false) {
            $is_valid = false;
        }

        EDIT_RENDER:
        if ($query['sch_page_id'] == 3) {
            $this->render('editFaq');
        }

        if (!$is_valid) {
            return $this->render('error');
        }
    }

    public function edit($id = 0)
    {
        $this->checkLogin();

        // プレビュー機能
        if ($this->request->getData('postMode') == 'preview') {
            return $this->preview($id);
        }

        $validate = 'default';

        // 記事に許可されていないユーザがアクセスを試みた場合
        if ($id && !$this->isOwnInfoByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user_admin/');
            return;
        }

        $query = $this->_getQuery();
        $sch_page_id = $query['sch_page_id'];

        $this->setList();

        $options = [
            // 'saveAll' => ['associated' => ['InfoContents']], // save時使用
            'contain' => [
                'InfoContents' => function ($q) {
                    return $q->order('InfoContents.position ASC')->contain(['SectionSequences']);
                },
                'InfoTags' => function ($q) {
                    return $q->contain(['Tags'])->order(['Tags.position' => 'ASC']);
                },
                'InfoAppendItems' => function ($q) {
                    return $q->contain(['AppendItems'])->order(['AppendItems.position' => 'ASC']);
                },
                'InfoCategories'
            ] // find時使用
        ];

        $page_title = 'コンテンツ';
        $page_config = null;
        if ($sch_page_id) {
            $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $sch_page_id])->contain(['PageConfigExtensions', 'EnabledPageConfigItems'])->first();
            if (!empty($page_config)) {
                $page_title = $page_config->page_title;

                $page_config_items = Hash::combine($page_config->page_config_items ?? [], '{n}.item_key', '{n}', '{n}.parts_type');
                $this->set(compact('page_config_items'));

                if (!$this->isUserRole($page_config['editable_role'])) {
                    $this->Flash->set('編集権限がありません');
                    return $this->redirect('/user_admin/');
                }
            }
        }

        //関連記事もとの取得
        $this->getRelatedInfo($page_config);

        // ページ設定拡張
        $detail_extent_buttons = [];
        if (!empty($page_config->page_config_extensions)) {
            foreach ($page_config->page_config_extensions as $ex) {
                if ($ex->type == PageConfigExtension::TYPE_DETAIL_BUTTON) {
                    $detail_extent_buttons[] = $ex;
                }
            }
        }
        $this->set(compact('detail_extent_buttons'));

        // 追加入力項目
        $append_list = [];
        if ($sch_page_id) {
            $append_list = $this->AppendItems->find()->where(['page_config_id' => $sch_page_id])->order('position asc')->contain(['MstOptions'])->all();
        }

        $append_item_list = $this->getAppendList($sch_page_id);

        foreach ($append_list->toArray() as $k => $AppendItem) {
            //関連記事ようのリストを取得する。
            if ($AppendItem->value_type == AppendItem::TYPE_RELATION || $AppendItem->value_type == AppendItem::TYPE_RELATION_ONE) {
                $slug = $AppendItem->slug ?? '';

                $page = $this->PageConfigs->find()->where(['PageConfigs.slug' => $slug])->contain(['Infos'])->first();
                if ($page) {
                    $infos = Hash::combine($page->infos, '{n}.id', '{n}.title');
                    $this->set($slug . '_list', $infos);
                }
            }

            $this->set('append_custom_key_list', AppendItem::$custom_key_list);
        }

        $this->set(compact('page_title', 'query', 'page_config', 'append_list', 'append_item_list'));

        if ($this->request->is(['post', 'put'])) {
            if (empty($this->request->getData())) {
                $this->Flash->error('アップロード出来る容量を超えました');
                return $this->redirect(['action' => 'edit', $id, '?' => $query]);
            }

            $this->request->data['page_config_id'] = $sch_page_id;
            $info_category_ids = $this->request->getData('info_categories');

            // カテゴリ　バリデーション
            if ($this->isCategoryEnabled($page_config)) {
                $validate = 'isCategory';
            }
            // 並び順
            if (array_key_exists('info_contents', $this->request->getData())) {
                $position = 0;

                foreach ($this->request->getData('info_contents') as $k => $v) {
                    $this->request->data['info_contents'][$k]['position'] = ++$position;
                }
            }

            // 登録者
            if (!$id) {
                $this->request->data['regist_user_id'] = $this->getSessionUserID();
            }

            // メタキーワード
            $meta_keywords = $this->request->getData('keywords');
            if (!empty($meta_keywords)) {
                $this->request->data['meta_keywords'] = '';
                $pre = '';
                foreach ($meta_keywords as $k => $v) {
                    $v = strip_tags(trim($v));
                    if (!empty($v)) {
                        $this->request->data['meta_keywords'] .= $pre . $v;
                        $pre = ',';
                    }
                }
            } else {
                $this->request->data['meta_keywords'] = '';
            }

            $delete_ids = $this->request->getData('delete_ids');
            unset($this->request->data['delete_ids']);

            $tags = $this->request->getData('tags');
            unset($this->request->data['tags']);

            // infoAppendItemsがある場合
            if (array_key_exists('info_append_items', $this->request->getData())) {
                foreach ($this->request->getData('info_append_items') as $ap_num => $i_append_item) {
                    // 必須でないリスト対策
                    if (empty($i_append_item['value_int'])) {
                        $this->request->data['info_append_items'][$ap_num]['value_int'] = 0;
                    }

                    //チェックボックス
                    if (isset($this->request->data['info_append_items'][$ap_num]['checkbox_array'])) {
                        $checkbox_array = $this->request->data['info_append_items'][$ap_num]['checkbox_array'] ?? [];
                        $this->request->data['info_append_items'][$ap_num]['value_text'] = $checkbox_array ? implode(',', $checkbox_array) : '';
                    }
                }
            }

            // $contents = $this->request->getData('info_contents');
            // foreach ($contents as $k => $v) {
            //     if (array_key_exists('_serialize_values', $v) && !empty($v['_serialize_values'])) {
            //         $this->request->data["info_contents"][$k]['content'] = serialize($v['_serialize_values']);
            //     }
            // }

            $options['callback'] = function ($id) use ($delete_ids, $tags, $page_config, $info_category_ids) {
                // コンテンツ削除
                if ($id && $delete_ids) {
                    $sub_delete_ids = [];
                    foreach ($delete_ids as $del_id) {
                        $sub_delete_ids = $this->content_delete($id, $del_id);
                        // 枠ごと削除した場合の中身のコンテンツ削除
                        if (!empty($sub_delete_ids)) {
                            foreach ($sub_delete_ids as $sub_del_id) {
                                $this->content_delete($id, $sub_del_id);
                            }
                        }
                    }
                }

                // 枠の紐付け
                $q = $this->InfoContents->find()->where(['InfoContents.info_id' => $id])->order(['position' => 'ASC']);
                if (!$q->isEmpty()) {
                    $info_contents = $q->all();
                    foreach ($info_contents as $v) {
                        if (array_key_exists((int)$v['block_type'], Info::BLOCK_TYPE_WAKU_LIST)) {
                            $section_query = $this->SectionSequences->find()->where(['SectionSequences.id' => $v['section_sequence_id']]);
                            if ($section_query->isEmpty()) {
                                continue;
                            }
                            $section_entity = $section_query->first();
                            $section_entity->info_content_id = $v['id'];
                            $this->SectionSequences->save($section_entity);
                        }
                    }
                }

                // タグ
                $tag_ids = $this->saveTags($page_config->id, $tags); // マスターの登録
                if (!empty($tag_ids)) {
                    foreach ($tag_ids as $tag_id) {
                        $info_tag = $this->InfoTags->find()->where(['InfoTags.tag_id' => $tag_id, 'InfoTags.info_id' => $id])->first();
                        if (empty($info_tag)) {
                            $info_tag = $this->InfoTags->newEntity();
                            $info_tag->info_id = $id;
                            $info_tag->tag_id = $tag_id;
                            $this->InfoTags->save($info_tag);
                        }
                    }
                }
                // タグの削除
                if (empty($tag_ids)) {
                    $this->InfoTags->deleteAll(['InfoTags.info_id' => $id]);
                } else {
                    $this->InfoTags->deleteAll(['InfoTags.info_id' => $id, 'InfoTags.tag_id not in' => $tag_ids]);
                }

                if (!empty($delete_info_cate_ids)) {
                    $delete_info_cate_ids = array_values($delete_info_cate_ids);
                    $this->InfoCategories->deleteAll(['InfoCategories.info_id' => $id, 'InfoCategories.id in' => $delete_info_cate_ids]);
                }

                // 複数カテゴリ
                if ($page_config->is_category == 'Y' && $page_config->is_category_multiple == 1) {
                    $this->InfoCategories->deleteAll(['InfoCategories.info_id' => $id, 'InfoCategories.id not in ' => $info_category_ids]);
                    if (!empty($info_category_ids)) {
                        foreach ($info_category_ids as $cat_id) {
                            $info_cate = $this->InfoCategories->find()->where(['InfoCategories.info_id' => $id, 'InfoCategories.category_id' => $cat_id])->first();
                            if (empty($info_cate)) {
                                $info_cate = $this->InfoCategories->newEntity();
                                $info_cate->info_id = $id;
                                $info_cate->category_id = $cat_id;
                                $this->InfoCategories->save($info_cate);
                            }
                        }
                    }
                }

                //topフラグがあれば別DBに保存する。
                $info = $this->Infos->find()->where(['Infos.id' => $id])->first();
                $before_top = $this->InfoTops->find()->where(['InfoTops.info_id' => $id])->first();
                $isTop = ($info->status == 'publish' && $info->is_top == 1);
                if ($isTop) {
                    if (!$before_top) {
                        $entity = $this->InfoTops->newEntity();
                        $entity->id = null;
                        $entity->page_config_id = $info->page_config_id;
                        $entity->info_id = $info->id;
                        $this->InfoTops->save($entity);
                    }
                } else {
                    if ($before_top) {
                        $this->InfoTops->delete($before_top);
                    }
                }

                // contentsのcallback
                if (!empty($page_config->after_save_callback)) {
                    $this->{$page_config->after_save_callback}($id);
                }

                // HTML更新
                // $this->_htmlUpdate($id);
            };

            // page_configs.before_save_callback
            if (!empty($page_config->before_save_callback)) {
                $this->request->data = $this->{$page_config->before_save_callback}($page_config, $this->request->getData());
            }
        } else {
            if (!$id) {
                $options['get_callback'] = function ($data) use ($query) {
                    $data['category_id'] = $query['sch_category_id'];
                    return $data;
                };
            }
            $info_category_ids = $this->getCategoryIds($id);
        }

        $this->set(compact('info_category_ids'));

        $options['append_validate'] = function ($isValid, $entity) use ($page_config) {
            // falseならエラー出力
            $isValid = true;

            //info_append_itemsのバリデチェック
            $info_append_items = $entity['info_append_items'] ?? [];
            if ($info_append_items) {
                $valid_info_append_item = $this->validInfoAppendItems($entity, $page_config);
                if (!$valid_info_append_item) {
                    $isValid = false;
                }
            }

            return $isValid;
        };
        $options['associated'] = ['InfoAppendItems', 'InfoContents'];

        $options['redirect'] = ['action' => 'index', '?' => $query];

        //カテゴリーIDがpostされていたら、それに変更する。
        $new_category_id = $this->request->getData('category_id') ?? 0;
        if ($new_category_id) {
            $new_query = $query;
            $new_query['sch_category_id'] = $new_category_id;
            $options['redirect'] = ['action' => 'index', '?' => $new_query];
        }
        $options['validate'] = $validate;


        if ($this->is_related_info) {
            $cond['Infos.relation_info_id'] = $query['relation_info_id'];
        }

        parent::_edit($id, $options);

        $entity = $this->viewVars['entity'] ?? [];

        // カテゴリリスト
        $selected_category = $entity['category_id'] ?? 0;
        $category_list = [];

        //true => 選択していたカテゴリーと同じ深さのカテゴリーを選択肢に出す。
        //false => 選択していたカテゴリーと親が同じの兄弟カテゴリーのみ、選択肢に出す。
        $select_same_depth = true;

        if ($sch_page_id && $page_config->is_category == 'Y') {
            $category_cond = ['Categories.page_config_id' => $sch_page_id];

            $category_id = $selected_category ? $selected_category : $query['sch_category_id'];
            $cond = ['Categories.id' => $category_id];

            $category = $this->Categories->find()->where($cond)->first();
            if (!($category)) {
                $category = $this->Categories->find()->where([
                    'Categories.page_config_id' => $page_config->id,
                    'Categories.status' => 'publish',
                ])->first();
            }

            //選択していた同じ親カテゴリーを持つ兄弟カテゴリーのみ、選択肢に出す。
            if ($page_config->is_category_multilevel == 1) {
                if ($select_same_depth) {
                    //選択していたカテゴリーと同じ深さのカテゴリーを選択肢に出す。
                    $category_cond['Categories.multiple_level'] = $category->multiple_level;
                } else {
                    //選択していたカテゴリーと親が同じの兄弟カテゴリーのみ、選択肢に出す。
                    $category_cond['Categories.parent_category_id'] = $category->parent_category_id;
                }
            }

            $category_list = $this->Categories->find()->where($category_cond)->order(['Categories.position' => 'ASC'])->contain(['ParentCategory'])->toArray();

            //選択肢を都道府県に上書き
            $category_names = @$category->multiple_level && $page_config->{'category_name_' . $category->multiple_level} ?? '';
            //階層分けする。
            if ($select_same_depth) {
                $category_list = Hash::combine($category_list, '{n}.id', '{n}.name', '{n}.parent_category.name');
            } else {
                $category_list = Hash::combine($category_list, '{n}.id', '{n}.name');
            }
        }
        $this->set(compact('category_list'));

        //append_itemsに新規追加し位置を変えるとentityがずれる。
        $append_list = $append_list->toArray();
        if ($entity && count($append_list) > 0 && count($entity['info_append_items'] ?? []) != count($append_list)) {
            $this->entityCombine2InfoAppend($entity, $append_list);
        }
    }

    //append_itemsの位置が変わった、追加された時用
    public function entityCombine2InfoAppend($entity, $append_list)
    {
        $info_append_items = $entity['info_append_items'];
        if ($info_append_items) {
            $info_append_items = Hash::combine($info_append_items, '{n}.append_item_id', '{n}');
        }
        $new_info_append_items = [];
        foreach ($append_list as $k => $append_item) {
            $include = $info_append_items[$append_item['id']] ?? $this->InfoAppendItems->newEntity();
            $new_info_append_items[] = $include;
        }
        $entity->info_append_items = $new_info_append_items;
        $this->set('entity', $entity);
        $this->request->data['info_append_items'] = $new_info_append_items;
    }

    public function delete($id, $type, $columns = null)
    {
        $this->checkLogin();

        if (!$this->isOwnInfoByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user/');
            return;
        }

        $options = [];

        $data = $this->Infos->find()
            ->where(['Infos.id' => $id])
            ->contain([
                'PageConfigs' => function ($q) {
                    return $q->select(['slug', 'site_config_id', 'page_title']);
                },
                'InfoAppendItems',
                'InfoContents',
                'InfoCategories'
            ])
            ->first();
        if (empty($data)) {
            $this->redirect(['action' => 'index']);
            return;
        }

        $redirect_query = ['sch_page_id' => $data->page_config_id, 'sch_category_id' => $data->category_id];
        if ($this->request->getQuery('relation_info_id')) {
            $redirect_query['relation_info_id'] = $this->request->getQuery('relation_info_id');
        }

        if ($type == 'content') {
            $options['redirect'] = ['action' => 'index', '?' => $redirect_query];
        } else {
            $options['redirect'] = ['action' => 'edit', $id, '?' => $redirect_query];
        }

        if ($type == 'content') {
            if (!empty($data->info_append_items)) {
                foreach ($data->info_append_items as $sub) {
                    $this->appendDelete($id, $sub->id);
                }
            }

            if (!empty($data->info_contents)) {
                foreach ($data->info_contents as $sub) {
                    $this->content_delete($id, $sub->id);
                }
            }

            if (!empty($data->info_categories)) {
                foreach ($data->info_categories as $sub) {
                    $this->InfoCategories->delete($sub);
                }
            }
        }
        parent::_delete($id, $type, $columns, $options);

        // $this->_htmlDelete($id, $data);
    }

    public function position($id, $pos)
    {
        $this->checkLogin();

        if (!$this->isOwnInfoByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user/');
            return;
        }

        $query = $this->_getQueryIndex();

        $options = [];

        $data = $this->Infos->find()->where(['Infos.id' => $id])->first();
        if (empty($data)) {
            $this->redirect(['action' => 'index']);
            return;
        }

        if (!$this->isCategorySort($data->page_config_id)) {
            unset($query['sch_category_id']);
        }
        $options['redirect'] = ['action' => 'index', '?' => $query];

        return parent::_position($id, $pos, $options);
    }

    public function enable($id)
    {
        $this->checkLogin();

        if (!$this->isOwnInfoByUser($id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user/');
            return;
        }

        $options = [];

        $data = $this->Infos->find()->where(['Infos.id' => $id])->contain(['PageConfigs'])->first();
        if (empty($data)) {
            $this->redirect(['action' => 'index']);
            return;
        }

        $page_config_id = $this->request->getQuery('sch_page_id');
        $category_id = $this->request->getQuery('sch_category_id');
        $pos = $this->request->getQuery('pos');
        $page = $this->request->getQuery('page');
        if (empty($pos)) {
            $pos = 0;
        }

        if ($this->isCategoryEnabled($data->page_config) && $data->category_id == 0 && $data->status == 'draft' && $data->page_config->is_category_multiple == 0) {
            $this->Flash->set('カテゴリが未設定の記事は公開できません');
            $this->redirect(['action' => 'index', '?' => ['sch_page_id' => $page_config_id, 'sch_category_id' => $category_id]]);
            return;
        }

        $new_query = ['sch_page_id' => $page_config_id, 'sch_category_id' => $category_id, 'pos' => $pos, 'page' => $page];

        $relation_info_id = $this->request->getQuery('relation_info_id');
        if ($relation_info_id) {
            $new_query['relation_info_id'] = $relation_info_id;
        }

        $options['redirect'] = ['action' => 'index', '?' => $new_query];

        parent::_enable($id, $options);

        if (!empty($data->page_config->after_enable_callback)) {
            $this->{$data->page_config->after_enable_callback}($id);
        }
    }

    public function setList()
    {
        $list = array();

        // ブロック
        $_block_type_list = Info::getBlockTypeList();
        $block_type_list = [];
        if (empty($this->GQuery['sch_page_id'])) {
            $block_type_list = $_block_type_list;
        } else {
            foreach ($_block_type_list as $no => $name) {
                if ($this->PageConfigItems->enabled($this->GQuery['sch_page_id'], PageConfigItem::TYPE_BLOCK, Info::$block_number2key_list[$no])) {
                    $block_type_list[$no] = $name;
                }
            }
        }
        $list['block_type_list'] = $this->array_asso_chunk($block_type_list, 4);

        // 枠ブロック
        $_block_type_waku_list = Info::getBlockTypeList('waku');
        $block_type_waku_list = [];
        if (empty($this->GQuery['sch_page_id'])) {
            $block_type_waku_list = $_block_type_waku_list;
        } else {
            foreach ($_block_type_waku_list as $no => $name) {
                if ($this->PageConfigItems->enabled($this->GQuery['sch_page_id'], PageConfigItem::TYPE_SECTION, Info::$block_number2key_list[$no])) {
                    $block_type_waku_list[$no] = $name;
                }
            }
        }
        $list['block_type_waku_list'] = $block_type_waku_list;
        $list['font_list'] = Info::$font_list;

        $current_site_id = $this->Session->read('current_site_id');
        $list['page_config_list'] = $this->PageConfigs->find('list', ['keyField' => 'id', 'valueField' => 'page_title'])->where(['PageConfigs.site_config_id' => $current_site_id])->toArray();

        $list['out_waku_list'] = Info::$out_waku_list;
        $list['line_style_list'] = Info::$line_style_list;
        $list['line_color_list'] = Info::$line_color_list;
        $list['line_width_list'] = Info::$line_width_list;
        $list['waku_style_list'] = Info::$waku_style_list;
        $list['waku_color_list'] = Info::$waku_color_list;
        $list['waku_bgcolor_list'] = Info::$waku_bgcolor_list;
        $list['button_color_list'] = Info::$button_color_list;
        $list['content_liststyle_list'] = Info::$content_liststyle_list;
        $list['link_target_list'] = Info::$link_target_list;

        $list['placeholder_list'] = AppendItem::$placeholder_list;
        $list['notes_list'] = AppendItem::$notes_list;

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $PageConfig = new PageConfig;
        $this->set('PageConfig', $PageConfig);

        $this->list = $list;
        return $list;
    }

    public function addRow()
    {
        $this->viewBuilder()->setLayout('plain');

        $this->setList();

        $rownum = $this->request->getData('rownum');
        $data['block_type'] = $this->request->getData('block_type');

        $entity = $this->InfoContents->newEntity($data);
        $entity->id = null;
        $entity->position = 0;
        $entity->block_type = $data['block_type'];
        $entity->section_sequence_id = 0;
        $entity->option_value = '';
        $entity->option_value2 = '';
        $entity->option_value3 = '';
        $entity->image_pos = '';
        $entity->title = '';

        if ($this->request->getData('section_no')) {
            $entity->section_sequence_id = $this->request->getData('section_no');
        }

        if (array_key_exists((int)$data['block_type'], Info::BLOCK_TYPE_WAKU_LIST)) {
            $entity->section_sequence_id = $this->SectionSequences->createNumber();
            if (array_key_exists($data['block_type'], Info::$option_default_values)) {
                $entity->option_value = Info::$option_default_values[$data['block_type']];
            }
        }
        if ($data['block_type'] == Info::BLOCK_TYPE_SECTION_WITH_IMAGE) {
            $entity->image_pos = 'left';
        }

        $datas = $entity->toArray();

        $this->set(compact('rownum', 'datas'));
    }

    public function addTag()
    {
        $this->viewBuilder()->setLayout('plain');

        $num = $this->request->getData('num');
        $tag = $this->request->getData('tag');
        $tag = strip_tags(trim($tag));

        // $entity = $this->Tags->find()
        //                      ->where(['Tags.tag' => $tag])
        //                      ->first();

        $this->set(compact('tag', 'num'));
    }

    private function content_delete($id, $del_id)
    {
        $q = $this->InfoContents->find()->where(['InfoContents.id' => $del_id, 'InfoContents.info_id' => $id]);
        $e = $q->first();

        $sub_delete_ids = [];

        if (array_key_exists((int)$e->block_type, Info::BLOCK_TYPE_WAKU_LIST) && $e->section_sequence_id > 0) {
            $sub_delete_ids = $this->InfoContents->find()
                ->where(
                    [
                        'InfoContents.section_sequence_id' => $e->section_sequence_id,
                        'InfoContents.id !=' => $del_id,
                        'InfoContents.info_id' => $id
                    ]
                )
                ->extract('id');
        }

        $image_index = array_keys($this->InfoContents->attaches['images']);
        $file_index = array_keys($this->InfoContents->attaches['files']);

        foreach ($image_index as $idx) {
            foreach ($e->attaches[$idx] as $_) {
                $_file = WWW_ROOT . $_;
                if (is_file($_file)) {
                    @unlink($_file);
                }
            }
        }

        foreach ($file_index as $idx) {
            $_file = WWW_ROOT . $e->attaches[$idx][0];
            if (is_file($_file)) {
                @unlink($_file);
            }
        }
        $this->InfoContents->delete($e);

        return $sub_delete_ids;
    }

    public function htmlUpdateAll($page_config_id, $category_id = 0)
    {
    }

    public function _htmlDelete($info_id, $entity)
    {
    }

    public function _htmlUpdate($info_id)
    {
    }

    public function createDetailJson($info_id, $is_create = true)
    {
        return [];
    }

    private function setContents($content, $parentBlockType = 0)
    {
        $data = [];

        switch ($content['block_type']) {
            case Info::BLOCK_TYPE_TITLE_H2: // タイトル
            case Info::BLOCK_TYPE_TITLE: // タイトル
            case Info::BLOCK_TYPE_TITLE_H4: // タイトル
                $data['title'] = $content['title'];
                $data['font_name'] = $content['option_value'];
                break;

            case Info::BLOCK_TYPE_TITLE_H5: // タイトル
                $data['title'] = $content['title'];
                $data['font_name'] = $content['option_value'];
                break;

            case Info::BLOCK_TYPE_CONTENT: // 本文
                $data['content'] = $content['content'];
                $data['font_name'] = $content['option_value'];
                $data['list_style'] = $content['option_value2'];
                break;

            case Info::BLOCK_TYPE_WYSIWYG_OLD: // 本文(OLD)
                $data['content'] = $content['content'];
                $daa['font_name'] = $content['option_value'];
                $data['list_style'] = $content['option_value2'];
                break;

            case Info::BLOCK_TYPE_IMAGE: // 画像
                $data['content'] = Hash::get($content, 'attaches.image.0');
                $data['link'] = $content['content'];
                $data['target'] = $content['option_value'];
                break;

            case Info::BLOCK_TYPE_FILE: // ファイル
                $data['src'] = '';
                $data['file_name'] = '';
                $data['file_size'] = 0;
                if (Hash::get($content, 'attaches.file.src')) {
                    $data['src'] = '/contents' . Hash::get($content, 'attaches.file.download') . 'file.' . Hash::get($content, 'file_extension');
                    $data['file_name'] = (Hash::get($content, 'file_name') ?: '添付ファイル') . '.' . $content['file_extension'];
                    $data['file_size'] = $this->byte_format($content['file_size']);
                } else {
                    return false;
                }
                break;

            case Info::BLOCK_TYPE_RELATION: // 関連記事
                $data['title'] = nl2br($content['content']);
                $data['text'] = nl2br($content['option_value2']);
                $data['image'] = Hash::get($content, 'attaches.image.0');
                // $data['content'] = $content['content'];
                $data['link'] = $content['option_value'];
                break;

            case Info::BLOCK_TYPE_BUTTON: // リンクボタン
            case Info::BLOCK_TYPE_BUTTON2: // リンクボタン
                $data['name'] = $content['title'];
                $data['link'] = $content['content'];
                $data['button_color'] = $content['option_value'];
                $data['target'] = $content['option_value2'];
                break;

            case Info::BLOCK_TYPE_LINE: // 区切り線
                $data['line_style'] = $content['option_value'];
                $data['line_color'] = $content['option_value2'];
                $data['line_width'] = $content['option_value3'];
                break;

            case Info::BLOCK_TYPE_SECTION: // 枠
                $data['b_style'] = $content['option_value'];
                if ($data['b_style'] == 'waku_style_6') {
                    $data['bg_color'] = $content['option_value2'];
                } else {
                    $data['b_color'] = $content['option_value2'];
                }
                $data['b_width'] = $content['option_value3'];

                // no break
            case Info::BLOCK_TYPE_SECTION_RELATION: // 関連記事枠
                $data['sub_contents'] = [];
                break;

            case Info::BLOCK_TYPE_SECTION_FILE: // ファイル枠
                $data['title'] = strip_tags($content['title']);
                $data['sub_contents'] = [];
                break;

            case Info::BLOCK_TYPE_SECTION_WITH_IMAGE: // 画像回り込み用　枠
                $data['image'] = Hash::get($content, 'attaches.image.0');
                $data['image_pos'] = $content['image_pos'];
                $data['image_link'] = $content['option_value3'];
                $data['title'] = $content['title'];
                $data['content'] = $content['content'];
                $data['font_name'] = $content['option_value'];
                $data['list_style'] = $content['option_value2'];

                break;

            default:
                // code...
                break;
        }

        return $data;
    }

    private function byte_format($size)
    {
        $b = 1024;    // バイト
        $mb = pow($b, 2);   // メガバイト
        $gb = pow($b, 3);   // ギガバイト

        switch (true) {
            case $size >= $gb:
                $target = $gb;
                $unit = 'GB';
                break;
            case $size >= $mb:
                $target = $mb;
                $unit = 'MB';
                break;
            default:
                $target = $b;
                $unit = 'KB';
                break;
        }

        $new_size = round($size / $target, 2);
        $file_size = number_format($new_size, 2, '.', ',') . $unit;

        return $file_size;
    }

    public function toHierarchization($id, $entity, $options = [])
    {
        // 枠ブロックとして認識させる番号を指定
        $options['section_block_ids'] = array_keys(Info::BLOCK_TYPE_WAKU_LIST);
        return parent::toHierarchization($id, $entity, $options);
    }

    private function saveTags($page_config_id, $tags)
    {
        $ids = [];
        if (!empty($tags)) {
            foreach ($tags as $t) {
                $tag = strip_tags(trim($t['tag']));
                $entity = $this->Tags->find()->where(['Tags.tag' => $tag, 'Tags.page_config_id' => $page_config_id])->first();
                if (empty($entity)) {
                    $entity = $this->Tags->newEntity();
                    $entity->tag = $tag;
                    $entity->status = 'publish';
                    $entity->page_config_id = $page_config_id;

                    $this->Tags->save($entity);
                }
                $ids[] = $entity->id;
            }
        }
        return $ids;
    }

    public function popTaglist()
    {
        $this->viewBuilder()->setLayout('pop');

        $page_config_id = $this->request->getQuery('page_config_id');

        $cond = [
            'Tags.page_config_id' => $page_config_id
        ];

        $query = $this->Tags->find();
        $sql = $query->select(['id', 'tag', 'cnt' => $query->func()->count('InfoTags.id')])
            ->where($cond)
            ->leftJoinWith('InfoTags')
            ->group('Tags.id')
            // ->enableAutoFields(true)
            ->order(['cnt' => 'DESC']);

        $this->modelName = 'Tags';
        $this->_lists($cond, [
            'limit' => 10,
            'order' => ['Tags.position' => 'ASC'],
            'sql_query' => $sql
        ]);
    }

    public function distAttachmentCopy($id)
    {
        if ($this->Infos->getTable() !== 'infos') {
            return;
        }
        if ($this->InfoContents->getTable() !== 'info_contents') {
            return;
        }

        $_data = $this->Infos->find()->where(['Infos.id' => $id])->contain(['InfoContents'])->first();

        if (!$id || empty($_data)) {
            return;
        }

        $this->Infos->copyPreviewAttachement($_data->id, 'PreviewInfos');

        foreach ($_data->info_contents as $content) {
            $this->InfoContents->copyPreviewAttachement($content->id, 'PreviewInfoContents');
        }

        return;
    }

    public function deletePreviewSource($page_id)
    {
        $now = new \DateTime();

        if ($this->Infos->getTable() !== 'preview_infos') {
            return;
        }
        if ($this->InfoContents->getTable() !== 'preview_info_contents') {
            return;
        }

        $previews = $this->Infos->find()->where(['Infos.created <' => $now->format('Y-m-d 00:00:00'), 'Infos.page_config_id' => $page_id])->contain(['InfoContents'])->all();

        foreach ($previews as $prev) {
            if (!empty($prev->info_contents)) {
                foreach ($prev->info_contents as $content) {
                    $this->modelName = 'InfoContents';
                    // $this->_delete($content->id, 'content', null, ['redirect' => false]);
                    $this->InfoContents->delete($content);
                }
            }
            $this->modelName = 'Infos';
            // $this->_delete($prev->id, 'content', null, ['redirect' => false]);
            $this->Infos->delete($prev);
        }
    }

    private function deletePreviewAttachment()
    {
        $this->_deletePreviewImage();
        $this->_deletePreviewFile();
    }

    /**
     * プレビュー用の画像削除
     * @return [type] [description]
     */
    private function _deletePreviewImage()
    {
        $limit_dt = new \DatetIme('-24 hour');

        // PreviewInfos
        $image_dir = UPLOAD_DIR . 'PreviewInfos' . DS . 'images/*';

        $file_list = glob($image_dir, GLOB_BRACE);
        if (!empty($file_list)) {
            foreach ($file_list as $file) {
                if (is_file($file)) {
                    $unixdate = filemtime($file);
                    $filedate = date('YmdHis', $unixdate);

                    if ($filedate < $limit_dt->format('YmdHis')) {
                        @unlink($file);
                    }
                }
            }
        }

        // PreviewInfoContents
        $image_dir = UPLOAD_DIR . 'PreviewInfoContents' . DS . 'images/*';

        $file_list = glob($image_dir, GLOB_BRACE);
        if (!empty($file_list)) {
            foreach ($file_list as $file) {
                if (is_file($file)) {
                    $unixdate = filemtime($file);
                    $filedate = date('YmdHis', $unixdate);
                    if ($filedate < $limit_dt->format('YmdHis')) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    /**
     * プレビュー用のファイルを削除
     * @return [type] [description]
     */
    private function _deletePreviewFile()
    {
        $limit_dt = new \DatetIme('-24 hour');

        // PreviewInfos
        $file_dir = UPLOAD_DIR . 'PreviewInfos' . DS . 'files/*';

        $file_list = glob($file_dir, GLOB_BRACE);
        if (!empty($file_list)) {
            foreach ($file_list as $file) {
                if (is_file($file)) {
                    $unixdate = filemtime($file);
                    $filedate = date('YmdHis', $unixdate);

                    if ($filedate < $limit_dt->format('YmdHis')) {
                        @unlink($file);
                    }
                }
            }
        }

        // PreviewInfoContents
        $file_dir = UPLOAD_DIR . 'PreviewInfoContents' . DS . 'files/*';

        $file_list = glob($file_dir, GLOB_BRACE);
        if (!empty($file_list)) {
            foreach ($file_list as $file) {
                if (is_file($file)) {
                    $unixdate = filemtime($file);
                    $filedate = date('YmdHis', $unixdate);
                    if ($filedate < $limit_dt->format('YmdHis')) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    protected function getAppendList($config_id = 0, $list_bool = false)
    {
        $list = [];

        if (empty($config_id)) {
            return $list;
        }

        if ($list_bool) {
            $append_datas = $this->MstLists->find('list', [
                'keyField' => 'ltrl_val',
                'valueField' => 'ltrl_nm'
            ])
                ->order(['MstLists.position' => 'ASC'])
                ->toArray();
        } else {
            $append_datas = $this->MstLists->find()
                ->order(['MstLists.position' => 'ASC'])
                ->toArray();
        }

        if (empty($append_datas)) {
            return $list;
        }

        if ($list_bool) {
            return $append_datas;
        }

        foreach ($append_datas as $n => $_) {
            $list[$_['use_target_id']][$_['ltrl_val']] = $_['ltrl_nm'];
        }

        return $list;
    }

    /**
     * Undocumented function
     *
     * @param [type] $data formの元データ
     * @param [type] $page_config
     * @return bool
     */
    protected function validInfoAppendItems($data, $page_config)
    {
        // falseならエラー出力
        $valid = true;

        //
        $all_append_items = $this->AppendItems->find()->toArray();
        $all_append_items_list = Hash::combine($all_append_items, '{n}.id', '{n}'); //
        $append_for_additional_list = Hash::combine($all_append_items, '{n}.id', '{n}.slug'); // 追加バリデーション用id-slugリスト

        //empty || 型チェック || slugバリデーション
        foreach ($data['info_append_items'] as $n => $info_append_item) {
            //AppendItemの取得 ないのはおかしい
            $append_item_id = $info_append_item['append_item_id'] ?? 0;
            $append_item = $all_append_items_list[$append_item_id] ?? [];
            if (!$append_item) {
                $valid = false;
                continue;
            }

            // empty & 型チェック
            $is_require = (bool) ($append_item->is_required ?? 0);
            $is_valid = $this->validWithType($data, $info_append_item, $append_item, [], $page_config->slug, $is_require);
            if (!$is_valid) {
                $valid = false;
                continue;
            }

            // 追加項目のスラッグごとに個別のバリデーション確認
            $is_valid = $this->additionalValidate($data, $info_append_item, $append_for_additional_list, $page_config->slug);
            if (!$is_valid) {
                $valid = false;
                continue;
            }
        }

        return $valid;
    }

    //append_itemのvalue_typeごとに、デフォルトのバリデを設定する。
    protected function validWithType($entity, $data, $append_item, $list, $slug, $is_require = true)
    {
        //falseならエラー出力
        $valid = true;

        //append_itemのvalue_type
        $value_type = $append_item['value_type'] ?? '';

        //targetとなるvalue_〇〇を入れる。 必須項目かつ空白ならエラーにする。
        $target = '';
        $target2 = null; //null以外が入ってたら空白確認する。

        // 数字型
        if ($value_type == AppendItem::TYPE_NUMBER) {
            $target = $data['value_int'] ?? 0;

            //数値以外の場合は0になってるため動作しない
            // if (!is_int($target)) {
            //     $valid = false;
            // }
        }
        // テキスト型
        if ($value_type == AppendItem::TYPE_TEXT) {
            $target = $data['value_text'] ?? '';
        }
        // テキストエリア型
        if ($value_type == AppendItem::TYPE_TEXTAREA) {
            $target = $data['value_textarea'] ?? '';
        }
        // 日付型
        if ($value_type == AppendItem::TYPE_DATE) {
            $target = $data['value_date'] ?? '';
        }
        // list
        if ($value_type == AppendItem::TYPE_LIST) {
            $target = $data['value_int'] ?? '';
        }
        // checkbox
        if ($value_type == AppendItem::TYPE_CHECK) {
            $target = $data['value_text'] ?? '';
        }
        // radio
        if ($value_type == AppendItem::TYPE_RADIO) {
            $target = $data['value_decimal'] ?? '';
        }
        // decimal
        if ($value_type == AppendItem::TYPE_DECIMAL) {
            $target = $data['value_decimal'] ?? '';
        }
        // file
        if ($value_type == AppendItem::TYPE_FILE) {
            $target = $data['_file']['size'] ?? '';
            $target2 = $data['file_size'] ?? '';
        }
        // 画像
        if ($value_type == AppendItem::TYPE_IMAGE) {
            $target = $data['_image']['tmp_name'] ?? '';
            $target2 = $data['_old_image'] ?? '';
        }
        // リンク
        if ($value_type == AppendItem::TYPE_LINK) {
            $target = $data['value_text'] ?? '';
        }

        if ($value_type == AppendItem::TYPE_INFO_LINK) {
            $target = $data['value_decimal'] ?? '';
            if ($target == 2) {
                $target2 = $data['value_text'] ?? '';
                $target = $target2 ? $target : null;
            } elseif ($target == 3) {
                $target2 = $data['value_text2'] ?? '';
                $target = $target2 ? $target : null;
            }
        }

        //emptyならエラー
        if ($is_require) {
            if (!is_null($target2)) {
                if (!$target && !$target2) {
                    $valid = false;
                }
            } else {
                if (!$target) {
                    $valid = false;
                }
            }
        }

        // もしリンクタイプがappend項目と違えば、空でもtrueを返す
        // if(in_array($slug,['news', 'information'])){
        //     $key_file = 1;
        //     $key_link = 2;

        //     if($append_item['slug'] == 'link'){
        //         foreach($entity['info_append_items'] as $i_app_item){
        //             if(in_array($i_app_item['append_item_id'],$list['target_type'])){
        //                 if(intval($i_app_item['value_decimal']) == $key_file){
        //                     $valid = true;
        //                 }
        //             }
        //         }
        //     }
        //     if($append_item['slug'] == 'file'){
        //         foreach($entity['info_append_items'] as $i_app_item){
        //             if(in_array($i_app_item['append_item_id'],$list['target_type'])){
        //                 if(intval($i_app_item['value_decimal']) == $key_link){
        //                     $valid = true;
        //                 }
        //             }
        //         }
        //     }
        // }

        // エラーメッセージセット
        if (!$valid) {
            if (in_array($value_type, [AppendItem::TYPE_TEXTAREA, AppendItem::TYPE_TEXT, AppendItem::TYPE_LINK, AppendItem::TYPE_INFO_LINK])) {
                $entity->setErrors([
                    "{$slug}.{$append_item['slug']}" => [
                        'notempty' => '入力してください'
                    ]
                ]);
            }

            if (in_array($value_type, [AppendItem::TYPE_CHECK, AppendItem::TYPE_RADIO, AppendItem::TYPE_LIST, AppendItem::TYPE_IMAGE, AppendItem::TYPE_FILE])) {
                $entity->setErrors([
                    "{$slug}.{$append_item['slug']}" => [
                        'notempty' => '選択してください'
                    ]
                ]);
            }
        }

        return $valid;
    }

    /**
     * emptyチェック以外のバリデーション(append項目)
     * @param [type] $entity formの元データ
     * @param [type] $data 評価中のinfo_append_itemデータ
     * @param [type] $list append_itemsのid-slugリスト
     * @param [type] $slug page_config->slug
     * @return bool
     */
    protected function additionalValidate($entity, $data, $list, $page_slug)
    {
        $error = false;

        $append_slug = $list[$data['append_item_id']];
        // dd([$entity, $data, $list, $slug]);

        // if ($page_slug == 'glossaries') {
        //     if ($append_slug == 'kana') {
        //         if (!empty($data['value_text'])) {
        //             if (!$this->InfoAppendItems->checkKana($data['value_text'])) {
        //                 $error = [
        //                     "{$page_slug}.{$append_slug}" => [
        //                         'checkurl' => '全角カタカナで入力してください'
        //                     ]
        //                 ];
        //             }
        //         }
        //     }
        // }

        //校舎
        // if ($page_slug == 'schools') {
        //     //アカウントIDのバリデーション
        //     if ($append_slug == 'account_id') {
        //         $value = $data['value_text'];
        //         //英数字のバリデーション
        //         if (!preg_match('/^[a-zA-Z0-9-_]+$/u', $value)) {
        //             $error = [
        //                 "{$page_slug}.{$append_slug}" => [
        //                     'checkIdA' => '半角英数字で入力してください'
        //                 ]
        //             ];
        //         }

        //         $cond = [
        //             'InfoAppendItems.id !=' => ($data['id'] ?? 0),
        //             'InfoAppendItems.append_item_id' => $data['append_item_id'],
        //             'InfoAppendItems.value_text' => $value,
        //         ];
        //         $issset_same_id = $this->InfoAppendItems->find()->where($cond)->first();
        //         if ($issset_same_id) {
        //             $error = [
        //                 "{$page_slug}.{$append_slug}" => [
        //                     'checkId' => '同じIDが既に登録されています'
        //                 ]
        //             ];
        //         }
        //     }

        //     //アカウントPASS
        //     if ($append_slug == 'account_password') {
        //         $value = $data['value_text'];
        //         //英数字のバリデーション
        //         if (!$this->InfoAppendItems->checkAlphabetNum($value)) {
        //             $error = [
        //                 "{$page_slug}.{$append_slug}" => [
        //                     'checkIdA' => '半角英数字で入力してください'
        //                 ]
        //             ];
        //         }
        //     }
        // }

        // if($append_slug == 'ticket_link'){
        //     if(!empty($data['value_text'])){
        //         if(!$this->InfoAppendItems->checkUrl($data['value_text'])){
        //             $valid = false;
        //             $entity->setErrors([
        //                 "{$slug}.{$append_slug}" => [
        //                     'checkurl' => '正しいURLを入力してください']]);
        //         }
        //     }
        // }

        // if($append_slug == 'news_link'){
        //     if(!empty($data['value_text'])){
        //         if(!$this->InfoAppendItems->checkUrl($data['value_text'])){
        //             $valid = false;
        //             $entity->setErrors([
        //                 "{$slug}.{$append_slug}" => [
        //                     'checkurl' => '正しいURLを入力してください']]);
        //         }
        //     }
        // }

        // if($append_slug == 'special_link'){
        //     if(!empty($data['value_text'])){
        //         if(!$this->InfoAppendItems->checkUrl($data['value_text'])){
        //             $valid = false;
        //             $entity->setErrors([
        //                 "{$slug}.{$append_slug}" => [
        //                     'checkurl' => '正しいURLを入力してください']]);
        //         }
        //     }
        // }

        $valid = true;
        if ($error) {
            $valid = false;
            $entity->setErrors($error);
        }

        return $valid;
    }

    public function appendDelete($info_id, $id, $type = 'content', $columns = null)
    {
        $this->checkLogin();
        if (!$this->isOwnInfoByUser($info_id)) {
            $this->Flash->set('不正なアクセスです');
            $this->redirect('/user/');
            return;
        }

        $q = $this->InfoAppendItems->find()->where(['InfoAppendItems.id' => $id, 'InfoAppendItems.info_id' => $info_id]);
        $e = $q->first();

        if ($type == 'content') {
            $image_index = array_keys($this->InfoAppendItems->attaches['images']);
            $file_index = array_keys($this->InfoAppendItems->attaches['files']);

            foreach ($image_index as $idx) {
                if (!empty($e[$idx])) {
                    foreach ($e->attaches[$idx] as $_) {
                        $_file = WWW_ROOT . $_;
                        if (is_file($_file)) {
                            @unlink($_file);
                        }
                    }
                }
            }
            foreach ($file_index as $idx) {
                if (!empty($e[$idx])) {
                    $_file = WWW_ROOT . $e->attaches[$idx][0];
                    if (is_file($_file)) {
                        @unlink($_file);
                    }
                }
            }

            return $this->InfoAppendItems->delete($e);
        }

        $query = $this->_getQuery();

        $options = [];
        if ($type == 'image') {
            $options['redirect'] = ['action' => 'edit', $info_id, '?' => $query];
        }

        $this->modelName = 'InfoAppendItems';
        $this->set('ModelName', $this->modelName);
        parent::_delete($id, $type, $columns, $options);
    }

    private function getCategoryIds($id = 0)
    {
        $list = [];
        $i_cates = $this->InfoCategories->find()
            ->where(['info_id' => $id])
            ->extract('category_id');
        $list = [];
        if ($i_cates->count()) {
            $list = $i_cates->toArray();
        }
        return array_values($list);
    }

    public function addVideoInfo($page_config, $data) {
        $video_id = $data['info_append_items'][0]['value_text'];

        // 正規表現
        preg_match('/.*watch\?v=(.+?)[&]?$/', $video_id, $check_id);

        // idが入力されていない場合
        if (empty($video_id)) {
            return $data;
        }

        // id or URL->idを取得
        if (11 >= strlen($video_id) && strlen($video_id) <= 12){
            $video_id = $video_id;
        } elseif ($check_id) {
            $video_id = explode('&',$check_id[1])[0];
        } else {
            return $data;
        }



        $append = $this->getYouTubeVideoInfo($video_id);

        // YouTube動画が取得出来なかった場合
        if (is_null($append)) {
            return $data;
        }

        $data['title'] = $append['title'];
        $image = file_get_contents($append['thumbnail']);

        $temp = tmpfile();

        fwrite($temp, $image);
        fseek($temp, 0);

        $meta_data = stream_get_meta_data($temp);
        $exif_data = @exif_read_data($meta_data['uri']);

        $tmp = [
            'tmp_name' => $meta_data['uri'],
            'error' => 0,
            'name' => $exif_data['FileName'].'.jpg',
            'type' => $exif_data['MimeType'],
            'size' => $exif_data['FileSize'],
        ];

        $key = "image";

        $token = ($data['attaches_token'] ?? '') ? $data['attaches_token'] : $this->getToken();//sessionと紐付ける。
        $data['attaches_token'] = $token;
        
        $saved_attache_data = $this->Infos->sessionsave_attache_files($token, $key . '_new', $tmp);
        if ($saved_attache_data) {
            $data['_saved_' . $key] = '/view_attaches/' . $token . '/' . $key . '_new';
        }
        if (isset($data[$key])) {
            $data['_' . $key] = $tmp;
            unset($data[$key]);
        } else {
            if (isset($data['_' . $key])) {
                unset($data['_' . $key]);
            }
        }

        return $data;
    }

    private function getYouTubeVideoInfo($id) {
        $url = 'https://www.googleapis.com/youtube/v3/videos?';
        $url = $url . 'part=snippet';
        $url = $url . '&id=' . $id;
        $url = $url . '&key=AIzaSyDd0fG7SzVsA7feAwR4JJ10S7tB7Qgg29Y';

        $result = $this->postFromHTTP($url, '', 'get');
        $result = json_decode($result);

        // 取得出来なかった場合
        if (!property_exists($result, 'items')){
            return null;
        } 
        if (empty($result->items)){
            return null;
        } 
        
        $info = $result->items[0];

        $title = $info->snippet->title;
        $thumbnail = $info->snippet->thumbnails->high->url;

        return [
            'title' => $title,
            'thumbnail' => $thumbnail
        ];
    }

    public function postFromHTTP($url, $data, $method='post') {

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic '.base64_encode('100:100')));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if($method=='post'){
            curl_setopt($ch, CURLOPT_POST, true);
        }else{
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}
