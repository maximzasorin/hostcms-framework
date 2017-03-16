<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Core command controller.
 *
 * @package HostCMS 6\Core\Command
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Command_Controller_Xslt extends Core_Command_Controller
{
	/**
	 * Default controller action
	 * @return Core_Response
	 * @hostcms-event Core_Command_Controller_Template_Not_Found.onBeforeShowAction
	 * @hostcms-event Core_Command_Controller_Template_Not_Found.onAfterShowAction
	 */
	public function showAction()
	{
		Core_Event::notify(get_class($this) . '.onBeforeShowAction', $this);

		$oCore_Response = new Core_Response();

		$oCore_Response
			->status(503)
			->header('Content-Type', "text/html; charset=UTF-8")
			->header('Last-Modified', gmdate('D, d M Y H:i:s', time()) . ' GMT')
			->header('X-Powered-By', 'HostCMS');

		$title = 'Несоответствие хостинга системным требованиям!';

		ob_start();
		$oSkin = Core_Skin::instance()
			->title($title)
			->header();

		Core::factory('Core_Html_Entity_Div')
			->id('indexMessage')
			->add(Core::factory('Core_Html_Entity_H1')->value($title))
			->add(Core::factory('Core_Html_Entity_P')->value(
				'Для работы системы управления сайтом HostCMS необходим один PHP 5 с установленной поддержкой <a href="http://www.hostcms.ru/documentation/libxslt/" target="_blank">Libxslt</a>.'
			))
			->add(Core::factory('Core_Html_Entity_P')->value(
				'На нашем сайте также размещен <a href="http://www.hostcms.ru/hosting/" target="_blank">список протестированных хостингов</a>, подходящих для работы HostCMS.'
			))
			->execute();

		$oSkin->footer();

		$oCore_Response->body(ob_get_clean());

		Core_Event::notify(get_class($this) . '.onAfterShowAction', $this, array($oCore_Response));

		return $oCore_Response;
	}
}