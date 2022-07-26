<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\Core\Configure;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */

Router::defaultRouteClass(DashedRoute::class);

Router::extensions(['json']);
Router::scope('/', function (RouteBuilder $routes) {
    $page_slug_exe = '[a-zA-Z0-9_\-]+';
    $page_pattern = array(
        'page_slug' => $page_slug_exe,
        'alphabets' => '[a-zA-Z0-90-9]+',
        'key' => '[a-zA-Z0-9_]+',
        'file_type' => 'file|image',

        'id' => '[1-9]+[0-9]*',
        'slug' => '[1-9a-zA-Z-_]+[0-9a-zA-Z-_]*',
        'schedule_id' => '.*',
        'order' => 'DESC|ASC',
        'is_view' => '0|view'
    );

    $routes->connect('/hash/:alphabets', ['controller' => 'Admin', 'action' => 'hash', 'prefix' => 'admin'])->setPatterns($page_pattern)->setPass(['alphabets']);
    $routes->connect('/view_attaches/:alphabets/:key', ['controller' => 'FileManage', 'action' => 'viewContentFile', 'prefix' => 'v1'])->setPatterns($page_pattern)->setPass(['alphabets', 'key']);

    //ファイル出力
    $routes->connect('/file/:alphabets/:id/:file_type', ['controller' => 'FileManage', 'action' => 'manageAttaches', 'prefix' => 'v1'])->setPatterns($page_pattern)->setPass(['alphabets', 'id', 'file_type']);
    $routes->connect('/file/:alphabets/:id/:file_type/:is_view', ['controller' => 'FileManage', 'action' => 'manageAttaches', 'prefix' => 'v1'])->setPatterns($page_pattern)->setPass(['alphabets', 'id', 'file_type', 'is_view']);

    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Items', 'action' => 'index']);

    $routes->connect('/admin', ['controller' => 'Admin', 'action' => 'index', 'prefix' => 'admin']);
    $routes->connect('/admin/logout', ['controller' => 'Admin', 'action' => 'logout', 'prefix' => 'admin']);

    //IDがあればactionより先に
    $routes->connect('/:controller/:id', ['action' => 'detail'])->setPatterns($page_pattern)->setPass(['id']);
    //actionがなかったらslugとして扱う
    // $routes->connect('/guide/:slug', ['controller' => 'Guide', 'action' => 'detail'])->setPatterns($page_pattern)->setPass(['slug']);

    $routes->connect('/:controller/:action');

    // API
    $routes->connect('/:site_slug/v1/:controller/:action', ['prefix' => 'v1'])
        ->setPass(['site_slug']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);


    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('admin', function ($routes) {
    // $routes->connect('/', ['plugin' => 'Admin','controller' => 'admin', 'action' => 'index']);
    // $routes->connect('/', ['controller' => 'admin', 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});

Router::prefix('userAdmin', function ($routes) {
    // user
    $routes->connect('/', ['controller' => 'Home', 'action' => 'index', 'prefix' => 'userAdmin'], ['_name' => 'userTop']);
    $routes->connect('/logout', ['controller' => 'Home', 'action' => 'logout', 'prefix' => 'userAdmin'], ['_name' => 'logout']);
    $routes->connect('/login', ['controller' => 'Home', 'action' => 'login', 'prefix' => 'userAdmin'], ['_name' => 'login']);

    $routes->fallbacks('DashedRoute');
});

Router::prefix('v1', function ($routes) {
    // $routes->connect('/v1/:site_slug/:controller', )

    $routes->fallbacks('DashedRoute');
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
