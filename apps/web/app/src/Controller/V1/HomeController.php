<?php

namespace App\Controller\V1;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
// use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Hash;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class HomeController extends AppController {
    private $list = [];

    public function initialize() {
        parent::initialize();

        $this->Infos = $this->getTableLocator()->get('Infos');
        $this->SiteConfigs = $this->getTableLocator()->get('SiteConfigs');

        $this->modelName = 'Infos';
        $this->set('ModelName', $this->modelName);
    }

    public function beforeFilter(Event $event) {
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('plain');

        // $this->getEventManager()->off($this->Csrf);
    }

    public function get($site_slug) {
        $site_config = $this->SiteConfigs->find()->where(['SiteConfigs.slug' => $site_slug])->first();
        if (empty($site_config)) {
            $this->rest_error(1000);
            return;
        }

        $condition = [
            'Infos.status' => 'publish',
            'PageConfigs.site_config_id' => $site_config->id,
            'OR' => [
                ['Categories.status' => 'publish'],
                ['Categories.id IS' => null]
            ]
        ];

        // お知らせ
        $infos = $this->_getInfos($site_config, 'information', $condition, 1);

        // Data
        $datas = $this->_getInfos($site_config, 'data', $condition, 10);

        // News
        $news = $this->_getInfos($site_config, 'news', $condition, 0);

        $result = [
            'infos' => $infos,
            'datas' => $datas,
            'news' => $news,
        ];

        $this->rest_success($result);
    }

    public function _getInfos($site_config, $slug, $cond, $limit = 0) {
        $cond['PageConfigs.slug'] = $slug;

        $q = $this->Infos->find()
                          ->where($cond)
                          ->contain([
                              'PageConfigs',
                              'Categories' => function ($q) {
                                  return $q->select(['id', 'name', 'identifier']);
                              }
                          ])
                          ->order(['Infos.start_date' => 'DESC']);
        if ($limit) {
            $q->limit($limit);
        }
        $entities = $q->all();

        if (empty($entities)) {
            return [];
        }

        $result = [];
        foreach ($entities as $v) {
            $tmp = [];
            $tmp['id'] = $v->id;
            $tmp['title'] = $v->title;
            $tmp['date'] = $v->start_date->format('Y.m.d');
            if ($this->isCategoryEnabled($v->page_config)) {
                $tmp['category_id'] = $v->category->id;
                $tmp['category_name'] = $v->category->name;
                $tmp['category_style'] = $v->category->identifier;
            }
            $tmp['position'] = $v->position;
            $tmp['image'] = ($v->attaches['image']['0'] ?: '');
            $tmp['link'] = "/{$site_config->slug}/{$v->page_config->slug}/{$v->id}.html";

            $result[] = $tmp;
        }

        return $result;
    }

    public function setList() {
        $list = array();

        if (!empty($list)) {
            $this->set(array_keys($list), $list);
        }

        $this->list = $list;
        return $list;
    }
}
