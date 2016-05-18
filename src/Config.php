<?php
namespace Dakov;

/**
 * Class Config
 *
 * @package stdakov/config
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
class Config
{
    protected $config     = [];
    protected $loadedConf = [];
    protected $configPath = '';

    public function __construct($configPath = '')
    {
        $this->configPath = $configPath;
    }

    public function listConfigs()
    {
        return array_keys($this->config);
    }

    /**
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function load($name = '')
    {
        $this->loadedConf = [];

        if ($name == '') {
            $this->loadedConf = $this->config;
        } elseif (array_key_exists($name, $this->config)) {
            $this->loadedConf = $this->config[$name];
        } else {
            throw new \Exception('Missing Configuration:' . $name);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return array
     *
     * @throws \Exception
     */
    public function get($name = "")
    {
        if (empty($this->loadedConf)) {
            throw new \Exception('The configuration is not loaded');
        }

        if ($name == '') {
            $config = $this->loadedConf;
        } elseif (array_key_exists($name, $this->loadedConf)) {
            $config = $this->loadedConf[$name];
        } else {
            throw new \Exception('Missing option:' . $name);
        }

        $this->loadedConf = [];

        return $config;
    }

    /**
     * @param array $config
     * @param string $name
     */
    public function set($config, $name)
    {
        $this->config[$name] = $config;
    }

    /**
     * @param string $configDir
     */
    public function loadConfigs($configDir = "")
    {
        $configDir = ($configDir == "" ? $this->configPath : $configDir);
        $configPath = rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $configFiles = glob($configPath . '*');

        foreach ($configFiles as $config) {
            $pathParts = pathinfo($config);

            switch ($pathParts['extension']) {
                case 'php':
                    ob_start();
                    $configData = require_once($config);
                    $this->set($configData, basename($config, '.php'));
                    ob_end_clean();
                    break;
                case 'ini':
                    $configData = parse_ini_file($config, true);
                    if (!empty($configData)) {
                        $this->set($configData, basename($config, '.ini'));
                    }
                    break;
                case 'json':
                    $configData = json_decode(file_get_contents($config), true);
                    if (!empty($configData)) {
                        $this->set($configData, basename($config, '.json'));
                    }
                    break;
                case 'xmp':
                    //TODO implement xml
                    break;
                default:
                    break;
            }

        }
    }

    /**
     * @param string $helperDir
     * @return $this
     */
    public function loadHelpers($helperDir)
    {
        $helperFiles = glob(rtrim($helperDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');
        foreach ($helperFiles as $helper) {
            require_once($helper);
        }

        return $this;
    }
}