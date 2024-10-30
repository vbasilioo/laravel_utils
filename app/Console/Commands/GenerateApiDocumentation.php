<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class GenerateApiDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:api-docs {--format=markdown : The format of the documentation (markdown or html)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gere documentação de API a partir das rotas registradas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        
        $format = $this->option('format');

        if($format !== 'markdown' && $format !== 'html'){
            $this->error("Formato inválido. Use 'markdown' ou 'html'.");
            return true;
        }

        $docsContent = '';

        foreach($routes as $route){
            if(strpos($route->uri, 'api/') !== 0) continue; 
            
            foreach ($route->methods as $method) {
                $uri = $route->uri;

                $description = $this->getRouteDescription($method, $uri);

                $docsContent .= "### {$method} {$uri}\n";
                $docsContent .= "Descrição: {$description}\n\n";
            }
        }

        $this->saveDocumentation($docsContent, $format);

        $this->info("Documentação da API gerada com sucesso no formato {$format}.");

        return false;
    }

    protected function getRouteDescription($method, $uri)
    {
        $baseUri = preg_replace('/^api\//', '', $uri);
        
        switch ($method) {
            case 'GET':
                if (preg_match('/^([^\/]+)$/', $baseUri)) return 'Lista todos os ' . ucfirst($baseUri);
                elseif (preg_match('/^([^\/]+)\/([^\/]+)$/', $baseUri, $matches)) return 'Retorna um ' . ucfirst($matches[1]) . ' com ID ' . $matches[2];
                break;
            case 'POST':
                if(preg_match('/^([^\/]+)$/', $baseUri)) return 'Cria um novo ' . ucfirst($baseUri);
                break;
            case 'PUT':
            case 'PATCH':
                if(preg_match('/^([^\/]+)\/([^\/]+)$/', $baseUri, $matches)) return 'Atualiza um ' . ucfirst($matches[1]) . ' com ID ' . $matches[2];
                break;
            case 'DELETE':
                if(preg_match('/^([^\/]+)\/([^\/]+)$/', $baseUri, $matches)) return 'Remove um ' . ucfirst($matches[1]) . ' com ID ' . $matches[2];
                break;
        }
        
        return 'Descrição não disponível.';
    }

    protected function saveDocumentation($content, $format){
        $filename = public_path("api-docs." . ($format === 'markdown' ? 'md' : 'html'));

        $format === 'markdown' ? $content = '# Documentação\n\n' . $content : $content = "<html><body><h1>Documentação</h1>" . nl2br($content) . "</body></html>";
    
        file_put_contents($filename, $content);
    }
}
