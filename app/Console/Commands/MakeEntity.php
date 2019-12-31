<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'new:entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command create new Entity';


    /**
     * @var Collection
     */
    protected $generators = null;

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
     * Execute the command.
     *
     * @see fire()
     * @return void
     */
    public function handle(){
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {

        if ($this->confirm('Would you like to create a Presenter? [y|N]')) {
            $this->call('make:presenter', [
                'name'    => $this->argument('name'),
                '--force' => $this->option('force'),
            ]);
        }

        $validator = $this->option('validator');
        if (is_null($validator) && $this->confirm('Would you like to create a Validator? [y|N]')) {
            $validator = 'yes';
        }

        if ($validator == 'yes') {
            $this->call('make:validator', [
                'name'    => $this->argument('name'),
                '--rules' => $this->option('rules'),
                '--force' => $this->option('force'),
            ]);
        }

        if ($this->confirm('Would you like to create a Controller? [y|N]')) {

            $resource_args = [
                'name'    => $this->argument('name')
            ];

            // Generate a controller resource
            $controller_command = ((float) app()->version() >= 5.5  ? 'make:rest-controller' : 'make:resource');
            $this->call($controller_command, $resource_args);
        }

        $path = app_path();
        if (!empty($this->option('path'))) {
            $path = $this->option('path');
            $nameSpace = str_replace('/', '\\', $path);
            config(['repository.generator.rootNamespace'     => $nameSpace]);
            config(['repository.generator.basePath' => $path]);
        }

        $this->call('make:repository', [
            'name'        => $this->argument('name'),
            '--fillable'  => $this->option('fillable'),
            '--rules'     => $this->option('rules'),
            '--validator' => $validator,
            '--force'     => $this->option('force')
        ]);

        $this->call('make:bindings', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force')
        ]);

        $this->call('move:entities', ['path' => $path]);
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null
            ],
            [
                'validator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds validator reference to the repository.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ],
            [
                'path',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Path to module repository.',
                null
            ]
        ];
    }
}
