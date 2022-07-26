<?php

namespace App\Controller\UserAdmin;

use Cake\Event\Event;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SchedulesController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('user');
        $this->setCommon();
        $this->loadModel('Infos');

        $this->modelName = $this->name;
    }


    public function index()
    {
        $this->checkLogin();
        $this->getDataInfo();
    }


    public function edit($id = 0)
    {
        $this->checkLogin();
        $this->getDataInfo();

        parent::_edit($id, []);
    }


    public function delete($id, $type, $columns = null)
    {
        $this->checkLogin();
        $dataInfo = $this->getDataInfo();
        parent::_delete($id, $type, $columns, ['redirect' => ['action' => 'index', '?' => ['info_id' => $dataInfo->id]]]);
    }


    protected function getDataInfo()
    {
        $info_id = $this->request->getQuery('info_id');
        $slug = 'opencampus';

        $data_info = $this->Infos
            ->find('all')
            ->where([
                'Infos.id' => $info_id,
                'Categories.value_text IN' => ['open', 'online'],
                'PageConfigs.slug' => $slug,
            ])
            ->contain(['Categories', 'PageConfigs', 'Schedules'])
            ->first();

        if (is_null($data_info)) $this->redirect(['prefix' => 'user_admin', 'controller' => 'infos', 'action' => 'index', '?' => ['page_slug' => $slug]]);
        $this->set(compact('data_info', 'slug'));
        return $data_info;
    }
}
