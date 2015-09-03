<?php
/*
 * Rathes Sachchithananthan
 * 01/26, 2015
 *
 * This class is the translator class which does the translating
 * of content. This can be used in the backend as well as in the frontend
 * of fokus
 */

namespace fokuscms\Components\Language;

class Language {

    /*
     * path of the language files,
     * expected with trailing slash
     */
    private $location;

    public function __construct($location){
        $this->location = $location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location){
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation(){
        return $this->location;
    }

    /**
     *
     * createLanguageArray()
     * creates a big language array using all files
     * of one directory
     *
     * @param $dir
     * @return array
     */
    private function createLanguageArray($dir){

        // empty array to fill with content of the files
        $langArray = array();

        // load all files in this directory
        $langFiles = scandir($dir);
        foreach ($langFiles as $file) {
            if ($file != "." && $file != "..") {
                $singleLangArray = include $dir.'/'.$file;
                $langArray = array_merge($langArray, $singleLangArray);
            }
        }

        return $langArray;
    }

    /**
     *
     * translate()
     * returns a translated version of $input into
     * language $locale
     *
     * @param $input
     * @param $locale
     * @return array
     */
    public function translate($input, $locale){

        // retrieve all language file
        $langArray = $this->createLanguageArray($this->location.$locale);

        // find lang data matching the given input
        if(isset($langArray[$input])){
            return $langArray[$input];
        } else {
            $keys = explode('.', $input);
            $aLangArray = &$langArray;
            while(count($keys) > 0){
                $k = array_shift($keys);
                if (!is_array($aLangArray)){
                    $aLangArray = array();
                }

                $aLangArray = &$aLangArray[$k];
            }
            if(isset($aLangArray)){
                return$aLangArray;
            }
        }
        return $input;


    }

} 