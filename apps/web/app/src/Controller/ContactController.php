<?php

namespace App\Controller;

use Cake\Event\Event;
use App\Form\ContactForm;

class ContactController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->setHeadTitle('お問い合わせ | 株式会社BLOCKSMITH&Co.');
    }

    public function index()
    {

        $this->viewBuilder()->setLayout('default');
        $contact = new ContactForm();

        $view = 'index';
        $data = null;

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $contact->validate($this->request->getData());
            if (empty($contact->getErrors())) {
                $is_confirm_success = isset($data['is_confirm_success']) && intval($data['is_confirm_success']) == 1;
                if ($is_confirm_success == false) {
                    $view = 'confirm';
                } else {
                    $contact->execute($data);
                    $this->redirect(['action' => 'complete']);
                }
            } else {
                $this->set('error', $contact->getErrors());
            }
        }
        $this->set('list_cat',  $contact->list_cat);
        $this->set(compact('contact', 'data'));
        $this->render($view);
    }

    public function complete()
    { }
}
