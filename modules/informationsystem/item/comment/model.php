<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Information systems.
 *
 * @package HostCMS 6\Informationsystem
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Informationsystem_Item_Comment_Model extends Comment_Model{
	/**
	 * Name of the table
	 * @var string
	 */	protected $_tableName = 'comments';

	/**
	 * Name of the model
	 * @var string
	 */	protected $_modelName = 'comment';
	/**
	 * Callback function
	 */	public function view()	{		ob_start();		$oInformationsystem_Item = $this->Comment_Informationsystem_Item->Informationsystem_Item;
		$href = $oInformationsystem_Item->Informationsystem->Structure->getPath() . $oInformationsystem_Item->getPath();

		$oSite = $oInformationsystem_Item->Informationsystem->Site;
		$oSite_Alias = $oSite->getCurrentAlias();
		!is_null($oSite_Alias) && $href = 'http://' . $oSite_Alias->name . $href;

		Core::factory('Core_Html_Entity_A')			->href($href)			->target('_blank')			->add(				Core::factory('Core_Html_Entity_Img')					->src('/admin/images/comment_view.gif')			)			->execute();		return ob_get_clean();	}

	/**
	 * Copy object
	 * @return Core_Entity
	 */
	public function copy()
	{		$newObject = parent::copy();		$aNewComment_Informationsystem_Item = clone $this->Comment_Informationsystem_Item;		$newObject->add($aNewComment_Informationsystem_Item);		return $newObject;	}}