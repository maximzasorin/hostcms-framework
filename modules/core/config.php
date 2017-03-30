<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Module configurations
 *
 * @package HostCMS
 * @subpackage Core
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Config
{
	/**
	 * Loaded values
	 * @var array
	 */
	private $_values = array();

	/**
	 * The singleton instance.
	 * @var mixed
	 */
	static private $_instance = NULL;

	/**
	 * Register an existing instance as a singleton.
	 * @return object
	 */
	static public function instance()
	{
		if (!isset(self::$_instance))
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Get config path
	 * @param string $name
	 * @return string
	 */
	public function getPath($name)
	{
		$aConfig = explode('_', $name);
		$sFileName = array_pop($aConfig);

		$path = Core::$modulesPath;
		$path .= implode(DIRECTORY_SEPARATOR, $aConfig) . DIRECTORY_SEPARATOR;
		$path .= 'config' . DIRECTORY_SEPARATOR . $sFileName . '.php';
		$path = Core_File::pathCorrection($path);

		return $path;
	}

	/**
	 * Correct config name
	 * @param string $name
	 * @return string
	 */
	protected function _correctName($name)
	{
		return basename(strtolower($name));
	}

	/**
	 * Get config, e.g. 'Core_Myconfig' requires modules/core/config/myconfig.php
	 * @param string $name, e.g. 'Core_Myconfig'
	 * @param mixed $defaultValue
	 * @return mixed Config or NULL
	 */
	public function get($name, $defaultValue = NULL)
	{
		$name = $this->_correctName($name);

		if (!isset($this->_values[$name]))
		{
			$path = $this->getPath($name);

			$this->_values[$name] = is_file($path)
				? require_once($path)
				: $defaultValue;
		}

		return $this->_values[$name];
	}

	/**
	 * Convert value to string
	 * @param mixed $value Value
	 * @param $level Level of tabs, default 1
	 * @return string
	 */
	protected function _toString($value, $level = 1)
	{
		if (is_array($value))
		{
			$sTabs = str_repeat("\t", $level);

			$aTmp = array();
			foreach ($value as $tmpKey => $tmpValue)
			{
				$aTmp[] = $sTabs . "'" . str_replace("'", "\'", $tmpKey) . "' => " . $this->_toString($tmpValue, $level + 1);
			}

			return "array(\n" . implode(",\n", $aTmp) . "\n" . str_repeat("\t", $level - 1) . ")";
		}
		else
		{
			return is_string($value)
				? "'" . str_replace("'", "\'", $value) . "'"
				: $value;
		}
	}

	/**
	 * Set config value
	 * @param string $name Config name, e.g. 'Core_Myconfig' set modules/core/config/myconfig.php
	 * @param array $value Config value, e.g. array('key' => 'value')
	 * @return Core_Config
	 */
	public function set($name, $value)
	{
		$this->_values[$name] = $value;

		$path = $this->getPath($name);

		// Create destination dir
		Core_File::mkdir(dirname($path), CHMOD, TRUE);

		$content = "<?php\n\nreturn " . $this->_toString($value) . ";";

		Core_File::write($path, $content);

		return $this;
	}
	
	/**
	 * Replace config without changing config file
	 * @param string $name Config name, e.g. 'Core_Myconfig' set modules/core/config/myconfig.php
	 * @param array $value Config value, e.g. array('key' => 'value')
	 * @return Core_Config
	 */
	public function replace($name, $value)
	{
		$this->_values[$name] = $value;
		return $this;
	}
}