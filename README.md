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
