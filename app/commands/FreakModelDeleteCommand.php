<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FreakModelDeleteCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'freak:delete';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete model.';

    /**
     * Create a new command instance.
     *
     * @return \FreakModelDeleteCommand
     */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$model    = $this->argument('model');

		$app_path = base_path() . '/registers';

		// Remove controller
		@unlink( $app_path . '/controllers/' . ucfirst($model) . 'Controller.php' );

		// Remove views directory
		$this->rrmdir( $app_path . '/views/' . Str::plural(strtolower($model)) ); 
		
		// Remove model
		@unlink( $app_path . '/models/' . $model . '.php' );
	}


	protected function rrmdir($dir)
	{ 
		foreach(glob($dir . '/*') as $file)
		{ 
			if(is_dir($file))

				$this->rrmdir($file); 
			
			else 

				unlink($file); 
		} 
		rmdir($dir); 
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('model', InputArgument::REQUIRED, 'Model name.'),
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
		);
	}

}