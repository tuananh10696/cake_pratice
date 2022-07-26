<?php

namespace App\Controller;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\Utility\Inflector;


class NewsController extends AppController
{

    public function beforeFilter(\Cake\Event\Event $event)
    {
        $this->modelName = 'Infos';
        $this->{$this->modelName} = $this->getTableLocator()->get($this->modelName);
        parent::beforeFilter($event);
    }


    public function index()
    {
        $this->viewBuilder()->setLayout('default');
        $this->setHeadTitle('お知らせ | 株式会社BLOCKSMITH&Co.');
        $infos = $this->Cms->findAll('news', ['limit' => 10, 'paginate' => true]);
        $this->set(compact('infos'));
    }


    public function detail($id = null)
    {
        $this->viewBuilder()->setLayout('default');
        $this->setHeadTitle('お知らせ | 株式会社BLOCKSMITH&Co.');
        $info_array = $this->Cms->findFirst('news', $id);
        if (is_null($info_array)) return $this->redirect(['action' => 'index']);
        $info = $info_array['info'] ?? [];
        extract($info_array);

        $this->set(compact('contents', 'info'));
        $this->set('listId', $this->getNextBack($id));
    }

    public function getNextBack($id = null)
    {
        $day = new \DateTime('now');
        $day = $day->format('Y-m-d');
        $cond = [
            'Infos.status' => 'publish',
            'Infos.start_datetime <=' => $day
        ];

        $query = $this->Infos->find('list', [
            'keyField' => 'id',
            'valueField' => 'id'
        ])->where($cond)->order(['Infos.position' => 'ASC']);
        $data = $query->toArray();
        $listId = array_keys($data);

        return $listId;
    }
}
