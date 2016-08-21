<?php

namespace Mascame\Artificer\Commands;

use Illuminate\Database\Console\Migrations\MigrateCommand;

class MigrateCommandExtension extends MigrateCommand {

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            $options = ['--database' => $this->option('database')];

            // This is the modified line
            $this->call('artificer:migrate:install', $options);
        }
    }

}