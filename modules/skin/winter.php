<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Winter skin.
 *
 * @package HostCMS 6\Skin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Winter extends Skin_Default
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addCss('/modules/skin/winter/css/style.css');
	}

	/**
	 * Get image href
	 * @return string
	 */
	public function getImageHref()
	{
		return "/modules/skin/default/images/";
	}
}