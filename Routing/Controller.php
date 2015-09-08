<?php
/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\Routing;

use fokuscms\Components\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

class Controller {

    public function __construct(){

    }

    /**
     *
     * returns a Response using the $view rendered by the $engine with
     * the name $engineName
     *
     * @param $file
     * @param $parameter
     * @param null $path
     * @param $engineName
     * @return Response
     */
    public function render($file, $parameter, $path = null, $engineName = null){

        $view = new View($file, $parameter, $path, $engineName);
        return $view->render();

    }

}

?>