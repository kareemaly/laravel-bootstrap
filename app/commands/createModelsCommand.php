<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class createModelsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'model:many';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create models command.';

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
	 * @return void
	 */
	public function fire()
	{
        $models = explode(',', $this->argument('models'));

        if($folder = $this->option('folder'))
        {
            $namespace1 = str_replace('/', '\\', $folder) . '\\';
            $folder = app_path() . '/models/' . trim($folder, '\\/');
        }
        else
        {
            $folder = app_path() . '/models';
            $namespace1 = '';
        }

        if(! file_exists($folder)) mkdir($folder);

        foreach ($models as $model)
        {
            if(strpos($model, '_') > 0) {
                $array = explode('_', $model);

                $model = '';
                foreach ($array as $value) {
                    $model .= ucfirst($value);
                }
            }

            $namespace = $namespace1 . ucfirst($model);
            $class = ucfirst($model);
            $table = Str::plural($model);

            $dir  = $folder . '/' . ucfirst($model);
            $testsDir = app_path() . '/tests/Models';
            $file = $dir . '/' . $class . '.php';

            if(! file_exists($dir)) mkdir( $dir );

            if(! file_exists($file)) {

                $data = require __DIR__ . '/templates/test.php';
                file_put_contents( $testsDir . '/' . $class . 'Test.php', $data);

                $data = require __DIR__ . '/templates/model.php';
                file_put_contents( $dir . '/' . $class . '.php', $data);

                $data = require __DIR__ . '/templates/algorithm.php';
                file_put_contents( $dir . '/' . $class . 'Algorithm.php', $data);
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
        return array(
            array('models', InputArgument::REQUIRED, 'Models to be created.'),
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
            array('folder', null, InputOption::VALUE_OPTIONAL, 'Folder for this models.', false)
        );
	}

}