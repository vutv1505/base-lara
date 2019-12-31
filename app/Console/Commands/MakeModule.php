<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeModule extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'new:module {name} {--P|path=default}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new module';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		//
		if ($this->option('path') != "default") {
			$path = $this->option('path');
            $nameSpace = str_replace('/', '\\', $path);
			config(['modules.namespace'     => config('modules.namespace').'\\'.$nameSpace]);
			config(['modules.paths.modules' => config('modules.paths.modules').'/'.$path]);
		}
		$name = $this->argument('name');
		if (empty($name)) {
			$this->error('Please input module name');
			return;
		}
		$this->info('----- begin create module ['.$name.'] --------');
		$this->call('module:make', [
				'name' => [$name],
			]);

		$this->warn('----- make RepositoryServiceProvider for module ['.$name.'] --------');
		$this->call('module:make-provider', [
				'name'   => 'RepositoryServiceProvider',
				'module' => $name
			]);

		$this->info('----- end create module ['.$name.'] --------');
	}

}
