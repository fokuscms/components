<?php
/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\View\Engine;


use fokuscms\Components\Helper\Helper;

class PhpEngine {

    # directory for templates
    private $templateDir = '';

    # path to template file
    private $templateFile = "";

    # name of template file
    private $templateName = "";

    # content of template
    private $template = "";

    # parameter
    private $parameter = [];


    /**
     * @param $path
     */
    public function __construct($path) {
        $this->templateDir = $path;
    }

    /**
     * @param $file
     * @return bool
     */
    public function load($file, $parameter){

        # the ":" marks a new directory within view directory
        $fileName = '';
        $fileNameParts = explode(":", $file);
        foreach($fileNameParts as $fileNamePart){
            $fileName .= DIRECTORY_SEPARATOR.$fileNamePart;
        }

        $this->templateFile = $this->templateDir.$fileName;
        $this->templateName = end($fileNameParts);

        $this->parameter = $parameter;

        return true;

    }

    /**
     * @return string
     */
    public function render(){

        ob_start();
        extract($this->parameter, EXTR_SKIP);
        include $this->templateFile;
        $template = ob_get_contents();
        ob_end_clean();
        return $template;

    }

}