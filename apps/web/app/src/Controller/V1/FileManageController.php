<?php

namespace App\Controller\V1;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;
use Cake\Routing\RequestActionTrait;

//use App\Model\Entity\DokkyoLiteral;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class FileManageController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->modelName = $this->name;
        $this->set('ModelName', $this->modelName);
    }

    public function beforeFilter(Event $event) {
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('plain');
        $this->getEventManager()->off($this->Csrf);

        // $this->Admins = $this->getTableLocator()->get('Admins');
        // $this->checkLogin();
    }

    /**
     *
     * infoContents等のattachesファイルを取得する。
     *
     */
    public function manageAttaches($model = 'infoContents', $id = 0, $attache_name = 'file', $is_view = 0) {
        $this->{$model} = $this->getTableLocator()->get($model);
        if (!$this->{$model}) {
            return;
        }

        $data = $this->{$model}->find()->where(['id' => $id])->first();
        if (!$data) {
            return;
        }

        $file = $data['attaches'][$attache_name] ?? [];
        $format_data = [
            [
                'path' => WWW_ROOT . ($file[0] ?? ''),
                'name' => ($file['name'] ?? ''),
            ]
        ];

        $is_view = (bool) $is_view;
        return $this->download($format_data, $is_view);
    }

    //file_get_contentsのデータを表示する。 事前にsessionにtypeとdataを保存しとく。
    public function viewContentFile($token, $key) {
        $data = $_SESSION['attache_files'][$token][$key]['data'] ?? '';//file_get_contents
        $type = $_SESSION['attache_files'][$token][$key]['type'] ?? '';//拡張子
        if (!$data || !$type) {
            return;
        }
        switch ($type) {
            case IMAGETYPE_JPEG:
            header('content-type: image/jpeg');
            break;
            case IMAGETYPE_PNG:
            header('content-type: image/png');
            break;
            case IMAGETYPE_GIF:
            header('content-type: image/gif');
            break;
            default:
            header("content-type: application/{$type}");
            break;
        }
        echo $data;
        exit;
    }

    /**
     *
     *
     * ファイルダウンロード
     * データのフォーマットは以下
     *
     *
     *
     */
    // $format_data = [
    //     [
    //         'path' => 'ファイルまでのフルパス',//WWW_ROOT~
    //         'name' => 'ファイル名',
    //     ]
    // ];
    public function download($format_data = [], $is_view = false) {
        if (count($format_data) == 1) {
            return $this->output_file($format_data[0], $is_view);
        }
        return $this->output_zip($format_data);
    }

    //ファイル出力
    //ファイルダウンロードか、開くを指定できる。
    public function output_file($data, $is_view = false) {
        $filename = $data['name'];

        $filename = mb_convert_encoding($filename, 'SJIS-WIN', 'UTF-8');

        $content = '';
        //$content .= 'filename=' . rawurlencode($filename) . ';';
        $content .= 'filename*=UTF-8\'\'' . rawurlencode($filename);

        $path = $data['path'];
        if (file_exists($path)) {
            $this->response->header('Content-Disposition', $content);
            $ext = $this->getExtension($path);
            $this->response->file($path, ['download' => (!$is_view), 'name' => $filename . '.' . $ext]);
            return $this->response;
        }
    }

    //ZIP出力
    public function output_zip($datas) {
        header('Content-Type: text/html; charset=UTF-8');

        //ZIP用意
        $zip = new \ZipArchive();
        $tmpname = 'files-' . date('Ymd');
        $tmpZipPath = '/tmp/' . $tmpname . '.zip';
        if (file_exists($tmpZipPath)) {
            unlink($tmpZipPath);
        }
        if ($zip->open($tmpZipPath, \ZipArchive::CREATE) === false) {
            throw new IllegalStateException("failed to create zip file. ${tmpZipPath}");
        }

        $c = 0;
        foreach ($datas as $key => $data) {
            $c++;

            $ext = $this->getExtension($data['path']);
            $filename = $data['name'] . '_' . date('Ymd') . '-' . $c . '.' . $ext;
            $filename = mb_convert_encoding($filename, 'SJIS-WIN', 'UTF-8');

            $zip->addFile($data['path'], $filename);
        }

        if ($zip->close() === false) {
            throw new IllegalStateException("failed to close zip file. ${tmpZipPath}");
        }

        $tmpname = mb_convert_encoding($tmpname, 'SJIS-WIN', 'UTF-8');

        if (file_exists($tmpZipPath)) {
            $this->response->type('application/zip');
            $this->response->file($tmpZipPath, array('download' => true));
            $this->response->download($tmpname . '.zip');
            $this->response->header('Pragma', 'public');
            $this->response->header('Expires', '0');
            $this->response->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
            $this->response->header('Content-Transfer-Encoding', 'binary');
            $this->response->header('Content-Type', 'application/octet-streams');
            $this->response->header('Content-Disposition', 'attachment; filename=' . $tmpname . '.zip');

            setcookie('loading', 'complete', 0, '/');
            return $this->response;
        } else {
            return false;
        }
    }

    /**
     *
     * その他
     */
    public function getExtension($filename) {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }
}
