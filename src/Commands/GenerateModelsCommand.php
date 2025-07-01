<?php

namespace Tomi\ModelGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GenerateModelsCommand extends Command
{
    // Permite generar solo una tabla puntual y define a qué carpeta se guardan los modelos generados
    protected $signature = 'modelgen:generate {--only=} {--path=app/Models}';

    protected $description = 'Genera modelos Eloquent desde la base de datos';

    public function handle()
    {
        $this->info('🔧 Generando modelos Eloquent...');

        $only = $this->option('only');
        $customPath = base_path($this->option('path'));

        // Verifica si la ruta personalizada existe, si no, la crea
        if (!is_dir($customPath)) {
            mkdir($customPath, 0755, true);
        }

        // Si se pasa --only, procesamos solo esa tabla. Si no, procesamos todas las tablas de la base.
        $tables = $only
            ? [ (object)[ 'name' => $only ] ]
            : collect(DB::select('SHOW TABLES'))->map(function ($obj) {
                return (object)[ 'name' => array_values((array)$obj)[0] ];
            });

        foreach ($tables as $tableObj) {
            $table = $tableObj->name;
            // Muestra el nombre de la tabla que está procesando
            $this->info("📄 Procesando tabla: $table");

            $columns = Schema::getColumnListing($table);
            $className = Str::studly(Str::singular($table));
            $namespace = str_replace('/', '\\', trim($this->option('path'), '/'));
            $modelCode = "<?php\n\n";
            $modelCode .= "namespace $namespace;\n\n";
            $modelCode .= "use Illuminate\\Database\\Eloquent\\Model;\n\n";
            $modelCode .= "class $className extends Model\n{\n";
            $modelCode .= "    protected \$table = '$table';\n";
            $modelCode .= "    protected \$fillable = [\n";

            foreach ($columns as $column) {
                $modelCode .= "        '$column',\n";
            }
            $modelCode .= "    ];\n";

            // Relaciones belongsTo (detectadas desde claves foráneas)
            $foreignKeys = DB::select("
                SELECT column_name, referenced_table_name
                FROM information_schema.key_column_usage
                WHERE table_schema = DATABASE()
                AND table_name = ?
                AND referenced_table_name IS NOT NULL
            ", [$table]);

            // Busca todas las claves foráneas de esta tabla
            foreach ($foreignKeys as $fk) {
                $relatedClass = Str::studly(Str::singular($fk->referenced_table_name));
                $method = Str::camel(Str::singular($fk->referenced_table_name));

                $modelCode .= "\n    public function $method()\n    {\n";
                $modelCode .= "        return \$this->belongsTo($relatedClass::class);\n";
                $modelCode .= "    }\n";
            }

            $modelCode .= "}\n";

            $filePath = "$customPath/$className.php";

            if (file_exists($filePath)) {
                $this->warn("⚠️  Modelo $className ya existe. Saltando...");
                continue;
            }

            file_put_contents($filePath, $modelCode);
        }

        // Mensaje final de éxito
        $this->info("✅ ¡Modelos generados en {$this->option('path')}!");
    }
}
