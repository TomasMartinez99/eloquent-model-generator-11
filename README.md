# 🧩 Eloquent Model Generator 11 (Laravel 11+)

Este plugin permite generar modelos Eloquent a partir de una base de datos ya existente, analizando su estructura real (tablas, columnas y relaciones).

---

## ✅ Funcionalidades actuales

- **Generación automática de archivos de modelo** con propiedad `$fillable` completa.
- **Detección y creación de relaciones `belongsTo()`** a partir de claves foráneas reales en la base.
- **Opción `--only=`** para generar solo un modelo puntual.
- **Opción `--path=`** para definir la carpeta destino donde se guardarán los modelos generados.
- **Evita sobrescribir modelos ya existentes**, protegiendo cambios manuales.
- **Namespace dinámico**, basado en la carpeta indicada en `--path`.
- Comando Artisan disponible: php artisan modelgen:generate


---

## 🧪 Requisitos

- Laravel 11+
- PHP 8.2+
- Composer 2.x

---

## 🚀 Instalación e integración en tu proyecto Laravel

### 1. Agregar el repositorio al `composer.json` de tu proyecto:

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/tomi-dev/eloquent-model-generator-11"
  }
]
```

> 🔁 Si ya existe la sección `"repositories"`, solo agregá el objeto dentro del array.

---

### 2. Instalar el paquete desde GitHub:

```bash
composer require tomi-dev/eloquent-model-generator-11
```

> ⚠️ Asegurate de reemplazar `tomi-dev` por el nombre real del vendor si cambia en el repo.

---

### 3. Publicar el `ServiceProvider` automáticamente (Laravel lo detecta solo vía *auto-discovery*)

Pero si no se registra automáticamente, podés agregarlo manualmente en `config/app.php`:

```php
'providers' => [
    // ...
    Tomi\ModelGenerator\ModelGeneratorServiceProvider::class,
],
```

---

### 4. Verificá que el comando Artisan funcione

```bash
php artisan list
```

Deberías ver el comando:

```bash
modelgen:generate    Genera modelos Eloquent desde la base de datos
```

---

## 🛠️ Uso básico

### Generar todos los modelos:

```bash
php artisan modelgen:generate
```

### Generar un modelo específico:

```bash
php artisan modelgen:generate --only=users
```

### Cambiar carpeta de destino de los modelos:

```bash
php artisan modelgen:generate --path=app/Models/DB
```

> Los modelos se guardarán en la carpeta `app/Models/DB` y tendrán ese namespace.

---

## 🧱 Ejemplo de modelo generado

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'created_at',
        'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
```

---

## 📌 Notas

- Las relaciones se crean solo para claves foráneas `realmente definidas` en la base.
- Si el modelo ya existe, se **omite** su generación para evitar sobrescritura.
