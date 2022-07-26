<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AdminController extends AppController {
    public function beforeFilter(Event $event) {
        // $this->viewBuilder()->theme('Admin');
        $this->viewBuilder()->setLayout('admin');
    }

    public function index() {
        $this->Admin = $this->getTableLocator()->get('Admins');

        $this->viewBuilder()->setLayout('plain');
        $view = 'login';
        $r = array();
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            if (!empty($data['username']) && !empty($data['password'])) {
                $query = $this->Admin->find('all', array('conditions' => array('username' => $data['username'],
                ),
                    'limit' => 1));
                $r = $query->first();

                if ($r) {
                    $hasher = new DefaultPasswordHasher();
                    if (!$hasher->check($data['password'], $r->password)) {
                        $r = false;
                    }
                }

                if ($r) {
                    $this->Session->write(array('uid' => $r->id,
                        'role' => $r->role));
                }
            }
            if (empty($r)) {
                $this->Flash->set('アカウント名またはパスワードが違います');
            }
        }
        if (0 < $this->Session->read('uid')) {
            $this->viewBuilder()->setLayout('admin');
            $view = 'index';
        }
        $this->render($view);
    }

    public function logout() {
        if (0 < $this->Session->read('uid')) {
            $this->Session->delete('uid');
            $this->Session->delete('role');
            $this->Session->destroy();
        }
        $this->redirect('/admin/');
    }
}
