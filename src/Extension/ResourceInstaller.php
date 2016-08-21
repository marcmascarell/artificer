<?php namespace Mascame\Artificer\Extension;

use Illuminate\Database\Migrations\Migrator;

class ResourceInstaller extends \Illuminate\Support\ServiceProvider {

    /**
     * @var AbstractExtension
     */
    protected $extension;

    protected $collectedCalls;

    protected $published = false;

    protected $migrated = false;

    public function __construct(\Illuminate\Contracts\Foundation\Application $app, $extension)
    {
        parent::__construct($app);

        $this->extension = $extension;

        $this->collectedCalls = $this->getCollectedCalls();

        foreach ($this->getCollectedCalls() as $call) {
            call_user_func_array(array($this, $call['method']), $call['args']);
        }

    }

    protected function getCollectedCalls() {
        $collectedCalls = $this->extension->resources->getCollected();
        $collectedExtensionCalls = [];

        if (isset($collectedCalls[$this->extension->namespace])) {
            $collectedExtensionCalls = $collectedCalls[$this->extension->namespace];
        }

        return $collectedExtensionCalls;
    }

    public function install() {
        $this->toggleResources('handle');
    }

    public function uninstall() {
        $this->toggleResources('revert');
    }

    protected function toggleResources($action) {
        foreach ($this->getCollectedCalls() as $call) {
            $handlerMethod = $action . ucfirst($call['method']);

            if (method_exists($this, $handlerMethod)) {
                $this->{$handlerMethod}($call['args']);
            }
        }
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
    protected function revertPublishes() {

    }

    // Remove migrations from that step
    // todo: delete by name not batch
    protected function revertLoadMigrationsFrom($args) {
        $migrations = $result = \DB::table('artificer_migrations')->get();
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
//            \Artisan::call('migrate:rollback', ['--step' => $step]);
        }
    }

    protected function rollbackBatch($paths, $batch) {
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

                $migrations = $this->repository->getConnection()->table('artificer_migrations')->where('batch', $batch)->get();

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

    protected function getFileNamesFromPath($path) {
        return array_map(function($value) {
            return str_replace('.php', '', $value);
        }, array_diff(scandir(base_path($path)), ['..', '.', '.gitkeep']));
    }

    protected function handlePublishes() {
        if ($this->published) return;

        \Artisan::call('vendor:publish', ['--provider' => $this->extension->namespace]);

        $this->published = true;
    }

    protected function handleLoadMigrationsFrom($args) {
        foreach ($this->getNormalizedPaths($args) as $path) {
            \Artisan::call('artificer:migrate', ['--path' => $path]);
        }
    }

    protected function getNormalizedPaths($args) {
        list($paths) = $args;
        $paths = (array) $paths;

        foreach ($paths as &$path) {
            // migrator will prepend base_path
            if (str_contains($path, base_path() . '/')) {
                $path = str_replace(base_path() . '/', '', $path);
            }
        }

        return $paths;
    }
}