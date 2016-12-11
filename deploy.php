<?php
require_once "recipe/common.php";

// Loading servers configuration file
serverList('servers.yml');

// Re-define composer options to ignore php version
env('composer_options', 'install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction --ignore-platform-reqs');

// Re-define used php version
env('bin/php', function () {
    return run('which php5.5-cli')->toString();
});

// Re-define composer paths
env('bin/composer', function () {
    $releasePath = run("cd {{release_path}} && pwd -P")->toString();

    if (commandExist('composer')) {
        $composer = run('which composer')->toString();
    }

    if (empty($composer)) {
        run("cd " . $releasePath . " && curl -sS https://getcomposer.org/installer | {{bin/php}}");
        $composer = '{{bin/php}} ' . $releasePath . '/composer.phar';
    }

    return $composer;
});


// Set default deployment stage
set('default_stage', 'staging');

// Set directories to copy
set('upload_dirs', [
    'public/index.php',
    'public/.htaccess',
    'public/js/main.min.js',
    'public/img/',
    'public/css/main.css',

    'module/',

    'config/application.config.php',
    'config/modules.config.php',
    'config/autoload/global.php',

    'data/language/en_GB.po',

    'composer.json',
]);

// Set shared directories to copy
set('cache_dirs', [
    'data/cache',
]);

// Set writables directories to copy
set('writable_dirs', get('cache_dirs'));

// By default, we don't have sudo capabilities
set('writable_use_sudo', false);

// Define uploading deployment task
task('deploy:upload_dirs', function() {
    $files = get('upload_dirs');
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

// Create cache diretories
task('deploy:cache_dirs', function() {
    $files = get('cache_dirs');
    $releasePath = env('release_path');

    foreach ($files as $file)
    {
        run('cd {{release_path}} && if [ ! -d ' . $file . ' ]; then mkdir -p ' . $file . '; fi');
    }
})->desc('Create cache diretories');

// Re-define symlink deployment task
task('deploy:symlink', function () {
    $releasePath = run("cd {{release_path}} && pwd -P")->toString();
    run("cd {{deploy_path}} && ln -sfn " . $releasePath . " current"); // Atomic override symlink.
    run("cd {{deploy_path}} && rm release"); // Remove release link.
})->desc('Creating symlink to release');


// Define production deployment task
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:upload_dirs',
    'deploy:cache_dirs',
    'deploy:config',
    'deploy:writable',
    'deploy:vendors',
    'deploy:symlink',
    'current',
])->desc('Deploy application.');

// Display message on successful deployment
after('deploy', 'success');
