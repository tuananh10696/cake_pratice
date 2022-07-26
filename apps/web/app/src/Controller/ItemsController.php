<?php

namespace App\Controller;

use App\Controller\AppController;

class ItemsController extends AppController
{
    public function index()
    {
        $this->viewBuilder()->layout('simple');
    }
}