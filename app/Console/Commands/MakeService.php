<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $stub = $this->getStub();
        $name = $this->argument('name');
        $output = $this->getPath() . DIRECTORY_SEPARATOR . $name . '.php';

        $stubContent = $this->files->get($stub);
        $stubContent = str_replace('{{ classname }}', $name, $stubContent);

        if ($this->files->exists($output)) {
            $this->error($name . ' has exists!');
            return 0;
        }

        $this->files->put($output, $stubContent);
        $this->info($name . ' created!');
        return 0;
    }

    protected function getStub(): string
    {
        $ds = DIRECTORY_SEPARATOR;
        $stub = $ds . 'stubs' . $ds . 'service.stub';
        return $this->laravel->basePath(trim($stub, $ds));
    }

    protected function getPath(): string
    {
        $ds = DIRECTORY_SEPARATOR;
        $stub = $ds . 'app' . $ds . 'Http' . $ds . 'Services';
        $path = $this->laravel->basePath(trim($stub, $ds));
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path);
        }
        return $path;
    }
}
