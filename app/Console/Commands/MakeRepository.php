<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:repository {name} {--P|path=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make repository, skip model, skip migrate';

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
    public function handle()
    {
        if ($this->option('path') != "default") {
            $path = $this->option('path');
            $nameSpace = str_replace('/', '\\', $path);
            config(['repository.generator.rootNamespace'     => config('repository.namespace').'\\'.$nameSpace]);
            config(['repository..generator.basePath' => config('repository.generator.basePath').'/'.$path]);
        }
        //
        $name = $this->argument('name');
        $makeRepositoryParams = [
            'name' => $name,
            '--skip-migration' => 'default',
            '--skip-model' => 'default',
        ];

        $makeModel = $this->confirm('Would you like to create a Model? [y|N]');

        if ($makeModel) {
            //check entities exist
            $entityPath = app_path('Entities/' . $name . '.php');
            if (!\File::exists($entityPath)) {
                unset($makeRepositoryParams['--skip-migration']);
                unset($makeRepositoryParams['--skip-model']);
            }
        }

        $this->call('make:repository', $makeRepositoryParams);

        $this->call('make:bindings', [
            'name' => $name
        ]);

        if ($makeModel) {
            $this->warn('Move model to app/Entities');
            $this->call('move:entities', ['path' => $path]);
        }
    }
}
