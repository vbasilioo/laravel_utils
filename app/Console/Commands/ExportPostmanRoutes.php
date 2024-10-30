<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class ExportPostmanRoutes extends Command
{
    protected $signature = 'app:export-postman-routes';

    protected $description = 'Exportar rotas do Laravel para uma coleção JSON para o Postman.';

    public function handle(){
        $routes = Route::getRoutes();

        $routesPostman = [];

        foreach ($routes as $route) {
            if(!in_array('api', $route->middleware())) continue;

            $uri = $route->uri();

            if(str_starts_with($uri, 'api/')) $uri = substr($uri, 4);
            
            $methods = $route->methods();

            if($this->shouldSkipRoute($methods)) continue;

            $mainSegment = explode('/', $uri)[0] ?? 'root';

            foreach($this->filterMethods($methods) as $method) $routesPostman[$mainSegment][] = $this->formatRouteForPostman($uri, $method);
        }

        $this->saveToFile($routesPostman);

        $this->createEnvironmentFile();

        $this->info("Rotas para o Postman exportadas para " . base_path('postman_collection.json'));

        $this->info("Arquivo de ambiente para o Postman criado em " . base_path('postman_environment.json'));
    }

    private function shouldSkipRoute(array $methods): bool{
        return $methods === ['HEAD'];
    }

    private function filterMethods(array $methods): array{
        return array_filter($methods, fn($method) => $method !== 'HEAD');
    }

    private function formatRouteForPostman(string $uri, string $method): array{
        return [
            'name' => $uri,
            'request' => [
                'method' => $method,
                'header' => [],
                'url' => [
                    'raw' => "{{base_url}}/{$uri}",
                    'host' => ["{{base_url}}"],
                    'path' => explode('/', $uri),
                ],
            ],
        ];
    }

    private function saveToFile(array $postmanRoutes): void{
        $collection = [
            'info' => [
                'name' => env('APP_NAME'),
                'description' => 'Coleção de rotas do Laravel exportada para o Postman',
                'version' => '1.0.0',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'item' => [],
        ];

        foreach ($postmanRoutes as $folder => $routes) {
            $collection['item'][] = [
                'name' => ucfirst($folder),
                'item' => $routes,
            ];
        }

        file_put_contents(base_path('postman_collection.json'), json_encode($collection, JSON_PRETTY_PRINT));
    }

    private function createEnvironmentFile(): void{
        $environment = [
            'id' => 'laravel_environment',
            'name' => env('APP_NAME'),
            'values' => [
                [
                    'key' => 'base_url',
                    'value' => 'http://127.0.0.1:8000/api',
                    'type' => 'default',
                    'enabled' => true
                ]
            ],
            'timestamp' => time(),
            '_postman_variable_scope' => 'environment',
            '_postman_exported_at' => now()->toIso8601String(),
            '_postman_exported_using' => 'Laravel Custom Export Script'
        ];

        file_put_contents(base_path('postman_environment.json'), json_encode($environment, JSON_PRETTY_PRINT));
    }
}
