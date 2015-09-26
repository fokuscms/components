<?php

/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\View;

use fokuscms\Components\Foundation\Application;
use fokuscms\Components\View\Engine\PhpEngine;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

class View {

    private $engine = null;
    private $engineName = null;
    private $path = null;
    private $file = null;
    private $parameter = [];
    private $response;

    /**
     * @param $file
     * @param $parameter
     * @param $path
     * @param $engineName
     */
    public function __construct($file, $parameter, $path, $engineName){

        # get engine from config
        $this->engine = Application::config('engine');

        # set engineName
        if ($engineName == null){
            $this->engineName = Application::config('engine');
        } else {
            $this->engineName = $engineName;
        }

        # set file
        if ($path == null){
            $this->path = Application::getBasePath().Application::config('view_path');
        } else {
            $this->path = $path;
        }

        # set file and parameter
        $this->file = $file;
        $this->parameter = $parameter;


    }

    /**
     * @return Response
     */
    public function render(){

        if ($this->engine == 'php'){

            $engine = new PhpEngine($this->path);
            $engine->load($this->file, $this->parameter);
            $this->response = $engine->render();

        }

        return new Response($this->response);

    }

}

?>