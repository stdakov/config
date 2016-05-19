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
    protected $config       = [];
    protected $loadedConf   = [];
    protected $configPath   = '';
    protected $loadedOption = [];

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
        $this->loadedOption = [];

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
     * @return array|$this
     *
     * @throws \Exception
     */
    public function get($name = "")
    {
        if (empty($this->loadedConf)) {
            throw new \Exception('The configuration is not loaded');
        }

        if ($name == '') {
            if (!empty($this->loadedOption)) {
                $config = $this->loadedOption;
            } else {
                $config = $this->loadedConf;
            }
        } elseif (array_key_exists($name, $this->loadedConf) && empty($this->loadedOption)) {
            $config = $this->loadedConf[$name];
        } elseif (array_key_exists($name, $this->loadedOption)) {
            $config = $this->loadedOption[$name];
        } else {
            throw new \Exception('Missing option:' . $name);
        }

        $this->loadedOption = $config;

        return $this;
    }

    public function value()
    {
        return $this->loadedOption;
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
        $configFiles = glob($configPath . '*.{php,ini,json,xmp}', GLOB_BRACE);

        foreach ($configFiles as $config) {
            $this->registerConfig($config);
        }
    }

    /**
     * @param string $config
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function registerConfig($config, $name = '')
    {
        if (!file_exists($config)) {
            throw new \Exception('Missing configuration:' . $config);
        }

        $pathParts = pathinfo($config);

        $configData = [];

        switch ($pathParts['extension']) {
            case 'php':
                ob_start();
                $configData = require_once($config);
                ob_end_clean();
                if (!$name) {
                    $name = basename($config, '.php');
                }
                break;
            case 'ini':
                $configData = parse_ini_file($config, true);
                if (!$name) {
                    $name = basename($config, '.ini');
                }
                break;
            case 'json':
                $configData = json_decode(file_get_contents($config), true);
                if (!$name) {
                    $name = basename($config, '.json');
                }
                break;
            case 'xmp':
                //TODO implement xml
                break;
            default:
                break;
        }

        if (empty($configData)) {
            throw new \Exception('Empty configuration:' . $config);
        }

        $this->set($configData, $name);

        return $this;
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