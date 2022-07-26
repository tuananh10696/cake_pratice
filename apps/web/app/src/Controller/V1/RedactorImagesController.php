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
class RedactorImagesController extends AppController {
    private $list = [];

    public function initialize() {
        $this->loadComponent('Csrf');

        $this->modelName = $this->name;
        $this->{$this->modelName} = $this->getTableLocator()->get($this->modelName);
        $this->set('ModelName', $this->modelName);

        $this->Infos = $this->getTableLocator()->get('Infos');

        parent::initialize();
    }

    public function beforeFilter(Event $event) {
        $this->viewBuilder()->setLayout('plain');

        $this->getEventManager()->off($this->Csrf);
    }

    public function upload($model) {
        $this->checkLogin();
        $this->autoRender = false;

        //postデータが変になってるから変換
        $getfile = $_FILES['file'];
        if (!$getfile) {
            return;
        }
        $image = [
            'error' => $getfile['error'][0] ?? '',
            'name' => $getfile['name'][0] ?? '',
            'size' => $getfile['size'][0] ?? '',
            'tmp_name' => $getfile['tmp_name'][0] ?? '',
            'type' => $getfile['type'][0] ?? '',
        ];

        //entity
        $data = [
            'image' => $image,
            'title' => $model,
        ];
        $entity = $this->RedactorImages->newEntity($data);
        $save = $this->RedactorImages->save($entity);
        if (!$save) {
            return;
        }

        $array = array(
            'file' => array(
                'url' => '/upload/RedactorImages/images/' . $save->image
            ),
        );
        echo stripslashes(json_encode($array));
        return;
    }

    public function _image_upload($file) {
        pr($file);
    }
}
