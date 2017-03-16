<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Modules.
 *
 * @package HostCMS 6\Module
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Module_Module extends Core_Module{	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.1';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2013-06-19';
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 220,				'block' => 3,				'name' => Core::_('Module.menu'),				'href' => "/admin/module/index.php",				'onclick' => "$.adminLoad({path: '/admin/module/index.php'}); return false"			)		);
	}}