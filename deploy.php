<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'vinhome');

// Project repository
set('repository', 'git@github.com:zzvvmm/vinhome.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false); 

// Default branch
set('branch', 'develop');

// Shared files/dirs between deploys
add('shared_files', [
    '.env',
]);

add('shared_dirs', [
    'storage',
    'bootstrap/cache',
]);

// Hosts

host('52.220.48.43')
    ->user('ec2-user')
    ->stage('development')
    ->set('deploy_path', '/var/www/html/vinhome')
    ->set('http_user', 'ec2-user')
    ->set('writable_mode', 'chmod')
    ->forwardAgent(false);
    
// Tasks

// task('build', function () {
//     run('cd {{release_path}} && build');
// });

task('deploy', [
    // outputs the branch and IP address to the command line
    'deploy:info',
    // preps the environment for deploy, creating release and shared directories
    'deploy:prepare',
    // adds a .lock file to the file structure to prevent numerous deploys executing at once
    'deploy:lock',
    // removes outdated release directories and creates a new release directory for deploy
    'deploy:release',
    // clones the project Git repository
    'deploy:update_code',
    // loops around t he list of shared directories defined in the config file
    // and generates symlinks for each
    'deploy:shared',
    // loops around the list of writable directories defined in the config file
    // and changes the owner and permissions of each file or directory
    'deploy:writable',
    // if Composer is used on the site, the Composer install command is executed
    'deploy:vendors',
    // loops around release and removes unwanted directories and files
    'deploy:clear_paths',
    // links the deployed release to the "current" symlink
    'deploy:symlink',
    // deletes the unlock file, allowing further deploys to be executed
    'deploy:unlock',
    // loops around a list of release directories and removes any which are now outdated
    'cleanup',
    // can be used by the user to assign custom tasks to execute on successful deployments
    'success',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
