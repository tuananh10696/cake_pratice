<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use App\Model\Entity\Info;
use App\Utils\CustomUtility;
use Cake\Routing\Router;
use Cake\Auth\DefaultPasswordHasher;
use \SplFileObject;
use Cake\Utility\Hash;
use Cake\ORM\Query;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $Session;
    public $error_messages;
    public $helpers = [
       'Paginator' => ['templates' => 'paginator-templates']
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        if (!isset($_SESSION)) {
            session_start();
        }
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        // $this->loadComponent('Paginator');
        $this->loadComponent('Csrf');
        $this->loadComponent('Cms');
        $this->setHeadTitle();

        $this->Session = $this->request->getSession();

        $this->viewBuilder()->setLayout(false);

        $this->set('now', $this->getNow(''));
        $this->set('now_jp', $this->getNow('jp'));
        $this->set('user', $this->getSessionUser());

        $url = $_SERVER['REQUEST_URI'];
        $this->set('reset_url', parse_url($url, PHP_URL_PATH));
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }


    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        // Note: These defaults are just to get started quickly with development
        // and should not be used in production. You should instead set "_serialize"
        // in each action as required.
        if (
            !array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->getType(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }

        $this->set('error_messages', $this->error_messages);
    }

    public function beforeFilter(Event $event)
    {
        if ($this->request->getParam('prefix') === 'admin') {
            // $this->viewBuilder()->theme('Admin');
            $this->viewBuilder()->setLayout('admin');
        } else {
            //Theme 設定
            $this->viewBuilder()->setLayout('simple');
            // $this->theme = 'Pc';

            // 準備
            $this->_prepare();
        }
    }

    public function hash($str)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($str);
    }

    protected function _setView($lists)
    {
        $this->set(array_keys($lists), $lists);
    }

    private function _prepare()
    { }

    /*
    AppModelとも共通の関数　CustomUtilityに保管
    */
    public function getNow($format = 'Y-m-d H:i')
    {
        return CustomUtility::getNow($format);
    }

    public function getDateJP()
    {
        return CustomUtility::getDateJP();
    }

    public function getIp()
    {
        return CustomUtility::getIp();
    }

    public function getReferrer()
    {
        return CustomUtility::getReferrer();
    }

    //USER
    public function getSessionUser($key = '')
    {
        return CustomUtility::getSessionUser($key);
    }

    public function getSessionUserID()
    {
        return CustomUtility::getSessionUserID();
    }

    public function isLogin()
    {
        return CustomUtility::isLogin();
    }

    public function checkUserLogin()
    {
        if (!$this->isLogin()) {
            exit;
        }
    }

    //ADMIN
    public function getSessionAdminID()
    {
        return CustomUtility::getSessionAdminID();
    }

    public function isAdmin()
    {
        return CustomUtility::isAdmin();
    }

    public function nextDay($date)
    {
        return CustomUtility::nextDay($date);
    }

    public function getDate($date = '', $format = '')
    {
        return CustomUtility::getDate($date, $format);
    }

    //ひらがなから、あかさたなを算出する
    public function getHiraganaAKSTN($hira)
    {
        return CustomUtility::getHiraganaAKSTN($hira);
    }

    //公開側と管理側でリダイレクト先変える
    public function checkLogin()
    {
        if (!$this->isLogin()) {
            exit;
            //$this->redirectWithException('/admin/login/');
        }
    }

    public function checkAdmin()
    {
        if (!$this->isAdmin()) {
            exit;
            //$this->redirectWithException('/admin/login/');
        }
    }

    //AND検索対応
    public function getConditionANDkeyword($model, $sch_keyword, $needle)
    {
        $sch_keywords = $this->multi_explode(array(' ', '　'), $sch_keyword);
        $cond = [];
        foreach ($needle as $key) {
            $command = $model . '.' . $key . ' LIKE';
            $keycond = [];
            foreach ($sch_keywords as $keyword) {
                $keycond[] = [$command => '%' . $keyword . '%'];
            }
            if ($keycond) {
                $cond[] = ['AND' => $keycond];
            }
        }
        return ['OR' => $cond];
    }

    //複数黙るのAND検索
    public function getConditionANDkeyword_multiple($needs, $sch_keyword)
    {
        $sch_keywords = $this->multi_explode(array(' ', '　'), $sch_keyword);

        $cond = [];
        foreach ($sch_keywords as $keyword) {
            $ors = [];
            foreach ($needs as $model => $needle) {
                foreach ($needle as $key) {
                    $command = $model . '.' . $key . ' LIKE';
                    $ors[$command] = '%' . $keyword . '%';
                }
            }

            $cond[] = ['OR' => $ors];
        }
        return $cond;
    }

    public function multi_explode($word_array, $str)
    {
        $array = array($str);
        foreach ($word_array as $value1) {
            $return = array();
            foreach ($array as $key => $value2) {
                $return = array_merge($return, explode($value1, $value2));
            }
            $array = $return;
        }
        return $array;
    }

    /**
     * ハイアラーキゼーションと読む！（階層化という意味だ！）
     * １次元のentityデータを階層化した状態の構造にする
     */
    public function toHierarchization($id, $entity, $options = [])
    {
        // $options = array_merge([
        //     'section_block_ids' => [10]
        // ], $options);
        $data = $this->request->getData();
        $content_count = 0;
        $contents = [
            'contents' => []
        ];

        $contents_table = $this->{$this->modelName}->useHierarchization['contents_table'];
        $contents_id_name = $this->{$this->modelName}->useHierarchization['contents_id_name'];

        $sequence_table = $this->{$this->modelName}->useHierarchization['sequence_table'];
        $sequence_id_name = $this->{$this->modelName}->useHierarchization['sequence_id_name'];
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

    /**
     * 正常時のレスポンス
     */
    protected function rest_success($datas)
    {
        $data = array(
            'result' => array('code' => 0),
            'data' => $datas
        );

        $this->set(compact('data'));
        $this->set('_serialize', 'data');
    }

    /**
     * エラーレスポンス
     */
    protected function rest_error($code = '', $message = '')
    {
        $http_status = 200;

        $state_list = array(
            '200' => 'empty',
            '400' => 'Bad Request', // タイプミス等、リクエストにエラーがあります。
            '401' => 'Unauthorixed', // 認証に失敗しました。（パスワードを適当に入れてみた時などに発生）
            // '402' => '', // 使ってない
            '403' => 'Forbidden', // あなたにはアクセス権がありません。
            '404' => 'Not Found', // 該当アドレスのページはありません、またはそのサーバーが落ちている。
            '500' => 'Internal Server Error', // CGIスクリプトなどでエラーが出た。
            '501' => 'Not Implemented', // リクエストを実行するための必要な機能をサポートしていない。
            '509' => 'Other', // オリジナルコード　例外処理
        );

        $code2messages = array(
            '1000' => 'パラメーターエラー',
            '1001' => 'パラメーターエラー',
            '1002' => 'パラメーターエラー',
            '2000' => '取得データがありませんでした',
            '2001' => '取得データがありませんでした',
            '9000' => '認証に失敗しました',
            '9001' => '',
        );

        if (!array_key_exists($http_status, $state_list)) {
            $http_status = '509';
        }

        if ($message == '') {
            if (array_key_exists($code, $code2messages)) {
                $message = $code2messages[$code];
            } elseif (array_key_exists($http_status, $state_list)) {
                $message = $state_list[$http_status];
            }
        }
        if ($code == '') {
            $code = $http_status;
        }
        $data['result'] = array(
            'code' => intval($code),
            'message' => $message
        );

        // セットヘッダー
        // $this->header("HTTP/1.1 " . $http_status . ' ' . $state_list[$http_status], $http_status);
        // $this->response->statusCode($http_status);
        // $this->header("Content-Type: application/json;");

        $this->set(compact('data'));
        $this->set('_serialize', 'data');

        return;
    }

    public function getCategoryEnabled()
    {
        return CATEGORY_FUNCTION_ENABLED;
    }

    public function getCategorySortEnabled()
    {
        return CATEGORY_SORT;
    }

    public function isCategoryEnabled($page_config, $mode = 'category')
    {
        if (!$this->getCategoryEnabled()) {
            return false;
        }

        if (empty($page_config)) {
            return false;
        }

        $mode = 'is_' . $mode;
        if ($page_config->{$mode} === 'Y' || strval($page_config->{$mode}) === '1') {
            return true;
        }

        return false;
    }

    public function isCategorySort($page_config_id)
    {
        if (!CATEGORY_SORT) {
            return false;
        }

        $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $page_config_id])->first();
        if (empty($page_config)) {
            return false;
        }

        if ($page_config->is_category_sort == 'Y') {
            return true;
        }

        return false;
    }

    public function isViewSort($page_config, $category_id = 0)
    {
        if (
            $this->getCategoryEnabled() && $page_config->is_category === 'Y'
            && ($this->isCategorySort($page_config->id)) || (!$this->isCategorySort($page_config->id) && !$category_id)
        ) {
            return true;
        }

        return false;
    }

    /**
     * 記事がユーザーに権限のあるものかどうか
     * @param  [type]  $info_id [description]
     * @return boolean          [description]
     */
    public function isOwnInfoByUser($info_id)
    {
        $user_id = $this->getSessionUserID();

        $info = $this->Infos->find()
            ->where(['Infos.id' => $info_id])
            ->contain([
                'PageConfigs' => function ($q) {
                    return $q->select(['site_config_id']);
                }
            ])
            ->first();
        if (empty($info)) {
            return false;
        }
        return true;

        $user_site = $this->UseradminSites->find()->where(['UseradminSites.useradmin_id' => $user_id, 'UseradminSites.site_config_id' => $info->page_config->site_config_id])->first();
        if (empty($user_site)) {
            return false;
        }

        return true;
    }

    /**
     * ページがユーザーに権限のあるものかどうか
     * @param  [type]  $page_config_id [description]
     * @return boolean                 [description]
     */
    public function isOwnPageByUser($page_config_id)
    {
        return true;

        $user_id = $this->getSessionUserID();

        $page_config = $this->PageConfigs->find()->where(['PageConfigs.id' => $page_config_id])->first();
        if (empty($page_config)) {
            return false;
        }

        $user_site = $this->UseradminSites->find()->where(['UseradminSites.useradmin_id' => $user_id, 'UseradminSites.site_config_id' => $page_config->site_config_id])->first();
        if (empty($user_site)) {
            return false;
        }

        return true;
    }

    public function isOwnCategoryByUser($category_id)
    {
        return true;
        $user_id = $this->getSessionUserID();

        $category = $this->Categories->find()
            ->where(['Categories.id' => $category_id])
            ->contain([
                'PageConfigs' => function ($q) {
                    return $q->select(['site_config_id']);
                }
            ])
            ->first();
        if (empty($category)) {
            return false;
        }

        $user_site = $this->UseradminSites->find()->where(['UseradminSites.useradmin_id' => $user_id, 'UseradminSites.site_config_id' => $category->page_config->site_config_id])->first();
        if (empty($user_site)) {
            return false;
        }

        return true;
    }

    public function redirectWithException($url, $status = 302)
    {
        throw new \Cake\Routing\Exception\RedirectException(\Cake\Routing\Router::url($url, true), $status);
    }

    public function startupProcess()
    {
        try {
            return parent::startupProcess();
        } catch (\Cake\Routing\Exception\RedirectException $e) {
            return $this->redirect($e->getMessage(), $e->getCode());
        }
    }

    public function invokeAction()
    {
        try {
            return parent::invokeAction();
        } catch (\Cake\Routing\Exception\RedirectException $e) {
            return $this->redirect($e->getMessage(), $e->getCode());
        }
    }

    public function shutdownProcess()
    {
        try {
            return parent::shutdownProcess();
        } catch (\Cake\Routing\Exception\RedirectException $e) {
            return $this->redirect($e->getMessage(), $e->getCode());
        }
    }

    protected function _preventGarbledCharacters($bigText, $width = 249)
    {
        return CustomUtility::_preventGarbledCharacters($bigText, $width);
    }

    //CSVパスから配列を取得する
    public function csv_import($path)
    {
        $data = file_get_contents($path);
        $interenc = mb_internal_encoding();
        $inputenc = mb_convert_variables($interenc, 'ASCII,UTF-8,SJIS-win', $data);

        $temp = tmpfile();
        $meta = stream_get_meta_data($temp);
        fwrite($temp, $data);
        rewind($temp);

        $file = new SplFileObject($meta['uri'], 'rb');
        $file->setFlags(SplFileObject::READ_CSV);
        return $file;
    }

    //CSVエクスポート
    public function csv_export($data, $_header, $filename, $options = [])
    {
        //cakephp2以前
        // $csv = array_merge([$header], $rows);
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename=' . $file_name . '.csv');
        // foreach ($csv as $line) {
        //     // mb_convert_variables('SJIS', 'UTF-8', $line);
        //     fputcsv(fopen('php://output', 'w'), $line);
        // }

        $options = array_merge(
            [
                '_footer' => '',

                '_serialize' => ['data'],
                '_csvEncoding' => 'sjis-win',
                '_dataEncoding' => 'UTF-8',
                '_extension' => 'mbstring',

                '_newline' => "\r\n",
                '_eol' => "\r\n"
            ],
            $options
        );
        extract($options);

        $filename = mb_convert_encoding($filename, 'SJIS-WIN', 'UTF-8');
        $this->response->download($filename . '.csv');
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('data', '_serialize', '_header', '_footer', '_csvEncoding', '_newline', '_eol', '_dataEncoding', '_extension'));
        setcookie('loading', 'complete', 0, '/');
    }

    //拡張子取得
    public function getExtension($filename)
    {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }

    /**
     * ファイルダウンロード　ファイル名が文字化けしないバージョン
     *
     * */
    public function file($id = 0, $columns = null)
    {
        $this->{$this->modelClass}->id = $id;
        if (!$columns) {
            $columns = key($this->{$this->modelName}->attaches['files']);
        }
        if ($this->{$this->modelClass}->exists()) {
            $data = $this->{$this->modelClass}->read();
            $_ = $data[$this->modelClass];
            if ($_[$columns]) {
                $file = WWW_ROOT . $_['attaches'][$columns]['src'];
                $name = $_['attaches'][$columns]['name'];

                $content = 'attachment;';
                $content .= 'filename=' . $name . ';';
                $content .= 'filename*=UTF-8\'\'' . rawurlencode($name);

                if (file_exists($file)) {
                    $this->response->header('Content-Disposition', $content);
                    $this->response->file($file);
                    return $this->response;
                }
            }
        }
        throw new NotFoundException();
    }

    public function getToken()
    {
        return uniqid('', true);
    }

    //rangeの数値を全て2桁で0埋めして、単位をつけて配列で返す。
    public function getDateArray()
    {
        $getConvertDay = function ($range, $unit) {
            $retun = [];
            foreach ($range as $data) {
                $date = sprintf('%02d', $data);
                $retun[$date] = $date . $unit;
            }
            return $retun;
        };
        $today = new \DateTime();
        return [
            'year_list' => $getConvertDay(range(1900, $today->format('Y')), '年'),
            'month_list' => $getConvertDay(range(1, 12), '月'),
            'day_list' => $getConvertDay(range(1, 31), '日'),
        ];
    }

    public function isStaffAccount()
    {
        return ($this->Session->read('user_role') > 1);
    }

    //カテゴリー取得
    public function getCategory($slug, $options = [])
    {
        $PageConfigs = $this->getTableLocator()->get('PageConfigs');
        $Categories = $this->getTableLocator()->get('Categories');
        $page_config = $PageConfigs->find()->where(['slug' => $slug])->extract('id')->first();

        //オプション
        $options = array_merge(
            [
                'organize_parent_relationships' => false,
                'contain' => [],
                'order' => ['parent_category_id' => 'ASC', 'position' => 'ASC'],
                'conditions' => ['Categories.page_config_id' => $page_config, 'Categories.status' => 'publish'],
                'append_conditions' => []
            ],
            $options
        );
        extract($options);

        $conditions = array_merge(
            $conditions,
            $append_conditions
        );

        //全カテゴリー
        $data = $Categories->find()->order($order)->contain($contain)->where($conditions)->toArray();

        //子孫関係で整理しない場合
        if (!$organize_parent_relationships) {
            return $data;
        }

        //子孫関係で整理する
        function organize($targets, $convers, $relations = [], $result = [])
        {
            $new_relations = [];
            foreach ($targets as $k => $target) {
                $target = $target->toArray();
                extract($target);
                $save_data = $convers($target); //

                //親なしの場合
                $is_first_loop = (empty($relations) && $parent_category_id === 0);
                if ($is_first_loop) {
                    $result[$id] = $save_data;
                    $new_relations[$id] = $id;
                    unset($targets[$k]);
                    continue;
                }

                //子孫関連を整理
                $relation = $relations[$parent_category_id] ?? [];
                if (!$relation) {
                    continue;
                }
                $is_second = !is_array($relation);
                if ($is_second) {
                    $new_relations[$id] = [$relation => $relation];
                } else {
                    $new_relations[$id] = [$parent_category_id => $relation];
                }

                //todo 3階層までしかできない
                $r_content = $new_relations[$id] ?? [];
                $parent_id = $r_content && is_array($r_content) ? (array_keys($r_content)[0] ?? 0) : 0;

                $r_content = $new_relations[$id][$parent_id] ?? [];
                $parent_parent_id = $r_content && is_array($r_content) ? (array_keys($r_content)[0] ?? 0) : 0;

                $r_content = $new_relations[$id][$parent_id][$parent_parent_id] ?? [];
                $parent_parent_parent_id = $r_content && is_array($r_content) ? (array_keys($r_content)[0] ?? 0) : 0;

                //更新
                if ($parent_parent_parent_id) {
                    $result[$parent_parent_parent_id]['childs'][$parent_parent_id]['childs'][$parent_id]['childs'][$id] = $save_data;
                    unset($targets[$k]);
                    continue;
                }
                if ($parent_parent_id) {
                    $result[$parent_parent_id]['childs'][$parent_id]['childs'][$id] = $save_data;
                    unset($targets[$k]);
                    continue;
                }
                if ($parent_id) {
                    $result[$parent_id]['childs'][$id] = $save_data;
                    unset($targets[$k]);
                    continue;
                }
            }
            if (empty($result)) {
                pr('親がありません');
                exit;
            }
            //なくなるまでループ
            if ($targets) {
                return organize($targets, $convers, $new_relations, $result);
            }

            return $result;
        }
        //取得するカテゴリーを変換する
        $convers = function ($category) {
            return $category;
            return [
                'name' => $category['name'] ?? ''
            ];
        };
        return organize($data, $convers);
    }

    protected function setHeadTitle($title = Null, $isFull = False)
    {
        $_title = \Cake\Core\Configure::read('App.headTitle');
        if ($title) {
            $title = is_array($title) ? implode('  ', $title) : $title;
            $_title = $isFull ? $title : __('{0}  {1}', [$title, $_title]);
        }
        $this->set('__title__', $_title);
        return $_title;
    }
}
