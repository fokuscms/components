<?php
/**
 *
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 *
 * This class should simplify the way Routes are
 * added to the RouteCollection
 *
 */

namespace fokuscms\Components\Routing;


use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class Router {

    private $collection;

    # path to controller
    private $prefix;

    /**
     * @param $path
     */
    public function __construct($path = "App\\Http\\Controllers\\"){
        $this->collection = new RouteCollection();
        $this->prefix = $path;
    }

    /**
     * @return mixed
     */
    public function getCollection(){
        return $this->collection;
    }

    /**
     *
     * addRoute()
     * adds a route to the route collection
     *
     * @param $route
     * @param $action
     * @param $method
     * @param null $data
     * @return bool
     */
    public function addRoute($route, $action, $method, $data = null){

        $actionPrefix = $this->prefix;

         # If $data['name'] is empty then a name is generated
         # automatically using the method name
        if (!$data['name'] || empty($data['name']) ){
            $f = explode('::', $action);
            $data['name'] = $f[1];
        }

        $action = $actionPrefix.$action;

        $controller = array('_controller' => $action);
        if(isset($data['defaults'])){
            $defaults = array_merge($controller, $data['defaults']);
        } else {
            $defaults = $controller;
        }

        if (isset($data['req'])){
            $requirements = $data['req'];
        } else {
            $requirements = array();
        }

        # check if route is null then any is called
        if($method === null){
            $this->collection->add($data['name'], new Route($route, $defaults ));
            return true;
        } else {
            $this->collection->add($data['name'], new Route($route, $defaults,
                $requirements, array(), '', array(), array($method)));
            return true;
        }
    }

    /**
     *
     * get()
     * adds a get-Route to the collection
     *
     * @param $route
     * @param $action
     * @param null $data
     */
    public function get($route, $action, $data = null){
        $this->addRoute($route, $action, 'GET', $data);
    }

    /**
     *
     * post()
     * adds a post-route to the collection
     *
     * @param $route
     * @param $action
     * @param null $data
     */
    public function post($route, $action, $data = null){
        $this->addRoute($route, $action, 'POST', $data);
    }

    /**
     *
     * put()
     * adds a put-route to the collection
     *
     * @param $route
     * @param $action
     * @param null $data
     */
    public function put($route, $action, $data = null){
        $this->addRoute($route, $action, 'PUT', $data);
    }

    /**
     *
     * delete()
     * adds a delete-route to the collection
     *
     * @param $route
     * @param $action
     * @param null $data
     */
    public function delete($route, $action, $data = null){
        $this->addRoute($route, $action, 'DELETE', $data);
    }

    /**
     *
     * any()
     * adds a route to the collection that has no definition
     * of the methods but matches to all types of requests
     *
     * @param $route
     * @param $action
     * @param null $data
     */
    public function any($route, $action, $data = null){
        $this->addRoute($route, $action, null, $data);
    }

    /**
     *
     * api()
     * adds the following routes for following actions
     * GET index()
     * GET show()
     * POST store()
     * PUT update()
     * DELETE destroy()
     *
     * The route is expected to end with the name of the
     * resource
     *
     * @param $route
     * @param $controller
     */
    public function api($route, $controller){

        $routeName = array();
        $routeController = array();
        $method = 'GET';
        $routeParts = explode('/', $route);
        $resourceName = end($routeParts);
        $actions = array('index', 'show', 'store', 'update', 'destroy');
        $realRoute = $route;
        foreach($actions as $action){
            $helpAction = ucfirst($action);
            $resName = ucfirst($resourceName);
            $routeName[$action] = 'api'.$helpAction.$resName;
            $routeController[$action] = $controller.'::'.$action;
            if($action == 'index'){
                $method = 'GET';
                $route = $realRoute;
            } elseif ($action == 'show'){
                $method = 'GET';
                $route = $realRoute.'/{'.$resourceName.'}';
            } elseif ($action == 'store'){
                $method = 'POST';
                $route = $realRoute;
            } elseif ($action == 'update'){
                $method = 'PUT';
                $route = $realRoute.'/{'.$resourceName.'}';
            } elseif ($action == 'destroy'){
                $method = 'DELETE';
                $route = $realRoute.'/{'.$resourceName.'}';
            }
            $this->addRoute($route, $routeController[$action], $method, array('name' => $routeName[$action]));
        }
    }

} 