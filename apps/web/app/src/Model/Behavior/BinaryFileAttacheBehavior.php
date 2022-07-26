<?php
namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Utility\Text;
use Cake\Filesystem\Folder;
use Cake\Event\EventManager;
use App\Utils\CustomUtility;
use Cake\Utility\Inflector;

class BinaryFileAttacheBehavior extends Behavior {
    public $uploadDirCreate = true;
    public $uploadDirMask = 0777;
    public $uploadFileMask = 0666;

    //ImageMagick configure
    public $convertParams = '-thumbnail';

    protected $_defaultConfig = array(
        'uploadDir' => null,
        'wwwUploadDir' => null,
    );

    public $uploadDir = '';
    public $wwwUploadDir = '';

    /**
     * 初期設定
     */
    public function initialize(array $config) {
        $Model = $this->getTable();
        $entity = $this->getTable()->newEntity();
        $entity->setVirtual(['attaches']);

        $uploadBasePath = $Model->getTable();
        $uploadBasePath = Inflector::camelize($uploadBasePath);
        $this->uploadBasePath = $uploadBasePath;

        $this->_config = $config + $this->_defaultConfig;
        $this->uploadDir = UPLOAD_DIR . $uploadBasePath;
        $this->wwwUploadDir = '/' . UPLOAD_BASE_URL . '/' . $uploadBasePath;
        if (!empty($config['uploadDir'])) {
            $this->uploadDir = $config['uploadDir'];
        }
        if (!empty($config['wwwUploadDir'])) {
            $this->wwwUploadDir = $config['wwwUploadDir'];
        }

        $this->AttachesImages = array_keys($Model->attaches['images']);
        $this->AttachesFiles = array_keys($Model->attaches['files']);
        $this->AttachesKeys = array_merge($this->AttachesImages, $this->AttachesFiles);
    }

    /**
     * newEntity時だった気がする
     * 画像はsessionに保存しプレビューリンクを用意する。
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        $table = $event->getSubject();

        //attachesのデータをsession保存する。
        foreach ($this->AttachesKeys as $key) {
            $tmp = $data[$key] ?? [];
            $token = ($data['attaches_token'] ?? '') ? $data['attaches_token'] : $this->getToken();//sessionと紐付ける。
            $data['attaches_token'] = $token;
            $saved_attache_data = $this->sessionsave_attache_files($token, $key . '_new', $tmp);
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
        }
    }

    /**
     * 取得時
     * attaches配列を付与する。
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary) {
        $table = $event->getSubject();
        $query->formatResults(function ($results) use ($table, $primary) {
            return $results->map(function ($row) use ($table, $primary) {
                if (is_object($row) && !array_key_exists('existing', $row)) {
                    $results = $this->setAttaches($table, $row, $primary);
                }
                return $row;
            });
        });
    }

    /**
     * セーブ直前
     * 保存直前にファイルアップロードしてカラムにパスを入れる。
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        $table = $event->getSubject();

        $id = $entity->id ?? 0;
        $old_entity = $table->find()->where([$table->getAlias() . '.' . $table->getPrimaryKey() => $id])->first();

        $attaches_token = ($entity->attaches_token ?? '') ? $entity->attaches_token : '';
        foreach ($this->AttachesKeys as $key) {
            //新しいファイルがない場合は、旧ファイルのまま上書きしない
            $is_saved = $entity->{'_saved_' . $key} ?? false;
            if (!$is_saved) {
                continue;
            }

            //sessionが破棄されていたらエラー
            $attache_files = $_SESSION['attache_files'][$attaches_token][$key . '_new'] ?? [];
            if (!$attache_files) {
                return false;
            }

            //sessionの保存データからtmpファイルを作成する。
            $tmp_file = tmpfile();
            fwrite($tmp_file, $attache_files['data']);
            fseek($tmp_file, 0);
            $tmp_path = stream_get_meta_data($tmp_file)['uri'] ?? '';
            $file = [
                'tmp_name' => $tmp_path,
                'name' => $attache_files['name'] ?? '',
                'size' => $attache_files['size'] ?? '',
            ];

            //アップロード処理
            $type = in_array($key, $this->AttachesImages) ? 'images' : (in_array($key, $this->AttachesFiles) ? 'files' : '');
            $tableConf = $table->attaches[$type][$key] ?? [];

            $uploaded = $this->uploadFiles($file, $tableConf, $id, $type);
            $newname = $uploaded['newname'] ?? '';
            if (!$newname) {
                fclose($tmp_file);
                continue;
            }
            //entityにデータ追加(保存する)
            if ($type == 'images') {
                $entity->{$key} = $newname;
            }
            if ($type == 'files') {
                $entity->{$key} = $uploaded['newname'];
                $entity->{$key . '_name'} = $this->getFileName($uploaded['name'], $uploaded['ext']);
                $entity->{$key . '_size'} = $uploaded['size'];
                $entity->{$key . '_extension'} = $uploaded['ext'];
            }

            //tmpファイル破棄
            fclose($tmp_file);

            // 旧ファイルの削除
            $attaches = $old_entity['attaches'][$key] ?? [];
            foreach ($attaches as $file_path) {
                if ($file_path && is_file(WWW_ROOT . $file_path)) {
                    @unlink(WWW_ROOT . $file_path);
                }
            }
        }
        //セッション破棄
        unset($_SESSION['attache_files'][$attaches_token]);
    }

    //attachesの更新は_uploadAttachesか、_deleteのみ。
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        $table = $event->getSubject();
        
        // pr('after');
        // exit;
        
        // //アップロード処理
        // $this->_uploadAttaches($event, $entity);
        
        $id = $entity->id;
        $old_entity = $table->find()->where([$table->getAlias() . '.' . $table->getPrimaryKey() => $id])->first();
        if ($old_entity) {
            $attaches = $this->getAttachesData($table, $old_entity->toArray());

        } else {
            $attaches = $this->getAttachesData($table, []);
        }
        $entity->attaches = $attaches;
    }

    //attachesの実データをまとめる。
    public function getAttachesData($table, $data) {
        $_att_images = $table->attaches['images'] ?? [];
        $_att_files = $table->attaches['files'] ?? [];

        $attaches = [];

        //image
        foreach ($_att_images as $columns => $_att) {
            $_attaches = [];
            $attaches_path = $data[$columns] ?? '';

            $_attaches['0'] = '';
            $_file = $this->wwwUploadDir . '/images/' . $attaches_path;
            if (is_file(WWW_ROOT . $_file)) {
                $_attaches['0'] = $_file;
            }

            foreach ($_att['thumbnails'] as $_name => $_val) {
                $key_name = (!is_int($_name)) ? $_name : $_val['prefix'];
                $_attaches[$key_name] = '';
                $_file = $this->wwwUploadDir . '/images/' . $_val['prefix'] . $attaches_path;
                if (is_file(WWW_ROOT . $_file)) {
                    $_attaches[$key_name] = $_file;
                }
            }
            $attaches[$columns] = $_attaches;
        }

        //file
        foreach ($_att_files as $columns => $_att) {
            $def = array('0', 'src', 'extention', 'name', 'download');
            $def = array_fill_keys($def, null);

            $attaches_path = $data[$columns] ?? '';

            $_attaches = $def;
            $_file = $this->wwwUploadDir . '/files/' . $attaches_path;

            if (is_file(WWW_ROOT . $_file)) {
                $uploadBasePath = $table->getTable();
                $uploadBasePath = Inflector::camelize($uploadBasePath);

                $_attaches['0'] = $_file;
                $_attaches['src'] = $_file;
                $_attaches['extention'] = $this->getExtension($data[$columns . '_name']);
                $_attaches['name'] = $data[$columns . '_name'];
                $_attaches['size'] = $data[$columns . '_size'];
                $_attaches['download'] = '/file/' . $uploadBasePath . '/' . $data[$table->getPrimaryKey()] . '/' . $columns . '/';
                $_attaches['view'] = '/file/' . $uploadBasePath . '/' . $data[$table->getPrimaryKey()] . '/' . $columns . '/view';
            }
            $attaches[$columns] = $_attaches;
        }
        return $attaches;
    }

    //fileをtableConfに合わせ変換してアップロードする。
    public function uploadFiles($file, $tableConf, $id, $type = 'images') {
        // $file = [
        //     'tmp_name' => '',
        //     'name' => '',
        //     'size' => ''
        // ];
        $basedir = $this->uploadDir . DS . $type . DS;

        $uuid = Text::uuid();
        $ext = $this->getExtension($file['name']);//拡張子
        $newname = sprintf($tableConf['file_name'], $id, $uuid) . '.' . $ext;//ファイル名

        //確認
        $in_req_extentions = (in_array($ext, $tableConf['extensions']));
        $get_size = ($file['size'] ?? 0) ? $file['size'] : getimagesize($file['tmp_name']);
        if (!$in_req_extentions || !$get_size) {
            return false;
        }

        if ($type == 'files') {
            rename($file['tmp_name'], ($basedir . $newname));
            chmod($basedir . $newname, $this->uploadFileMask);
        }
        if ($type == 'images') {
            //変換
            $convert_method = (!empty($tableConf['method'])) ? $tableConf['method'] : null;//方法
            $convert_new_name = $newname;//ファイル名

            $this->convert_img(
                $tableConf['width'] . 'x' . $tableConf['height'],
                $file['tmp_name'],
                $basedir . $convert_new_name,
                $convert_method
            );

            //サムネイル
            if (!empty($tableConf['thumbnails'])) {
                foreach ($tableConf['thumbnails'] as $suffix => $val) {
                    $convert_method = (!empty($val['method'])) ? $val['method'] : null;//画像処理方法
                    $convert_new_name = ((!empty($val['prefix'])) ? $val['prefix'] : $suffix) . $newname;//ファイル名
                    //変換
                    $this->convert_img(
                        $val['width'] . 'x' . $val['height'],
                        $file['tmp_name'],
                        $basedir . $convert_new_name,
                        $convert_method
                    );
                }
            }
        }

        return [
            'newname' => $newname,
            'name' => $file['name'] ?? '',
            'ext' => $ext,
            'size' => $file['size'] ?? '',
        ];
    }

    /**
     *
     * tmpパスからバイナリーデータを生成して、指定されたトークン鍵でセッション保存する。
     *
     */
    public function sessionsave_attache_files($token, $key, $tmp) {
        $attache_files = [];
        $path = $tmp['tmp_name'] ?? '';
        $data = @file_get_contents($path);
        $imagetype = @exif_imagetype($path);
        $size = $tmp['size'] ?? '';
        $name = $tmp['name'] ?? '';

        if (!$data) {
            return [];
        }

        //画像だった場合
        if ($imagetype) {
            //バイナリー状のまま回転させる。(写真対応)
            $Image = new \Image();
            $data = $Image->rotateFromBinary($data);

            $attache_files = [
                'path' => $path,
                'name' => $name,
                'size' => $size,
                'data' => $data,
                'type' => $imagetype,
            ];
        }

        //ファイルだった場合
        if (!$imagetype) {
            $attache_files = [
                'path' => $path,
                'name' => $name,
                'size' => $size,
                'data' => $data,
                'type' => $this->getExtension($name),
            ];
        }

        //返却
        if (!$attache_files) {
            return [];
        }
        $_SESSION['attache_files'][$token][$key] = $attache_files;
        return $attache_files;
    }

    /**
     * ファイルアップロード
     * @param $size [width]x[height]
     * @param $source アップロード元ファイル(フルパス)
     * @param $dist 変換後のファイルパス（フルパス）
     * @param $method 処理方法
     *        - fit     $size内に収まるように縮小
     *        - cover   $sizeの短い方に合わせて縮小
     *        - crop    cover 変換後、中心$sizeでトリミング
     * */
    public function convert_img($size, $source, $dist, $method = 'fit') {
        list($ow, $oh, $info) = getimagesize($source);
        $sz = explode('x', $size);
        $cmdline = CustomUtility::convertPath();
        //サイズ指定ありなら
        if (0 < $sz[0] && 0 < $sz[1]) {
            if ($ow <= $sz[0] && $oh <= $sz[1]) {
                //枠より完全に小さければ、ただのコピー
                $size = $ow . 'x' . $oh;
                $option = $this->convertParams . ' ' . $size . '>';
            } else {
                //枠をはみ出していれば、縮小
                if ($method === 'cover' || $method === 'crop') {
                    //中央切り取り
                    $crop = $size;
                    if (($ow / $oh) <= ($sz[0] / $sz[1])) {
                        //横を基準
                        $size = $sz[0] . 'x';
                    } else {
                        //縦を基準
                        $size = 'x' . $sz[1];
                    }

                    //cover
                    $option = '-thumbnail ' . $size . '>';

                    //crop
                    if ($method === 'crop') {
                        $option .= ' -gravity center -crop ' . $crop . '+0+0';
                    }
                } else {
                    //通常の縮小 拡大なし
                    $option = $this->convertParams . ' ' . $size . '>';
                }
            }
        } else {
            //サイズ指定なしなら 単なるコピー
            $size = $ow . 'x' . $oh;
            $option = $this->convertParams . ' ' . $size . '>';
        }
        $a = system(escapeshellcmd($cmdline . ' ' . $option . ' ' . $source . ' ' . $dist));
        @chmod($dist, $this->uploadFileMask);
        return $a;
    }

    /**
    * attaches配列を付与する。
    */
    protected function setAttaches($table, $results, $primary = false) {
        $this->checkUploadDirectory($table);

        // $results->setVirtual(['attaches']);
        $attaches = $this->getAttachesData($table, $results->toArray());
        $results->attaches = $attaches;

        //find取得時にもattchesのデータを保存する。
        foreach ($attaches as $key => $paths) {
            // $path = $paths[0] ?? '';
            $token = ($results->attaches_token ?? '') ? $results->attaches_token : $this->getToken();//sessionと紐付ける。
            // if ($path) {
            //     $path = WWW_ROOT . 'upload/..' . $path;
            //     $this->sessionsave_attache_files($token, $key, $path);
            // }
            $results->attaches_token = $token;
            // $entity['_is_uploaded_' . $key] = (bool) ($path);
        }

        return $results;
    }

    /**
     *
     * その他
     *
     * */
    //uploadフォルダーがなければ作成する。
    public function checkUploadDirectory($table) {
        $Folder = new Folder();

        if ($this->uploadDirCreate) {
            $dir = $this->uploadDir . DS . 'images';
            if (!is_dir($dir) && !empty($table->attaches['images'])) {
                if (!$Folder->create($this->uploadDir . DS . 'images', $this->uploadDirMask)) {
                }
            }

            $dir = $this->uploadDir . DS . 'files';
            if (!is_dir($dir) && !empty($table->attaches['files'])) {
                if (!$Folder->create($dir, $this->uploadDirMask)) {
                }
            }
        }
    }

    public function getExtension($filename) {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }

    public function getFileName($filename, $ext) {
        return str_replace('.' . $ext, '', $filename);
    }

    public function getToken($length = 8) {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }
}
