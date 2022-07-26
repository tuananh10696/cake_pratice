<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ModelAwareTrait;
use Cake\Utility\Inflector;

/**
 * OutputHtml component
 */
class OutputHtmlComponent extends Component {
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    use ModelAwareTrait;

    public function index($slug) {
        $slug = str_replace('__', '/', $slug);
        $dir = USER_PAGES_DIR . $slug;
        $file = $dir . DS . 'index.html';

        $params = explode('/', $slug); // [0]=site_name [1]=page_name
        $site_slug = $params[0];
        $page_slug = $params[1];

        if (count($params) < 2) {
            $page_slug = '';
        } elseif (count($params) >= 3) {
            $site_slug = $params[0] . '__' . $params[1];
            $page_slug = $params[2];
            for ($i = 3;$i < count($params);$i++) {
                $page_slug .= '__' . $params[$i];
            }
        }

        $html = $this->_registry->getController()->requestAction(
            ['controller' => 'Contents', 'action' => 'index', 'pass' => ['site_slug' => $site_slug, 'slug' => $page_slug]],
            ['return', 'bare' => false]
        );

        file_put_contents($file, $html);

        chmod($file, 0666);
    }

    /**
     * 記事詳細の書き出し　body,meta情報等の外枠のみ
     * @param  [type] $info_id [description]
     * @return [type]          [description]
     */
    public function detail($model, $info_id, $slug) {
        $this->loadModel($model);
        return;
        if (empty($slug)) {
            $slug = HOME_DATA_NAME;
        }

        $slug = str_replace('__', '/', $slug);

        $params = explode('/', $slug);
        $site_slug = $params[0];
        $page_slug = $params[1];

        if (count($params) < 2) {
            $page_slug = '';
        } elseif (count($params) >= 3) {
            $site_slug = $params[0] . '__' . $params[1];
            $page_slug = $params[2];
            for ($i = 3;$i < count($params);$i++) {
                $page_slug .= '__' . $params[$i];
            }
        }

        $slug = rtrim($slug, '/');

        $info = $this->{$model}->find()->contain(['Categories'])->where([$model . '.id' => $info_id])->first();

        if (empty($info)) {
            $dir = USER_PAGES_DIR . $slug;
            $file = $dir . DS . "{$info_id}.html";
            if (is_file($file)) {
                @unlink($file);
            }
            $file = $dir . DS . USER_JSON_URL . "/{$info_id}.json";
            if (is_file($file)) {
                @unlink($file);
            }
            return;
        }

        $dir = USER_PAGES_DIR . $slug;
        $file = $dir . DS . "{$info_id}.html";

        if ($info->status == 'draft' || ($info->category_id > 0 && $info->category->status == 'draft')) {
            // pr($file);exit;
            if (is_file($file)) {
                @unlink($file);
            }
        } else {
            $action = 'detail';
            $tmp_slug = explode('__', $site_slug);
            if (count($tmp_slug) > 1) {
                $action .= '_' . $tmp_slug[0];
                $action = Inflector::variable($action);
                $site_slug = $tmp_slug[1];
            }

            $html = $this->_registry->getController()->requestAction(
                ['controller' => 'Contents', 'action' => $action, 'pass' => ['site_slug' => $site_slug, 'slug' => $page_slug, 'id' => $info_id]],
                ['return', 'bare' => false]
            );

            file_put_contents($file, $html);

            chmod($file, 0666);
        }
    }

    public function writeJson($data, $info_id, $status, $slug) {
        $json = json_encode($data);

        if (empty($slug)) {
            $slug = HOME_DATA_NAME;
        }
        $slug = str_replace('__', '/', $slug);

        $slug = rtrim($slug, '/');

        $dir = USER_PAGES_DIR . $slug . DS . USER_JSON_URL;
        $file = $dir . DS . "{$info_id}.json";

        if ($status == 'draft') {
            if (is_file($file)) {
                @unlink($file);
            }
        } else {
            // file_put_contents($file, $json);

            // chmod($file, 0666);
        }
    }

    public function _existsJson($info_id, $slug) {
        $slug = str_replace('__', '/', $slug);
        $slug = rtrim($slug, '/');
        $dir = USER_PAGES_DIR . $slug . DS . USER_JSON_URL;
        $file = $dir . DS . "{$info_id}.json";

        if (is_file($file)) {
            return true;
        }
        return false;
    }

    private function userId() {
        return $this->_registry->getController()->getUserId();
    }
}
