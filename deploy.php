<?php

declare(strict_types=1);

namespace Deployer;

use Exception;

require 'recipe/symfony.php';

// General settings
set('keep_releases', 2);
set('shared_dirs', [
    'var/log',
    'var/sessions',
]);
set('shared_files', [
    '.env.local',
]);
set('writable_dirs', [
    'var',
]);
set('writable_chmod_mode', '0777');
set('bin/php', '/usr/bin/php8.1');

// Project name
set('application', 'dentbul');

// Project repository
set('repository', 'git@gitlab.bigoen.net:customer/backend/dentbul.git');

// set hosts.
set('remote_user', 'root');
set('port', 3539);
set('identity_file', '~/.ssh/id_rsa');
set('forward_agent', true);
set('ssh_multiplexing', true);
set('ssh_arguments', [
    '-o UserKnownHostsFile=/dev/null,StrictHostKeyChecking=no,ControlMaster=auto,ControlPersist=15,ControlPath=/tmp/symfony/%r@%h:%p',
]);

host('dentbul.bigoen.net')
    ->setDeployPath('/var/www/backend/dentbul/production')
    ->set('stage', 'prod')
    ->set('branch', 'master');
host('towersimplant.com.tr')
    ->setDeployPath('/var/www/backend/towersimplant/production')
    ->set('stage', 'prod')
    ->set('branch', 'master');
host('dev.dentbul.bigoen.net')
    ->setDeployPath('/var/www/backend/dentbul/develop')
    ->set('stage', 'dev')
    ->set('branch', 'develop');

desc('Reload php fpm 8.1');
task('reload-fpm', function (): void {
    run('systemctl reload php8.1-fpm.service');
});

task('deploy:key:action', function (): void {
    cd('{{release_path}}');
    run('{{bin/console}} lexik:jwt:generate-keypair --skip-if-exists');

    $message = 'Key generated!';
    writeln(sprintf('<info>%s</info>', $message));
});
after('deploy:vendors', 'deploy:key:action');

desc('Database create');
task('doctrine:database:create', function (): void {
    run('{{bin/console}} doctrine:database:create');
});
desc('Database schema update');
task('doctrine:schema:update', function (): void {
    try {
        run('{{bin/console}} doctrine:schema:update -f -q -n');
    } catch (Exception) {
        run('{{bin/console}} doctrine:database:create');
        run('{{bin/console}} doctrine:schema:update -f -q -n');
    }
});

desc('Front packages worker. (yarn run for production.)');
task('front:packages', function (): void {
    run('source ~/.zshrc && cd {{release_path}} && nvm use $(cat .nvmrc) && yarn');
    run('source ~/.zshrc && cd {{release_path}} && nvm use $(cat .nvmrc) && yarn run encore production');
});

desc('Bundle assets install.');
task('assets:install', function (): void {
    run('{{bin/console}} assets:install --symlink');
});

desc('Doctrine database fixture.');
task('doctrine:fixture:load', function (): void {
    run('{{bin/console}} d:f:l --purge-with-truncate --env=dev');
});


desc('Deploy Success after use it.');
task('dep-success', [
    'doctrine:schema:update',
    'assets:install',
    'front:packages',
]);

// if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy:vendors', 'dep-success');
before('deploy:cleanup', 'reload-fpm');
