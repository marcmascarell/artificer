<?php


namespace Mascame\Artificer;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PublishCommand extends Command {

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
//		$this->call('config:publish', array('package' => 'mascame/artificer'));

//		php artisan config:publish --path="workbench/foo/bar/src/config" foo/bar
		if (file_exists(base_path() .'/workbench/mascame/artificer/src/config/')) {
			$this->call('config:publish', array('--path' => "workbench/mascame/artificer/src/config", 'package' => $package));
		} else {
			$this->call('config:publish', array('package' => 'mascame/artificer'));
		}

		$this->call('asset:publish', array('--bench' => $package));

		$this->info("Done.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL,
				'An example option.', null)
		);
	}
}
?>