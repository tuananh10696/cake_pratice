<?php
namespace App\View\Helper;

use Cake\Datasource\ModelAwareTrait;
use App\Model\Entity\EventSchedule;

class CommonHelper extends AppHelper {
    use ModelAwareTrait;

    public function session_read($key) {
        return $this->getView()->getRequest()->getSession()->read($key);
    }

    public function session_check($key) {
        return $this->getView()->getRequest()->getSession()->check($key);
    }

    public function getCategoryEnabled() {
        return CATEGORY_FUNCTION_ENABLED;
    }

    public function getCategorySortEnabled() {
        return CATEGORY_SORT;
    }

    public function isCategoryEnabled($page_config, $mode = 'category') {
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

    public function isCategorySort($page_config_id) {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('PageConfigs');

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

    public function isViewSort($page_config, $category_id = 0) {
        //並び順表示しない
        if ($page_config->disable_position_order == 1) {
            return false;
        }

        //カテゴリー使っていない場合は、並び順表示する
        if ($page_config->is_category !== 'Y') {
            return true;
        }

        //カテゴリーごとに並び替え
        $is_selected_category = (bool) $category_id;
        if ($page_config->is_category_sort == 'Y') {
            //カテゴリーごとに並び替える場合、全て以外なら並び替える。
            return $is_selected_category;
        } else {
            //カテゴリーごとに並び替えない場合、全てでのみ並び替え可能
            return !$is_selected_category;
        }

        return false;
    }

    public function isViewPreviewBtn($page_config) {
        if ($page_config->disable_preview) {
            return false;
        }

        return true;
    }

    public function isUserRole($role_key, $isOnly = false) {
        $role = $this->session_read('user_role');

        if (intval($role) === 0) {
            $res = 'develop';
        } elseif ($role < 10) {
            $res = 'admin';
        } elseif ($role >= 90) {
            $res = 'demo';
        }
        /** 必要に応じて追加 */
        else {
            $res = 'staff';
        }

        if (!$isOnly) {
            if ($role_key == 'develop') {
                $role_key = array('develop');
            }
            if ($role_key == 'admin') {
                $role_key = array('develop', 'admin');
            }
            if ($role_key == 'staff') {
                $role_key = array('develop', 'admin', 'staff');
            }
            if ($role_key == 'demo') {
                $role_key = array('develop', 'admin', 'staff', 'demo');
            }
        }

        if (in_array($res, (array)$role_key)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUserPublisher() {
        return true;
    }

    public function getAdminMenu() {
        return $this->session_read('admin_menu.menu_list');
    }

    public function getAppendFields($info_id) {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('InfoAppendItems');

        $contain = [
            'AppendItems'
        ];
        $append_items = $this->InfoAppendItems->find()->where(['InfoAppendItems.info_id' => $info_id])->contain($contain)->all();
        if (empty($append_items)) {
            return [];
        }

        $result = [];
        foreach ($append_items as $item) {
            // $_data = $item;
            $result[$item->append_item->slug] = $item;
        }

        return $result;
    }

    public function enabledInfoItem($page_id, $type, $key) {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('PageConfigItems');

        return $this->PageConfigItems->enabled($page_id, $type, $key);
    }

    public function infoItemTitle($page_id, $type, $key, $col = 'title', $default = '') {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('PageConfigItems');

        $title = '';
        if ($col == 'title') {
            $title = $this->PageConfigItems->getTitle($page_id, $type, $key, $default);
        } elseif ($col == 'sub_title') {
            $title = $this->PageConfigItems->getSubTitle($page_id, $type, $key, $default);
        }

        if (empty($title)) {
            $title = $default;
        }
        return $title;
    }

    public function getInfoCategories($info_id, $result_type = 'entity', $options = []) {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('Infos');

        return $this->Infos->getCategories($info_id, $result_type, $options);
    }
}
