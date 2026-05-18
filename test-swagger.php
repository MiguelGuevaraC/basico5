<?php

define('L5_SWAGGER_CONST_HOST', 'http://localhost/mvc-productos/basico5/public');

require __DIR__.'/vendor/autoload.php';

use OpenApi\Generator;

$generator = new Generator();
$openapi = $generator->generate([
    __DIR__.'/app/Swagger',
    __DIR__.'/app/Http/Controllers/Api'
]);

file_put_contents(__DIR__.'/storage/api-docs/api-docs.json', $openapi->toJson());

echo "Generated!\n";
echo $openapi->toJson();



