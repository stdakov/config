<?php
require '../vendor/autoload.php';

$configFolder = 'config';
$helpersFolder = 'helpers';

$config = new \Dakov\Config();
$config->loadHelpers($helpersFolder);
$config->loadConfigs($configFolder);

print_r($config->listConfigs());
echo "\n------------------INI--Configuration-----------------------\n";
print_r($config->load('ini')->get());
print_r($config->load('ini')->get('database'));
try {
    print_r($config->load('ini')->get('server'));
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

try {
    print_r($config->load('app')->get('server'));
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
echo "\n------------------JSON--Configuration-----------------------\n";
print_r($config->load('json')->get());
print_r($config->load('json')->get('name'));
echo "\n------------------PHP--Configuration-----------------------\n";
print_r($config->load('php')->get());
print_r($config->load('php')->get('test'));
echo "\n------------------Custom Configuration-------------------------\n";


$configData = [
    'user'     => 'username',
    'password' => 'password'
];

$config->set($configData, 'custom');
print_r($config->load('custom')->get());
print_r($config->load('custom')->get('user'));

echo "\n-------------------------------------------\n";