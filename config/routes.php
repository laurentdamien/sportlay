<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\Router;

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
 * If no call is made to `Router::defaultRouteClass`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('Route');

Router::extensions('json');

Router::scope('/', function ($routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Prognostics', 'action' => 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/*', ['controller' => 'Pages', 'action' => 'page404']);
    
    $routes->connect('/prognostic/:id', ['controller' => 'Prognostics', 'action' => 'pronostic'], ['id' => '\d+', 'pass' => ['id']]);
    
    $routes->connect('/ticket/:id/:match', ['controller' => 'Prognostics', 'action' => 'ticket'], ['id' => '\d+', 'match' => '\w+', 'pass' => ['id', 'match']]);
    
    $routes->connect('/cgu', ['controller' => 'Pages', 'action' => 'cgu']);

    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
    
    $routes->connect('/account', ['controller' => 'Users', 'action' => 'account']);
    
    $routes->connect('/admin', ['controller' => 'Users', 'action' => 'admin']);
    
    $routes->connect('/admin/advices', ['controller' => 'Users', 'action' => 'advices']);
    
    $routes->connect('/admin/listMatch', ['controller' => 'Users', 'action' => 'listMatch']);
    
    $routes->connect('/admin/addMatch', ['controller' => 'Users', 'action' => 'addMatch']);
    
    $routes->connect('/admin/addTicket', ['controller' => 'Users', 'action' => 'addTicket']);
    
    $routes->connect('/admin/updateMatch', ['controller' => 'Users', 'action' => 'updateMatch']);
    
    $routes->connect('/admin/updateTicket', ['controller' => 'Users', 'action' => 'updateTicket']);
    
    $routes->connect('/admin/updateAdvice', ['controller' => 'Users', 'action' => 'updateAdvice']);
    
    $routes->connect('/admin/editMatch', ['controller' => 'Users', 'action' => 'editMatch']);
    
    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `InflectedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'InflectedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);`
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
    // $routes->fallbacks('InflectedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
