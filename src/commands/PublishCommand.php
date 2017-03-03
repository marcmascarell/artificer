<?php

namespace Mascame\Artificer;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class PublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'artificer:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets and config.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $package = 'mascame/artificer';

        if (file_exists(base_path().'/workbench/mascame/artificer/')) {
            $this->call('config:publish', ['--path' => 'workbench/mascame/artificer/src/config', 'package' => $package]);
            $this->call('asset:publish', ['--bench' => $package]);
        } else {
            $this->call('config:publish', ['package' => $package]);
            $this->call('asset:publish', ['package' => $package]);
        }

        $this->info('Done.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [//			array('example', InputArgument::REQUIRED, 'An example argument.'),
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL,
                'An example option.', null, ],
        ];
    }
}
