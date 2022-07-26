<?php
namespace App\Model\Entity;

use Cake\ORM\Entity as baseEntity;
use Cake\ORM\TableRegistry;
// use Cake\Utility\Hash;
//use App\Model\Entity\DokkyoLiteral;
use Cake\I18n\FrozenTime;
use Cake\I18n\Date;
use Cake\Utility\Hash;
//mail
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\Routing\Router;

class AppEntity extends baseEntity {
    /**
     * 各Entityに必要
     */
    public function detailUrl($addQuery = []) {
        return Router::url([
            'controller' => $this->model_name,
            'action' => 'edit',
            $this->id ?? 0
        ]);
    }

    protected $_virtual = ['h_content', 'n_content', 'h_title', 'is_new', 'model_name'];

    public function _getModelName() {
        return $this->_registryAlias;
    }

    public function _getIsNew($value) {
        $data = $this->_properties;

        if (!isset($data['id']) || !isset($data['start_datetime'])) {
            return false;
        }
        if (!is_object($data['start_datetime'])) {
            return false;
        }

        $now = new \DateTime();
        return ($now <= $data['start_datetime']->modify('+1 weeks'));
    }

    public function _getIsNewDate($value) {
        $data = $this->_properties;

        if (!isset($data['id']) || !isset($data['date'])) {
            return false;
        }
        if (!is_object($data['date'])) {
            return false;
        }

        $now = new \DateTime();
        return ($now <= $data['date']->modify('+1 weeks'));
    }

    public function _getHContent($value) {
        $data = $this->_properties;

        if (!isset($data['id']) || !isset($data['content'])) {
            return '';
        }

        return nl2br(h($data['content']));
    }

    public function _getNContent($value) {
        $data = $this->_properties;

        if (!isset($data['id']) || !isset($data['content'])) {
            return '';
        }

        return nl2br($data['content']);
    }

    public function _getHTitle($value) {
        $data = $this->_properties;

        if (!isset($data['id']) || !isset($data['title'])) {
            return '';
        }

        return h($data['title']);
    }
}
