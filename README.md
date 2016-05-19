# config
This is powerful configuration tool to manage every configuration file.
##Supported files:
ini,php,json

##Installation
The preferred way to install this tool is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require stdakov/config:dev-master
```

or add

```
"stdakov/config": "dev-master"
```


##Usage
For example we will have 3 configuration files in config dir:
'ini.ini'
'php.php'
'json.json'

```php
require 'vendor/autoload.php';

$configFolder = 'config';
$helpersFolder = 'helpers';

$config = new \Dakov\Config();
```

We can also load helper functions if we have.

```php
$config->loadHelpers($helpersFolder);
```

Lets load our configurations

```php
$config->loadConfigs($configFolder);

print_r($config->listConfigs());

//This is file configuration names which are loaded
```

Output

```
Array
(
    [0] => ini
    [1] => json
    [2] => php
)
```
------------------INI--Configuration-----------------------
```php

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

$config->load('ini')->get('database')->value()['DB_HOST'] == $config->load('ini')->get('database')->get('DB_HOST')->value();
```

Will output

```
Array
(
    [database] => Array
        (
            [DB_HOST] => localhost
            [DB_DATABASE] => test
            [DB_USERNAME] => root
            [DB_PASSWORD] => test
        )

    [admin authentication] => Array
        (
            [ADMIN_AUTH] => 1
            [ADMIN_TABLE] => admin
        )

    [upload] => Array
        (
            [tmp_dir] => /tmp
            [upload_dir] => /public/storage
        )

)
Array
(
    [DB_HOST] => localhost
    [DB_DATABASE] => test
    [DB_USERNAME] => root
    [DB_PASSWORD] => test
)
string(21) "Missing option:server"
string(25) "Missing Configuration:app"
```
------------------JSON--Configuration-----------------------
```php
print_r($config->load('json')->get()->value());
print_r($config->load('json')->get('name')->value());
```

Will output

```
Array
(
    [name] => stuff
    [components] => Array
        (
            [Version] => Array
                (
                    [versions] => 1
                )

            [Source] => Array
                (
                    [ip] => 0.0.0.1
                )

            [Empty] => Array
                (
                )

        )

)
stuff
```

------------------PHP--Configuration-----------------------

```php
print_r($config->load('php')->get()->value());
print_r($config->load('php')->get('test')->value());
```

Will output

```
Array
(
    [test] => Array
        (
            [show] => this is test
        )

)
Array
(
    [show] => this is test
)
```

------------------Custom Configuration-------------------------

```php
$configData = [
    'user'     => 'username',
    'password' => 'password'
];

$config->set($configData, 'custom');
print_r($config->load('custom')->get()->value());
print_r($config->load('custom')->get('user')->value());

```

Will output

```
Array
(
    [user] => username
    [password] => password
)
username
-------------------------------------------
```


-------------------Custom dir-INI-----------------------

```php

$customConfigPath = 'config/new/ini2.ini';
$config->registerConfig($customConfigPath);
print_r($config->load('ini2')->get()->value());
print_r($config->load('ini2')->get('database')->value());
```

Will output

```
Array
(
    [database] => Array
        (
            [DB_HOST] => localhost
            [DB_DATABASE] => test
            [DB_USERNAME] => root
            [DB_PASSWORD] => test
        )

    [admin authentication] => Array
        (
            [ADMIN_AUTH] => 1
            [ADMIN_TABLE] => admin
        )

    [upload] => Array
        (
            [tmp_dir] => /tmp
            [upload_dir] => /public/storage
        )

)
Array
(
    [DB_HOST] => localhost
    [DB_DATABASE] => test
    [DB_USERNAME] => root
    [DB_PASSWORD] => test
)
string(21) "Missing option:server"
string(26) "Missing Configuration:app2"

```

-------------------Custom dir-INI-with custom name----------------------

```php
$customConfigPath = 'config/new/ini2.ini';
$config->registerConfig($customConfigPath, 'myIni');
print_r($config->load('myIni')->get()->value());
print_r($config->load('myIni')->get('database')->value());
```

Will output

```
Array
(
    [database] => Array
        (
            [DB_HOST] => localhost
            [DB_DATABASE] => test
            [DB_USERNAME] => root
            [DB_PASSWORD] => test
        )

    [admin authentication] => Array
        (
            [ADMIN_AUTH] => 1
            [ADMIN_TABLE] => admin
        )

    [upload] => Array
        (
            [tmp_dir] => /tmp
            [upload_dir] => /public/storage
        )

)
Array
(
    [DB_HOST] => localhost
    [DB_DATABASE] => test
    [DB_USERNAME] => root
    [DB_PASSWORD] => test
)
string(21) "Missing option:server"
string(21) "Missing option:server"

```


For working examples see the tests/example.php

##TODO
1.Implement xml files
2.Add functionality $config->load('ini2')->get('database.host')->value();
