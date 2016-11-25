<?php
require_once "recipe/common.php";

// Loading servers configuration file
serverList('servers.yml');

// Set default deployment stage
set('default_stage', 'staging');

// Set directories to copy
set('copy_dirs', [
    'public/index.php',
    'public/.htaccess',
    'public/js/main.min.js',
    'public/img',
    'public/css/main.css',

    'module/',

    'config/application.config.php',
    'config/modules.config.php',
    'config/autoload/global.php',

    'data/language/en_GB.po',

    'composer.json',
]);

// Set shared directories to copy
set('shared_dirs', [
    'data/cache',
]);

// Set writables directories to copy
set('writable_dirs', get('shared_dirs'));

// By default, we don't have sudo capabilities
set('writable_use_sudo', false);

// Define uploading deployment task
task('deploy:upload', function() {
    $files = get('copy_dirs');
    $releasePath = env('release_path');

    foreach ($files as $file)
    {
        upload($file, "{$releasePath}/{$file}");
    }
});

// Create database configuration file
task('deploy:config', function() {
    $templateFilename = __DIR__ . "/config/autoload/local.php.template";
    $targetFilename = __DIR__ . "/config/autoload/local.php";
    $remoteFilename = "config/autoload/local.php";
    $releasePath = env('release_path');

    // Get the database config
    $config = env('database');

    // Load the template file
    writeLn("Loading template configuration file <info>" . $templateFilename . "</info>.");
    $template = file_get_contents($templateFilename);

    // Replace entries in the template file
    $template = str_replace("@@DSN@@", $config['dsn'], $template);
    $template = str_replace("@@USERNAME@@", $config['username'], $template);
    $template = str_replace("@@PASSWORD@@", $config['password'], $template);

    // Write the non templated file
    file_put_contents($targetFilename, $template);

    // Upload config file to server
    upload($targetFilename, "{$releasePath}/{$remoteFilename}");

    // Remove created file
    runLocally("rm -f " . $targetFilename);
})->desc('Installing database configuration file');

// Define a deployment target
function defineDeploymentTarget($target)
{
    // Define production deployment task
    task('deploy:' . $target, [
        'deploy:prepare',
        'deploy:release',
        'deploy:upload',
        'deploy:config',
        'deploy:shared',
        'deploy:writable',
        'deploy:vendors',
        'deploy:symlink',
        'current',
    ])->desc('Deploy application to ' . $target . '.');

    // Display message on successful deployment
    after('deploy:' . $target, 'success');
}

// Define both production and staging targets
defineDeploymentTarget('production');
defineDeploymentTarget('staging');
