<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Information systems.
 *
 * @package HostCMS 6\Informationsystem
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2012 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Informationsystem_Item_Comment_Controller_Edit extends Comment_Controller_Edit{
	/**
	 * Processing of the form. Apply object fields.
	 */	protected function _applyObjectProperty()	{		parent::_applyObjectProperty();		$Comment_Informationsystem_Item = $this->_object->Comment_Informationsystem_Item;		if (is_null($Comment_Informationsystem_Item->id))		{			$Comment_Informationsystem_Item->informationsystem_item_id = intval(Core_Array::getGet('informationsystem_item_id'));			$Comment_Informationsystem_Item->save();		}	}}