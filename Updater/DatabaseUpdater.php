<?php

/**
 * @author Rathes Sachchithananthan <sachchi@rathes.de>
 * @version 1.0.0
 */

namespace fokuscms\Components\Updater;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;


class DatabaseUpdater
{

    protected $connection;

    /**
     * array of migration files
     * @var array
     */
    protected $migrations;

    /**
     * path to migrations
     * @var String
     */
    protected $path;

    /**
     * DatabaseUpdater constructor.
     * @param Capsule $connection
     * @param $path
     */
    public function __construct(Capsule $connection, $path)
    {
        $this->connection = $connection;
        $this->migrations = [];
        $this->path = $path;
        foreach (scandir($this->path) as $file)
        {
            if($file != '.' && $file != '..') $this->migrations[] = explode('.', $file)[0];
        }
    }


    public function migrate()
    {

        # get all migrations that need to run
        $migrations = $this->getMigrationsToRun();

        foreach($migrations as $migration)
        {
            $class_name = $this->resolve($migration);

            include_once $this->path.$migration.'.php';
            $migrationObject = new $class_name($this->connection);

            if (method_exists($this, 'up'))
            {
                $migrationObject->up();
            }
            else
            {
                throw new MethodNotFoundException('Method "up" not found in migration file');
            }

            if(!$this->connection->schema()->hasTable('migrations')) $this->createMigrationsTable();

            // insert migration
            $this->connection->table('migrations')->insert([
                [
                    'migration' => $migration,
                    'timestamp' => time()
                ]
            ]);

        }

    }



    private function createMigrationsTable()
    {

        $this->connection->schema()->create('migrations', function(Blueprint $table){
            $table->increments('id');
            $table->string('migration');
            $table->timestamp('timestamp');
        });

    }



    /**
     * returns the migrations that are not already stored in
     * the database
     *
     * @return array|null
     */
    private function getMigrationsToRun()
    {

        if (empty($this->migrations)) return null;
        if(!$this->connection->schema()->hasTable('migrations')) return $this->migrations;
        return array_diff($this->migrations, $this->connection->table('migrations')->get(['migration']));

    }



    /**
     * @param $migration
     * @return String $class_name
     */
    private function resolve($migration){

        $parts = explode('_', $migration);
        $class_name = str_replace(' ', '', ucwords(implode(' ', array_slice($parts, 1))));
        return $class_name;

    }

}