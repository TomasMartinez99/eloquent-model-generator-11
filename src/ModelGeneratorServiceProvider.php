<?php

namespace Tomi\ModelGenerator;

use Illuminate\Support\ServiceProvider;
use Tomi\ModelGenerator\Commands\GenerateModelsCommand;

class ModelGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registramos el comando personalizado
        $this->commands([
            GenerateModelsCommand::class,
        ]);
    }
}
