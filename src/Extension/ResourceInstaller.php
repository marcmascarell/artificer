<?php

namespace Mascame\Artificer\Extension;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;

class ResourceInstaller extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var AbstractExtension
     */
    protected $extension;

    protected $collectedCalls;

    protected $published = false;

    protected $migrated = false;

    /**
     * Methods that will run at the end, with the loadDefered method.
     *
     * @var array
     */
    protected $defered = [
        'mergeConfigFrom',
        'mergeRecursiveConfigFrom',
    ];

    public function __construct(\Illuminate\Contracts\Foundation\Application $app, $extension)
    {
        parent::__construct($app);

        $this->extension = $extension;

        foreach ($this->getCollectedCalls() as $call) {
            if ($this->isDefered($call['method'])) {
                continue;
            }

            call_user_func_array([$this, $call['method']], $call['args']);
        }
    }

    public function loadDefered()
    {
        foreach ($this->getCollectedCalls() as $call) {
            if (! $this->isDefered($call['method'])) {
                continue;
            }

            call_user_func_array([$this, $call['method']], $call['args']);
        }
    }

    private function isDefered($method)
    {
        return in_array($method, $this->defered);
    }

    /**
     * Get the collected resources from the extension instance.
     *
     * @return array
     */
    protected function getCollectedCalls()
    {
        $collectedCalls = $this->extension->resources->getCollected();
        $collectedExtensionCalls = [];

        if (isset($collectedCalls[$this->extension->namespace])) {
            $collectedExtensionCalls = $collectedCalls[$this->extension->namespace];
        }

        return $collectedExtensionCalls;
    }

    /**
     * We will call handle{Method} when an extension is installed.
     */
    public function install()
    {
        $this->toggleResources('handle');
    }

    /**
     * We will call revert{Method} when an extension is uninstalled.
     */
    public function uninstall()
    {
        $this->toggleResources('revert');
    }

    protected function toggleResources($action)
    {
        foreach ($this->getCollectedCalls() as $call) {
            $handlerMethod = $action.ucfirst($call['method']);

            if (method_exists($this, $handlerMethod)) {
                $this->{$handlerMethod}($call['args']);
            }
        }
    }

    /**
     * Will look the migrations in $args and find their batch @ artificer_migrations table,
     * then we will remove all migrations from that batch (because it is one batch per extension).
     *
     * @param $args
     */
    protected function revertLoadMigrationsFrom($args)
    {
        $migrations = $result = \DB::table(config('admin.migrations'))->get();
        $migrations = $migrations->pluck('batch', 'migration')->toArray();
        $batchToRollback = [];

        foreach ($this->getNormalizedPaths($args) as $path) {
            foreach ($this->getFileNamesFromPath($path) as $fileName) {
                if (array_key_exists($fileName, $migrations)) {
                    $batch = $migrations[$fileName];

                    $batchToRollback[$batch][] = base_path($path);

                    break;
                }
            }
        }

        foreach ($batchToRollback as $batch => $paths) {
            $this->rollbackBatch($paths, $batch);
        }
    }

    protected function getFileNamesFromPath($path)
    {
        return array_map(function ($value) {
            return str_replace('.php', '', $value);
        }, array_diff(scandir(base_path($path)), ['..', '.', '.gitkeep']));
    }

    protected function handlePublishes()
    {
        if ($this->published) {
            return;
        }

        $pathsToPublish = ServiceProvider::pathsToPublish(
            $this->extension->namespace
        );

        $this->ensurePathsExistence(array_keys($pathsToPublish));

        \Artisan::call('vendor:publish', ['--provider' => $this->extension->namespace]);

        // Ensure the paths exist
        $this->waitUntilPathsExist(array_values($pathsToPublish));

        $this->published = true;
    }

    protected function ensurePathsExistence($paths)
    {
        foreach ($paths as $path) {
            if (! \File::exists($path)) {
                throw new \Exception('Origin path to publish not found, please ensure the path is correct');
            }
        }
    }

    protected function waitUntilPathsExist($paths, $retries = 5, $checkInterval = 2)
    {
        foreach ($paths as $path) {
            $retry = 0;

            while (! file_exists($path)) {
                if ($retry >= $retries) {
                    throw new \Exception('The path "'.$path.'" which we expected to see did not exist for the time we waited.');
                }

                sleep($checkInterval);
            }
        }
    }

    protected function handleLoadMigrationsFrom($args)
    {
        foreach ($this->getNormalizedPaths($args) as $path) {
            \Artisan::call('artificer:migrate', ['--path' => $path]);
        }
    }

    protected function getNormalizedPaths($args)
    {
        list($paths) = $args;
        $paths = (array) $paths;

        foreach ($paths as &$path) {
            // migrator will prepend base_path
            if (str_contains($path, base_path().'/')) {
                $path = str_replace(base_path().'/', '', $path);
            }
        }

        return $paths;
    }

    /**
     * Register a database migration path.
     *
     * @param  array|string  $paths
     * @return void
     */
    protected function loadMigrationsFrom($paths)
    {
        $migrator = app('ArtificerMigrator');

        foreach ((array) $paths as $path) {
            $migrator->path($path);
        }
    }

    protected function rollbackBatch($paths, $batch)
    {
        $migrator = new class(app('ArtificerMigrationRepository'), app('db'), app('files')) extends Migrator {
            /**
             * Rollback the given batch.
             *
             * @param $batch
             * @return array
             */
            public function rollbackBatch($paths, $batch)
            {
                $this->notes = [];

                $rolledBack = [];

                $migrations = $this->repository->getConnection()->table(config('admin.migrations'))->where('batch', $batch)->get();

                $count = count($migrations);

                $files = $this->getMigrationFiles($paths);

                if ($count === 0) {
                    $this->note('<info>Nothing to rollback.</info>');
                } else {
                    // Next we will run through all of the migrations and call the "down" method
                    // which will reverse each migration in order. This getLast method on the
                    // repository already returns these migration's names in reverse order.
                    $this->requireFiles($files);

                    foreach ($migrations as $migration) {
                        $rolledBack[] = $files[$migration->migration];

                        $this->runDown(
                            $files[$migration->migration],
                            (object) $migration, $pretend = false
                        );
                    }
                }

                return $rolledBack;
            }
        };

        $migrator->rollbackBatch($paths, $batch);
    }

    protected function publishes(array $paths, $group = null)
    {
        // We need to override this line
        $class = $this->extension->namespace;

        if (! array_key_exists($class, static::$publishes)) {
            static::$publishes[$class] = [];
        }

        static::$publishes[$class] = array_merge(static::$publishes[$class], $paths);

        if ($group) {
            if (! array_key_exists($group, static::$publishGroups)) {
                static::$publishGroups[$group] = [];
            }

            static::$publishGroups[$group] = array_merge(static::$publishGroups[$group], $paths);
        }
    }

    // Remove files
    protected function revertPublishes()
    {
        $class = $this->extension->namespace;

        foreach (static::$publishes[$class] as $publisedPath) {
            \File::deleteDirectory($publisedPath);
        }
    }

    protected function mergeRecursiveConfigFrom($path, $key)
    {
        config([
            $key => array_replace_recursive(config($key, []), require $path),
        ]);
    }
}
