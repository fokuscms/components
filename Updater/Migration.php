<?php

/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\Updater;


use Illuminate\Database\Capsule\Manager as Capsule;

abstract class Migration
{
    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection;

    /**
     * Migration constructor.
     *
     * @param $connection
     */
    public function __construct(Capsule $connection){
        $this->connection = $connection;
    }

}