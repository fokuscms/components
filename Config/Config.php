<?php
/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\Config;

class Config {

    private $path;
    private $data = [];

    /**
     * @param $path
     */
    public function __construct($path){

        $this->path = $path;
        $this->fillData();

    }

    /**
     * goes through every file in $path and creates a big
     * data array
     */
    private function fillData(){

        // load all files in this directory
        $configFiles = scandir($this->path);
        foreach ($configFiles as $file) {
            if ($file != "." && $file != "..") {
                $singleConfigArray = include $this->path.'/'.$file;
                $this->data = array_merge($this->data, $singleConfigArray);
            }
        }

    }

    /**
     * searches in $data for the given key and returns
     * the partial of $data starting at $key
     * returns null if $key is not found
     *
     * @param $key
     * @return null
     */
    public function get($key, $data = null){

        if ($data = null)
            $data = $this->data;

        if(isset($data[$key])){
            return $data[$key];
        } else {

            foreach($data as $subArray){
                if(is_array($subArray))
                    $this->get($key, $subArray);
            }

        }

        return null;

    }

}