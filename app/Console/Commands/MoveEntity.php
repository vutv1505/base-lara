<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MoveEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:entity {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move all entities from current module to app/Entities';

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
        //
        $path = app_path();
        if ($this->option('path') != "default") {
            $path = $this->option('path');
        }
        $moduleEntityPath = config('repository.generator.basePath') . '/' . config('repository.generator.paths.models');
        if (!\File::isDirectory($moduleEntityPath)) {
            $this->warn('Path ' . $moduleEntityPath . ' not found');
            return;
        }

        $files = \File::files($moduleEntityPath);
        if (empty($files)) {
            $this->warn($moduleEntityPath . ' is empty');
            return;
        }

        foreach ($files as $entity) {
            $path = $entity->getPathname();
            $target = app_path('Entities') . '/' . $entity->getBasename();
            if (\File::exists($target)) {
                \File::delete($target);
            } else {
                \File::move($path, $target);
                $this->info($target);
            }
        }

        $this->info('Move entities success');
    }
}
