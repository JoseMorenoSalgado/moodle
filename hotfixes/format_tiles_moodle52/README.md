# Reparación de navegación animada de Tiles en Moodle 5.2

Este directorio contiene una herramienta CLI reversible para recuperar la navegación JavaScript del formato de curso **Tiles / Mosaico** (`format_tiles`).

La reparación:

- verifica que `format_tiles` esté instalado;
- detecta y sustituye la dependencia AMD obsoleta `core/modal_factory` por `core/modal` en código fuente y bundles compilados;
- activa `format_tiles/usejavascriptnav`;
- elimina la preferencia `format_tiles_stopjsnav` que puede dejar la navegación animada deshabilitada para usuarios concretos;
- purga las cachés de Moodle;
- guarda una copia de cada archivo modificado dentro de Moodledata, fuera del directorio público.

No modifica cursos, actividades, matrículas ni el esquema de la base de datos.

## 1. Copiar la herramienta al servidor

Copie `repair_navigation.php` a una ubicación administrativa del servidor. Puede permanecer dentro de este directorio si el repositorio está desplegado junto a Moodle.

## 2. Ejecutar primero en modo diagnóstico

```bash
/opt/cpanel/ea-php83/root/usr/bin/php repair_navigation.php \
  --moodleroot=/home/USUARIO/public_html \
  --dry-run
```

El modo `--dry-run` no modifica archivos, configuraciones ni preferencias.

## 3. Activar mantenimiento

```bash
/opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/public_html/admin/cli/maintenance.php --enable
```

## 4. Aplicar la reparación

```bash
/opt/cpanel/ea-php83/root/usr/bin/php repair_navigation.php \
  --moodleroot=/home/USUARIO/public_html
```

Para conservar las preferencias individuales existentes:

```bash
/opt/cpanel/ea-php83/root/usr/bin/php repair_navigation.php \
  --moodleroot=/home/USUARIO/public_html \
  --keep-user-preferences
```

## 5. Completar actualización y salir de mantenimiento

```bash
/opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/public_html/admin/cli/upgrade.php --non-interactive
/opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/public_html/admin/cli/purge_caches.php
/opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/public_html/admin/cli/maintenance.php --disable
```

Después realice `Ctrl + F5` o pruebe en una ventana privada.

## Validación

Compruebe como mínimo:

1. abrir el curso con formato Tiles;
2. pulsar varios mosaicos consecutivamente;
3. regresar al nivel principal del curso;
4. abrir actividades normales y actividades Drive Resource;
5. comprobar la consola del navegador;
6. confirmar que no aparece `core/modal_factory`, `No define call` o un error RequireJS.

## Respaldo y reversión

Si la herramienta modifica archivos, guarda los originales en:

```text
$CFG->dataroot/temp/format_tiles_navigation_hotfix/<fecha-hora>/
```

Para revertir:

1. active mantenimiento;
2. copie los archivos respaldados a sus rutas originales dentro de `course/format/tiles/amd/`;
3. purgue las cachés de Moodle;
4. desactive mantenimiento.

## Solución permanente

Este hotfix recupera el servicio de forma controlada. La solución permanente es instalar una versión oficial de `format_tiles` compatible con la versión exacta de Moodle y reconstruir los bundles AMD con la herramienta Grunt de Moodle cuando se modifique `amd/src/`.
