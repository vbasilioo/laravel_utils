<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

class ReportUnusedCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:report-unused-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um relatório de código não utilizado na aplicação.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando a verificação de código não utilizado...');

        $finder = new Finder();
        
        $finder->files()->in(app_path())->name('*.php');

        $usedFunctions = [];

        $usedClasses = [];

        foreach($finder as $file){
            $fileContent = File::get($file->getRealPath());
            
            $fileName = $file->getFilenameWithoutExtension();

            preg_match_all('/function\s+(\w+)/', $fileContent, $functionMatches);

            $functions = $functionMatches[1];

            preg_match_all('/class\s+(\w+)/', $fileContent, $classMatches);

            $classes = $classMatches[1];

            foreach($functions as $function) $usedFunctions[$function] = $fileName;

            foreach($classes as $class) $usedClasses[$class] = $fileName;
        }

        $this->checkFunctionUsages($usedFunctions);

        $this->checkClassUsages($usedClasses);

        $this->info('Verificação de código não utilizado concluída!');
    }

    protected function checkFunctionUsages(array $functions){
        $finder = new Finder();

        $finder->files()->in(app_path())->name('*.php');

        foreach($finder as $file){
            $fileContent = File::get($file->getRealPath());

            foreach($functions as $function => $origin) if(strpos($fileContent, $function . '(') === false && strpos($fileContent, $function . ' ') === false) $this->warn("Função não utilizada: {$function} em {$origin}. Verifique o arquivo: {$file->getRelativePathname()}");
        }
    }

    protected function checkClassUsages(array $classes){
        $finder = new Finder();

        $finder->files()->in(app_path())->name('*.php');

        foreach($finder as $file){
            $fileContent = File::get($file->getRealPath());

            foreach($classes as $class => $origin) if(strpos($fileContent, $class) === false) $this->warn("Classe não utilizada: {$class} em {$origin}. Verifique o arquivo: {$file->getRelativePathname()}");
        }
    }
}
