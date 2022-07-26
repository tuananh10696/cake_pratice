<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use App\Utils\CustomUtility;
use Cake\Network\Session;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use \App\Model\Validation\FormValidator;

class AppForm extends Form {
    //FormValidatorを変更する。
    protected $_validatorClass = FormValidator::class;

    protected function _buildValidator(Validator $validator) {
        //$validator = new FormValidator;
        return $validator;
    }

    //デフォルトのメール設定
    public $mailSetting = [
        'test' => [
            'auto_line_break' => false, //メールの自動改行する

            'from' => 'test+from@caters.co.jp',
            'to_admin' => 'test+to@caters.co.jp',
            'name' => '送信元名',
            'subject_admin' => '【テスト】お問い合わせがありました。', //ない場合は管理者送信しない
            'subject_user' => '【テスト】お問い合わせありがとうございました。', //ない場合はユーザー送信しない
            'template_admin' => 'contact_admin',
            'template_user' => 'contact_user'
        ],
        'honban' => [
            'auto_line_break' => false, //メールの自動改行する

            'from' => 'test+from@caters.co.jp',
            'to_admin' => 'test+to@caters.co.jp',
            'name' => '送信元名',
            'subject_admin' => 'お問い合わせがありました。', //ない場合は管理者送信しない
            'subject_user' => 'お問い合わせありがとうございました。', //ない場合はユーザー送信しない
            'template_admin' => 'contact_admin',
            'template_user' => 'contact_user'
        ]
    ];

    /**
     *
     * new 〇〇($config)
     * 確認画面経由しない設定とか、バリデ後の処理をカスタムしたり。
     *
     * デフォルトでは確認画面を経由して完了画面でメール送信+DB保存
     *
     */
    public function __construct($config = []) {
        $this->Session = new Session();
        $config = array_merge(
            [
                'isUser' => true, //id等を更新させない
                'customMethod' => null, //バリデーション後の処理をカスタムする。子クラスに関数を用意しておく。
                'saveOnly' => false, //確認画面なし、保存だけして終わり

                'require_confirm' => true, //確認画面を経由する。
                'allow_confirm_sendmail' => true, //確認画面経由時のメール送信を実行する。
                'allow_confirm_save' => true, //確認画面経由時のDB保存を実行する。
            ],
            $config
        );
        foreach ($config as $key => $val) {
            $this->{$key} = $val;
        }
    }

    /**
     *
     * バリデ前の変数変更 + バリデ + DB保存 + メール送信を行う。
     * フォームのデータを再構築するため、executeは使わない
     *
     */
    public function MyExecute(array $post_data) {
        $this->post_data = $this->_beforeExecure($post_data);
        return $this->execute($this->post_data);
    }

    /**
     *
     * バリデーション確認してから実行される。 (エラー時は実行されない)
     *
     */
    protected function _execute(array $post_data) {
        //postデータのセット
        $this->setData($this->post_data);

        //バリデーション後の処理をカスタムする。formの関数を読み込む
        if ($this->customMethod) {
            if (method_exists($this, $this->customMethod)) {
                return $this->{$this->customMethod}();
            }
        }

        //セーブして終わり
        // if ($this->saveOnly) {
        //     return (bool) $this->saveDB();
        // }

        //確認画面・完了画面を経由する一般処理。
        return $this->confirmMethod();
    }

    /**
     *
     * 確認画面・完了画面を経由する一般処理。
     *
     */
    public function confirmMethod() {
        //送信して完了画面へ
        $action = $this->post_data['action'] ?? 'index';
        if ($action == 'complete') {
            // トークンが違う場合はリダイレクト
            if (!($this->is_allowed_token())) {
                return false;
            }

            //データ保存　 (init時に設定してれば回避)
            // if ($this->allow_confirm_save && method_exists($this, 'saveDB') && !$this->saveDB()) {
            //     return false;
            // } else {
            //     //保存完了時( init時に設定してれば強制終了)
            //     if ($this->saveOnly) {
            //         return true;
            //     }
            // }

            //メール送信　(init時に設定してれば回避)
            if ($this->allow_confirm_sendmail && !$this->sendmail()) {
                return false;
            }
        }
        return true;
    }

    //データ保存 子クラスで用意していない場合は必ずエラー。
    //　init時にallow_confirm_saveを設定して回避することも可能
    // public function saveDB() {
    //     return false;
    // }

    //メール送信
    public function sendmail() {
        if (!CustomUtility::_sendmail($this->post_data, $this->mailSetting)) {
            return false;
        }
        return true;
    }

    /**
     *
     * execute前にフォームデータを再構築する。
     * トークン認証可否の変数も追加してる。
     *
     */
    public function _beforeExecure($post_data) {
        // $datas = [
        //     [
        //         'implodes' => ' ',
        //         'combines' => [
        //             'name' => [
        //                 'sei',
        //                 'mei'
        //             ],
        //             'kata_name' => [
        //                 'kata_sei',
        //                 'kata_mei'
        //             ],
        //             'hira_name' => [
        //                 'hira_sei',
        //                 'hira_mei'
        //             ],
        //             'addresses' => [
        //                 'pref',
        //                 'city',
        //                 'address',
        //                 'building'
        //             ],
        //         ]
        //     ],
        //     [
        //         'implodes' => '-',
        //         'combines' => [
        //             'buy_date' => [
        //                 'buy_date_year',
        //                 'buy_date_month',
        //                 'buy_date_day',
        //             ],

        //             'zip' => [
        //                 'zip1',
        //                 'zip2',
        //             ],
        //         ]
        //     ],
        // ];

        // foreach ($datas as $k => $data) {
        //     $implodes = $data['implodes'];
        //     $combines = $data['combines'];

        //     foreach ($combines as $will_join_key => $join_keys) {
        //         //sei, meiのどちらかがsetされていたら合わせる。
        //         $joins = $this->rebuild_array_select_keys($post_data, $join_keys);
        //         if (!$joins) {
        //             continue;
        //         }
        //         if ($joins === true) {
        //             $post_data[$will_join_key] = '';
        //             continue;
        //         }
        //         $post_data[$will_join_key] = implode($implodes, $joins);
        //     }
        // }

        /**
         * トークン処理
         */
        $action = $post_data['action'] ?? '';
        if ($action == 'confirm') {
            $token = $this->getToken();
            $post_data['token'] = $token;
            $this->Session->write(['contact.token' => $token]);
        }
        if ($action == 'complete') {
            $token_completed = ($post_data['token'] && $post_data['token'] == $this->Session->read('contact.token'));
            $post_data['token'] = '';
            $post_data['token_completed'] = $token_completed;
            $this->Session->delete('contact.token');
        }

        return $post_data;
    }

    /**
     * その他
     */
    //確認画面経由するためのトークン
    public function getToken() {
        return uniqid('', true);
    }

    //トークン認証可否
    public function is_allowed_token() {
        return (bool) ($this->post_data['token_completed'] ?? false);
    }

    //指定した鍵で配列を再構築する。(値が存在するもの)
    public function rebuild_array_select_keys($data, $keys) {
        $retun = [];
        foreach ($keys as $key) {
            $val = $data[$key] ?? '';

            if (array_key_exists($key, $data)) {
                if (!$data[$key]) {
                    //鍵は作るけど値は空白
                    return true;
                }
                $retun[] = $data[$key];
            }
        }
        //鍵も
        if ($retun) {
            return $retun;
        } else {
            return false;
        }
    }
}
