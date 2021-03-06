<?php
/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use fokuscms\Components\Foundation\Application as App;

if (!function_exists('get_base_path')){
    /**
     * return the base_path of the request
     *
     * @return string
     */
    function get_base_path(){
        return App::getBasePath();
    }
}

if(!function_exists('get_base_url')){
    /**
     * @return mixed
     */
    function get_base_url(){
        return App::getBaseUrl();
    }
}

if (!function_exists('resource')){
    /**
     * @param string $str
     * @param string $path
     * @return string
     */
    function resource($str = '', $path = '/content'){
        return App::getBaseUrl().$path.$str;
    }
}


if (!function_exists('router')){
    /**
     * @param $route
     * @param array $param
     * @param string $routes
     * @return string
     */
    function router($route, $param = [], $routes = ''){
        $request = Request::createFromGlobals();
        if($routes == ''){
            $routes = include App::getBasePath().'/system/app/Http/routes.php';
        }

        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        $generator = new Routing\Generator\UrlGenerator($routes, $context);
        return $generator->generate($route, $param);
    }
}

if (!function_exists('lang')){
    /**
     * translate the given input
     *
     * @param $input
     * @return array
     */
    function lang($input){
        $language = App::getLanguage();
        if($language == '' || $language == null){
            $language = App::getFallbackLanguage();
        }
        $lang = new \fokuscms\Components\Language\Language( App::getBasePath().'/content/lang/');
        return $lang->translate($input, $language);
    }
}