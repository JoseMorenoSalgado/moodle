<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Repair animated navigation for format_tiles on Moodle 5.2.
 *
 * This CLI utility performs a dependency-safe, reversible repair:
 * - verifies that format_tiles is installed;
 * - replaces the removed core/modal_factory AMD dependency with core/modal;
 * - re-enables JavaScript navigation;
 * - clears the per-user preference that disables JavaScript navigation;
 * - purges Moodle caches.
 *
 * Original files are copied to Moodledata before any modification.
 *
 * @copyright  2026 Jose Erasmo Moreno Salgado - Elearning Cloud
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

$options = getopt('', [
    'moodleroot:',
    'dry-run',
    'keep-user-preferences',
    'help',
]);

if (isset($options['help'])) {
    echo "Repair format_tiles animated navigation for Moodle 5.2.\n\n";
    echo "Usage:\n";
    echo "  php repair_navigation.php --moodleroot=/path/to/moodle [--dry-run] [--keep-user-preferences]\n\n";
    echo "Options:\n";
    echo "  --moodleroot=PATH          Absolute Moodle application root.\n";
    echo "  --dry-run                  Report changes without modifying files or configuration.\n";
    echo "  --keep-user-preferences    Do not clear format_tiles_stopjsnav preferences.\n";
    echo "  --help                     Show this help.\n";
    exit(0);
}

$moodleroot = isset($options['moodleroot']) ? rtrim((string) $options['moodleroot'], DIRECTORY_SEPARATOR) : '';
if ($moodleroot === '') {
    $candidate = dirname(__DIR__, 2);
    if (is_file($candidate . '/config.php')) {
        $moodleroot = $candidate;
    }
}

if ($moodleroot === '' || !is_file($moodleroot . '/config.php')) {
    fwrite(STDERR, "ERROR: Provide a valid Moodle root using --moodleroot=/absolute/path.\n");
    exit(1);
}

require($moodleroot . '/config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->libdir . '/adminlib.php');

$dryrun = isset($options['dry-run']);
$keepuserpreferences = isset($options['keep-user-preferences']);
$tilesroot = $CFG->dirroot . '/course/format/tiles';

if (!is_dir($tilesroot) || !is_file($tilesroot . '/version.php')) {
    cli_error('format_tiles is not installed under course/format/tiles.');
}

$plugininfo = core_plugin_manager::instance()->get_plugin_info('format_tiles');
if (!$plugininfo) {
    cli_error('Moodle does not recognise format_tiles as an installed plugin.');
}

cli_writeln('[MOODLE] ' . ($CFG->release ?? 'unknown release'));
cli_writeln('[PLUGIN] format_tiles version ' . ($plugininfo->versiondisk ?? 'unknown'));

$targets = [
    'amd/src/course_mod_modal.js',
    'amd/src/edit_icon_picker.js',
    'amd/build/course_mod_modal.min.js',
    'amd/build/edit_icon_picker.min.js',
];

$timestamp = gmdate('Ymd-His');
$backupdir = $CFG->dataroot . '/temp/format_tiles_navigation_hotfix/' . $timestamp;
$changedfiles = [];
$missingfiles = [];

foreach ($targets as $relativepath) {
    $path = $tilesroot . '/' . $relativepath;
    if (!is_file($path)) {
        $missingfiles[] = $relativepath;
        continue;
    }

    $content = file_get_contents($path);
    if ($content === false) {
        cli_error('Unable to read ' . $path);
    }

    $updated = str_replace('core/modal_factory', 'core/modal', $content, $replacementcount);
    if ($replacementcount === 0) {
        cli_writeln('[OK] No obsolete modal dependency in ' . $relativepath);
        continue;
    }

    cli_writeln('[CHANGE] ' . $relativepath . ': ' . $replacementcount . ' replacement(s).');
    $changedfiles[] = $relativepath;

    if ($dryrun) {
        continue;
    }

    if (!is_dir($backupdir) && !make_writable_directory($backupdir)) {
        cli_error('Unable to create backup directory: ' . $backupdir);
    }

    $backupfile = $backupdir . '/' . str_replace('/', '__', $relativepath);
    if (!copy($path, $backupfile)) {
        cli_error('Unable to back up ' . $relativepath . ' to ' . $backupfile);
    }

    $permissions = fileperms($path) & 0777;
    $temporaryfile = tempnam(dirname($path), '.format-tiles-hotfix-');
    if ($temporaryfile === false) {
        cli_error('Unable to create a temporary file beside ' . $relativepath);
    }

    if (file_put_contents($temporaryfile, $updated, LOCK_EX) === false) {
        @unlink($temporaryfile);
        cli_error('Unable to write the temporary replacement for ' . $relativepath);
    }

    @chmod($temporaryfile, $permissions);
    if (!rename($temporaryfile, $path)) {
        @unlink($temporaryfile);
        cli_error('Unable to atomically replace ' . $relativepath);
    }
}

if ($dryrun) {
    cli_writeln('Dry run complete. No files, configuration or preferences were changed.');
    exit(0);
}

set_config('usejavascriptnav', 1, 'format_tiles');
cli_writeln('[CONFIG] format_tiles/usejavascriptnav = 1');

if (!$keepuserpreferences) {
    $DB->delete_records('user_preferences', ['name' => 'format_tiles_stopjsnav']);
    cli_writeln('[PREFERENCES] Cleared format_tiles_stopjsnav user preferences.');
} else {
    cli_writeln('[PREFERENCES] Existing format_tiles_stopjsnav preferences preserved.');
}

purge_all_caches();
cli_writeln('[CACHE] Moodle caches purged.');

if ($changedfiles) {
    cli_writeln('[BACKUP] Original files saved under: ' . $backupdir);
} else {
    cli_writeln('[FILES] No AMD dependency replacement was required.');
}

if ($missingfiles) {
    cli_writeln('[WARNING] Missing optional target files: ' . implode(', ', $missingfiles));
}

cli_writeln('Repair completed. Perform a hard browser refresh before testing the course.');
exit(0);
