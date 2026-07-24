# Moodle

Repositorio de instalación, mantenimiento y correcciones operativas de Moodle.

## Hotfixes disponibles

### Navegación animada de Tiles en Moodle 5.2

- Herramienta CLI: `hotfixes/format_tiles_moodle52/repair_navigation.php`
- Procedimiento, validación y reversión: `hotfixes/format_tiles_moodle52/README.md`

La herramienta revisa dependencias AMD obsoletas, reactiva la navegación JavaScript de `format_tiles`, limpia preferencias que la deshabilitan y purga las cachés de Moodle sin modificar cursos ni actividades.
