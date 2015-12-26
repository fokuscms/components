<?php

/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\Updater;


use Illuminate\Database\Capsule\Manager as Capsule;

class Updater
{

    private $src;
    private $dest;

    private $connection;

    /**
     * Updater constructor
     *
     * @param $uri
     * @param $to
     * @param Capsule $connection
     * @param bool|true $zip
     * @throws \Exception
     */
    public function __construct($uri, $to, Capsule $connection, $zip = true) {

        $this->src = $uri;
        $filename = basename($uri);

        $this->connection = $connection;



        // download file from uri
        file_put_contents($to.$filename, fopen($uri, 'r'));

        // destination directory
        $this->dest = $to.time().'_'.$filename;

        // create new directory with timestamp prefix
        if(mkdir($this->dest)){

            if($zip === true){

                // unzip to this dir
                $zip = new \ZipArchive();
                $res = $zip->open($to.$filename);

                // extract to destination directory
                if($res === true){

                    $zip->extractTo($this->dest);
                    $zip->close();

                    // remove zip
                    unlink($to.$filename);

                } else {
                    throw new \Exception('Failed to open zip');
                }

            } else {

                // file not a zip, just move to new directory
                if(!rename($to.$filename, $this->dest.$filename)){
                    throw new \Exception('Could not move file to destination.');
                }

            }

        }

    }

    /**
     * replaces a file with the existing one on the given path
     *
     * @param $filename
     * @param $path
     * @param bool|true $safe
     * @throws \Exception
     */
    public function updateFile($filename, $path, $safe = true){

        // rename existing file
        if($safe === true && file_exists($path.$filename)) {
            rename($path.$filename, $path.$filename.'.bak');
        }

        if(!file_exists($this->dest.$filename)) throw new \Exception('File "'.$filename.'" does not exist');

        // copy file
        if(copy($this->dest.$filename, $path.$filename) && $safe === true && file_exists($path.$filename)){
            unlink($path.$filename.'.bak');
        };

    }

    /**
     * @param $migration__file
     * @param $migration__className
     */
    public function updateDatabase($migration__file, $migration__className)
    {
        // @Todo rewrite the update mechanism
    }

}