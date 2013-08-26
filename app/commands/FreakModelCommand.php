<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FreakModelCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'freak:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new model in the given application folder.';


	protected $model;

    /**
     * Create a new command instance.
     *
     * @return \FreakModelCommand
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
		$this->model = $this->argument('model');

		$app_path = base_path() . '/freak/';

		if(! file_exists($app_path))
		{
			$this->error('Application not created yet');

		} else {

			$tmp_path = __DIR__ . '/templates/freak';

			// Create controller for this model
			$this->copyTemplate( 
				$app_path . '/controllers/' . ucfirst($this->model) . 'Controller.php',
				$tmp_path . '/controller.php'
			);

			// Create views folder for this model
			$views_path = $app_path . '/views/' . Str::plural(strtolower($this->model));
			if(! file_exists($views_path)) mkdir( $views_path );
			
			// Create add, data and detail view
			$this->copyTemplate( $views_path . '/add.blade.php', $tmp_path   . '/views_add.php');
			$this->copyTemplate( $views_path . '/data.blade.php', $tmp_path   . '/views_data.php');
			$this->copyTemplate( $views_path . '/detail.blade.php', $tmp_path   . '/views_detail.php');

			// Create model file
			$this->copyTemplate( 
				$app_path . '/models/' . $this->model . '.php', 
				$tmp_path  . '/model.php'
			);
		}
	}

	protected function copyTemplate( $dst_file, $src_file )
	{
		$model = $this->model;

		$data = require $src_file;

		file_put_contents( $dst_file, $data);
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