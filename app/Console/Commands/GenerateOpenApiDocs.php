<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateOpenApiDocs extends Command
{
    protected $signature = 'openapi:generate';
    protected $description = 'Genera la documentación OpenAPI';

    public function handle()
    {
        $this->info('Generando documentación OpenAPI...');

        $generator = new Generator();
        $openapi = $generator->generate([
            app_path('Swagger'),
            app_path('Http/Controllers/Api'),
        ]);

        $openapiArray = json_decode($openapi->toJson(), true);

        // Replace server URL with APP_URL from environment
        if (env('APP_URL')) {
            $openapiArray['servers'] = [
                [
                    'url' => env('APP_URL'),
                    'description' => 'Servidor de producción'
                ]
            ];
        }

        $outputPath = storage_path('api-docs/api-docs.json');
        file_put_contents($outputPath, json_encode($openapiArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Documentación generada en: ' . $outputPath);
        return self::SUCCESS;
    }
}
