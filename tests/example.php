<?php
require '../vendor/autoload.php';

$configFolder = 'config';
$helpersFolder = 'helpers';

$config = new \Dakov\Config();
$config->loadHelpers($helpersFolder);
$config->loadConfigs($configFolder);

print_r($config->listConfigs());
echo "\n------------------INI--Configuration-----------------------\n";
print_r($config->load('ini')->get()->value());
print_r($config->load('ini')->get('database')->value());
try {
    print_r($config->load('ini')->get('server')->value());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

try {
    print_r($config->load('app')->get('server')->value());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
echo "\n------------------JSON--Configuration-----------------------\n";
print_r($config->load('json')->get()->value());
print_r($config->load('json')->get('name')->value());
echo "\n------------------PHP--Configuration-----------------------\n";
print_r($config->load('php')->get()->value());
print_r($config->load('php')->get('test')->value());
echo "\n------------------Custom Configuration-------------------------\n";


$configData = [
    'user'     => 'username',
    'password' => 'password'
];

$config->set($configData, 'custom');
print_r($config->load('custom')->get()->value());
print_r($config->load('custom')->get('user')->value());

echo "\n-------------------Custom dir-INI-----------------------\n";
$customConfigPath = 'config/new/ini2.ini';
$config->registerConfig($customConfigPath);
print_r($config->load('ini2')->get()->value());
if ($config->load('ini2')->get('database')->get('DB_HOST')->value() == $config->load('ini2')->get('database')->get()->value()['DB_HOST']) {
    print_r('There are same');
}
print_r($config->load('ini2')->get('database')->value());
try {
    print_r($config->load('ini2')->get('server')->value());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

try {
    print_r($config->load('app2')->get('server')->value());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

echo "\n-------------------Custom dir-INI-with custom name----------------------\n";
$customConfigPath = 'config/new/ini2.ini';
$config->registerConfig($customConfigPath, 'myIni');
print_r($config->load('myIni')->get()->value());
print_r($config->load('myIni')->get('database')->value());
try {
    print_r($config->load('myIni')->get('server')->value());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

try {
    print_r($config->load('myIni')->get('server')->value());
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

echo "\n-------------------Get recursive options from loaded config----------------------\n";
print_r($config->load('myIni')->get('database')->get('DB_HOST')->value());