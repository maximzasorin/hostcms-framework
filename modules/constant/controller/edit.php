<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Constants.
 *
 * @package HostCMS 6\Constant
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Constant_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		parent::setObject($object);
		$modelName = $this->_object->getModelName();
		$oMainTab = $this->getTab('main');
		
		switch($modelName)
		{
			case 'constant':
			
				if (is_null($this->_object->id))
				{
					$this->_object->constant_dir_id = Core_Array::getGet('constant_dir_id');
				}
				
				// Удаляем группу товаров
				$oMainTab->delete($this->getField('constant_dir_id'));

				$oGroupSelect = new Admin_Form_Entity_Select();
				$oGroupSelect->caption(Core::_('Constant_Dir.parent_id'))
					->options(array(' … ') + $this->fillDir(0))
					->name('constant_dir_id')
					->value($this->_object->constant_dir_id)
					->style('width:300px; float:left')
					->filter(TRUE);

				// Добавляем группу товаров
				$oMainTab->addAfter($oGroupSelect, $this->getField('name'));
			
				$this->title(
					$this->_object->id
						? Core::_('Constant.edit_title', $object->name)
						: Core::_('Constant.add_title')
					);

				$this->getField('value')
					->format(
						array(
							'minlen' => array('value' => 1)
						)
					);
			break;
			case 'constant_dir':
				if (is_null($this->_object->id))
				{
					$this->_object->parent_id = Core_Array::getGet('constant_dir_id');
				}
				
				$oAdditionalTab = $this->getTab('additional');
				
				// Удаляем группу товаров
				$oAdditionalTab->delete($this->getField('parent_id'));

				$oGroupSelect = new Admin_Form_Entity_Select();
				$oGroupSelect->caption(Core::_('Constant_Dir.parent_id'))
					->options(array(' … ') + $this->fillDir(0))
					->name('parent_id')
					->value($this->_object->parent_id)
					->style('width:300px; float:left')
					->filter(TRUE);

				// Добавляем группу товаров
				$oMainTab->addAfter($oGroupSelect, $this->getField('name'));
				
				$this->title(
					$this->_object->id
						? Core::_('Constant_Dir.edit')
						: Core::_('Constant_Dir.add')
					);
			
			break;
		}

		return $this;
	}
	
	/**
	 * Create visual tree of the directories
	 * @param int $parent_id parent directory ID
	 * @param boolean $bExclude exclude group ID
	 * @param int $iLevel current nesting level
	 * @return array
	 */
	public function fillDir($parent_id, $bExclude = FALSE, $iLevel = 0)
	{
		$parent_id = intval($parent_id);
		$iLevel = intval($iLevel);

		$oDir = Core_Entity::factory('Constant_Dir', $parent_id);

		$aResult = array();

		// Дочерние разделы
		$aChildrenDirs = $oDir->Constant_Dirs->findAll();

		if (count($aChildrenDirs))
		{
			foreach ($aChildrenDirs as $oChildrenDir)
			{
				if ($bExclude != $oChildrenDir->id)
				{
					$aResult[$oChildrenDir->id] = str_repeat('  ', $iLevel) . $oChildrenDir->name;
					$aResult += $this->fillDir($oChildrenDir->id, $bExclude, $iLevel+1);
				}
			}
		}

		return $aResult;
	}

	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 * @return self
	 */
	public function execute($operation = NULL)
	{
		if (!is_null($operation))
		{
			$name = Core_Array::getRequest('name');
			$id = Core_Array::getRequest('id');
			$oSameConstant = Core_Entity::factory('Constant')->getByName($name);

			if (!is_null($oSameConstant) && $oSameConstant->id != $id)
			{
				$this->addMessage(
					Core_Message::get(Core::_('Constant.add_error'))
				);
				return TRUE;
			}
		}

		return parent::execute($operation);
	}
}