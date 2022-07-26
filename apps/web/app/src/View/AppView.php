<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View;

use Cake\View\View;
use App\Model\Entity\Useradmin;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadHelper('Common');
        $this->loadHelper('Html', ['className' => 'MyHtml']);
        $this->loadHelper('Form', [
            'className' => 'MyForm',
            'templates' => [
                'inputContainer' => '{{content}}',
                'inputContainerError' => '{{content}}<div class="error-message">{{error}}</div>',
                'nestingLabel' => '{{input}}<label{{attrs}}>{{text}}</label>',

                'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
                'radioWrapper' => '<li class="contact-form__radio">{{label}}</li>',

                'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
                'checkboxWrapper' => '<p class="contact-form__checkbox">{{label}}</p>',
            ]
        ]);

        // $this->loadHelper('Paginator', ['templates' => 'paginator-templates']);

        $user_roles = [
            'develop' => Useradmin::ROLE_DEVELOP,
            'admin' => Useradmin::ROLE_ADMIN,
            'staff' => Useradmin::ROLE_STAFF
        ];
        $this->set(compact('user_roles'));;
    }
}
