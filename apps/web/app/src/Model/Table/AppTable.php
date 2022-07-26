<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Filesystem\Folder;
use Cake\Utility\Text;
use Cake\Datasource\ConnectionManager;
use App\Utils\CustomUtility;

class AppTable extends Table {
    public function initialize(array $config) {
        // 作成日時と更新日時の自動化
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
    }

    /*
    AppControllerとも共通の関数　CustomUtilityに保管
    */

    public function getMonthFirstDay($date, $format = 'Y-m-d') {
        return CustomUtility::getMonthFirstDay($date, $format);
    }

    public function getMonthLastDay($date, $format = 'Y-m-d') {
        return CustomUtility::getMonthLastDay($date, $format);
    }

    public function getNow($format = 'Y-m-d H:i') {
        return CustomUtility::getNow($format);
    }

    public function isExternalIP() {
        return CustomUtility::isExternalIP();
    }

    public function getIp() {
        return CustomUtility::getIp();
    }

    public function getReferrer() {
        return CustomUtility::getReferrer();
    }

    public function getSessionUser() {
        return CustomUtility::getSessionUser();
    }

    public function getSessionUserID() {
        return CustomUtility::getSessionUserID();
    }

    public function isLogin() {
        return CustomUtility::isLogin();
    }

    public function isAdmin() {
        return CustomUtility::isAdmin();
    }

    public function isApprover() {
        return CustomUtility::isApprover();
    }

    public function getDate($date = '', $format = '') {
        return CustomUtility::getDate($date, $format);
    }

    //ひらがなから、あかさたなを算出する
    public function getHiraganaAKSTN($hira) {
        return CustomUtility::getHiraganaAKSTN($hira);
    }

    // cakePHP2と互換性を保つためにcreateを自前で作る
    public function create($data) {
        $entity = $this->createEntity()->toArray();

        return $entity;
    }

    public function createEntity($data = null) {
        if (is_null($data)) {
            $data = $this->defaultValues;
        }
        $entity = $this->newEntity($data);

        return $entity;
    }

    public function toFormData($query) {
        $data = $query->toArray();

        return $data;
    }

    public function copyPreviewAttachement($source_id, $distModel) {
        // コピー元
        $source = $this->find()->where([$this->getAlias() . '.id' => $source_id])->first();

        if (empty($source)) {
            return false;
        }

        // 画像
        $basedir = UPLOAD_DIR . $this->getAlias() . DS . 'images' . DS;
        $distDir = UPLOAD_DIR . $distModel . DS . 'images' . DS;

        $image_columns = $this->attaches['images'];

        $r = true;

        if (!empty($image_columns)) {
            foreach ($image_columns as $column => $imageConfig) {
                if (empty($source->attaches[$column])) {
                    continue;
                }
                foreach ($source->attaches[$column] as $path) {
                    if (empty($path)) {
                        continue;
                    }
                    $copy = WWW_ROOT . ltrim($path, '/');
                    $dist = str_replace($basedir, $distDir, $copy);
                    copy($copy, $dist);
                }
            }
        }

        // ファイル

        $basedir = UPLOAD_DIR . $this->getAlias() . DS . 'files' . DS;
        $distDir = UPLOAD_DIR . $distModel . DS . 'files' . DS;

        $file_columns = $this->attaches['files'];

        if (!empty($file_columns)) {
            foreach ($file_columns as $column => $config) {
                if (empty($source->attaches[$column])) {
                    continue;
                }
                $src = $source->attaches[$column]['src'];
                if (empty($src)) {
                    continue;
                }
                $copy = WWW_ROOT . ltrim($src, '/');
                $dist = str_replace($basedir, $distDir, $copy);
                copy($copy, $dist);
            }
        }

        return $r;
    }

    // public function copyFile($id) {
    //     $table = str_replace('preview_', '', $this->getTable());
    //     $preview_table = 'preview_' . $table;

    //     $sql = 'select * from ' . $table;
    //     $sql .= ' where id = :id';

    //     $bind = ['id' => $id];
    //     $dataType = ['id' => 'integer'];

    //     $source = $this->_exec($sql, $bind, $dataType);

    //     if (empty($source)) {
    //         return;
    //     }

    // }

    private function _exec($sql, $bind, $dataType) {
        $conn = ConnectionManager::get('default');
        $stmt = $conn->prepare($sql);
        $stmt->bind($bind, $dataType);

        $stmt->execute();
        $datas = $stmt->fetchAll('assoc');

        return $datas;
    }

    public function checkDateTimeFormat($value, $context) {
        return true;
    }

    public function checkAlphabetNum($value) {
        if (preg_match('/^[a-zA-Z0-9]+$/u', $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkDateFormat($value, $context) {
        $date = str_replace('/', '-', $value);
        if (preg_match('/\A\d{4}-\d{2}-\d{2}\z/', $date, $match)) {
            $nums = explode('-', $match[0]);
            if (checkdate($nums[1], $nums[2], $nums[0])) {
                return true;
            }
        }
        return false;
    }
}
