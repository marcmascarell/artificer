<?php

namespace Mascame\Artificer\Commands;

use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;

class MigrationCommands
{

    protected $commands = [
        MigrateCommandExtension::class,
        MigrateRefreshCommandExtension::class,
        \Illuminate\Database\Console\Migrations\InstallCommand::class,
        \Illuminate\Database\Console\Migrations\RollbackCommand::class,
        \Illuminate\Database\Console\Migrations\ResetCommand::class,
    ];

    public function __construct(Migrator $migrator, MigrationRepositoryInterface $repository)
    {
        $commands = [];

        foreach ($this->commands as $command) {
            if ($command == InstallCommand::class) {
                $instance = new $command($repository);
            } else {
                $instance = new $command($migrator);
            }

            $instance->setName('artificer:' . $instance->getName());

            $commands[] = $instance;
        }

        $this->registerCommands($commands);
    }

    protected function registerCommands($commands)
    {
        app('events')->listen(\Illuminate\Console\Events\ArtisanStarting::class, function ($event) use ($commands) {
            foreach ($commands as $command) {
                $event->artisan->add($command);
            }
        });
    }
}
