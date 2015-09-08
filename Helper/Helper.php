<?php

/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing;

class Helper {

    /**
     *
     * createRedirectResponse()
     * creates a redirection response
     * for a given route
     *
     * @param $route
     * @param $routes
     * @return RedirectResponse
     */
    public static function createRedirectResponse($route, $routes){
        $request = Request::createFromGlobals();
        $context = new Routing\RequestContext();
        $context->fromRequest($request);

        $generator = new Routing\Generator\UrlGenerator($routes, $context);
        return new RedirectResponse($generator->generate($route));
    }

    /**
     *
     * route()
     * generates the link to a given route
     *
     * @param $route
     * @param array $parameter
     * @param $routes
     * @return string
     */
    public static function route($route, $parameter = array(), $routes){
        $request = Request::createFromGlobals();
        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        $generator = new Routing\Generator\UrlGenerator($routes, $context);
        return $generator->generate($route, $parameter);
    }

}