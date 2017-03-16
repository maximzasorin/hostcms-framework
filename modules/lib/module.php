<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Libs.
 *
 * @package HostCMS 6\Lib
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Lib_Module extends Core_Module{	/**
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
		$this->menu = array(			array(				'sorting' => 90,				'block' => 0,				'name' => Core::_('lib.menu'),				'href' => "/admin/lib/index.php",				'onclick' => "$.adminLoad({path: '/admin/lib/index.php'}); return false"			)		);	}}