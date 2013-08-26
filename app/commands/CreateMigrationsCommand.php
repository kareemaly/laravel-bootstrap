<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateMigrationsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate:many';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create many migrations.';

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
        $tables = explode(',', $this->argument('tables'));

        foreach($tables as $table)
        {
            $migrationName = 'create_' . $table . '_table';

            $this->call('migrate:make', array('name' => $migrationName, '--table' => $table, '--create' => true));
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
			array('tables', InputArgument::REQUIRED, 'All tables separated by comma.'),
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