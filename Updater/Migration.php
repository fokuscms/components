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

    protected $name;

    /**
     * Migration constructor.
     *
     * @param $connection
     */
    public function __construct(Capsule $connection){

        $this->connection = $connection;
        $this->name = time();

    }

    /**
     * Get the migration connection name.
     *
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param $name
     */
    public function setName($name){
        $this->name = $name;
    }

    /**
     * @throws \Exception
     */
    public function migrate(){

        if (method_exists($this, 'up')){

            $this->up();

            // create migrations table if it does not exist already
            if(!$this->connection->schema()->hasTable('migrations')){

                $this->connection->schema()->create('migrations', function($table){
                    $table->increments('id');
                    $table->string('migration');
                    $table->timestamps();
                });

            }

            // insert migration
            $this->connection->table('migrations')->insert([
                ['migration' => $this->name]
            ]);

        } else {
            throw new \Exception('Migration method not defined');
        }

    }

    /**
     * @throws \Exception
     */
    public function rollback(){

        if (method_exists($this, 'down')){

            $this->down();
            $this->connection->table('migrations')->where('migration', '=', $this->name)->delete();

        } else {
            throw new \Exception('Rollback method not defined');
        }

    }

}