<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Comment Module.
 *
 * @package HostCMS
 * @subpackage Comment
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2017 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Comment_Module extends Core_Module
{
	/**
	 * Module version
	 * @var string
	 */
	public $version = '6.7';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2017-12-25';

	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'comment';
}