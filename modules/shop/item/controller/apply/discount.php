<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Shop_Item_Controller_Apply_Discount
 *
 * @package HostCMS
 * @subpackage Admin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2016 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Item_Controller_Apply_Discount extends Admin_Form_Action_Controller
{
	/**
	 * Allowed object properties
	 * @var array
	 */
	protected $_allowedProperties = array(
		'title',
		'Shop',
		'buttonName',
		'skipColumns'
	);

	/**
	 * Constructor.
	 * @param Admin_Form_Action_Model $oAdmin_Form_Action action
	 */
	public function __construct(Admin_Form_Action_Model $oAdmin_Form_Action)
	{
		parent::__construct($oAdmin_Form_Action);

		$this->buttonName(Core::_('Admin_Form.apply'));
	}

	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 * @return self
	 */
	public function execute($operation = NULL)
	{
		if (is_null($operation))
		{
			// Original windowId
			$windowId = $this->_Admin_Form_Controller->getWindowId();

			$newWindowId = 'Apply_Discount_' . time();

			$oCore_Html_Entity_Form = Core::factory('Core_Html_Entity_Form');

			$oCore_Html_Entity_Div = Core::factory('Core_Html_Entity_Div')
				->id($newWindowId)
				->add($oCore_Html_Entity_Form);

			$oCore_Html_Entity_Form
				->action($this->_Admin_Form_Controller->getPath())
				->method('post');

			$window_Admin_Form_Controller = clone $this->_Admin_Form_Controller;

			// Select на всплывающем окне должен быть найден через ID нового окна, а не id_content
			$window_Admin_Form_Controller->window($newWindowId);

			// Получение списка скидок
			$aDiscounts = array(" … ");
			$aShop_Discounts = $this->Shop->Shop_Discounts->findAll(FALSE);
			foreach($aShop_Discounts as $oShop_Discount)
			{
				$aDiscounts[$oShop_Discount->id] = $oShop_Discount->name;
			}

			$oAdmin_Form_Entity_Select_Discount = Admin_Form_Entity::factory('Select')
				->name('discount_id')
				->id('discountId')
				->style('width: 280px; float: left')
				->filter(TRUE)
				->options($aDiscounts)
				->caption(Core::_('Shop_Item.discount_select_caption'))
				->controller($window_Admin_Form_Controller);

			$oAdmin_Form_Entity_Select_Discount_Checkbox = Admin_Form_Entity::factory('Checkbox')
				->name('flag_delete_discount')
				->caption(Core::_('Shop_Item.flag_delete_discount'));

			$oCore_Html_Entity_Form
				->add($oAdmin_Form_Entity_Select_Discount)
				->add($oAdmin_Form_Entity_Select_Discount_Checkbox);
				
			if (Core::moduleIsActive('siteuser'))
			{
				$aBonuses = array(" … ");
				$aShop_Bonuses = $this->Shop->Shop_Bonuses->findAll(FALSE);
				foreach($aShop_Bonuses as $oShop_Bonus)
				{
					$aBonuses[$oShop_Bonus->id] = $oShop_Bonus->name;
				}

				$oAdmin_Form_Entity_Select_Bonus = Admin_Form_Entity::factory('Select')
					->name('bonus_id')
					->id('bonusId')
					->style('width: 280px; float: left')
					->filter(TRUE)
					->options($aBonuses)
					->caption(Core::_('Shop_Item.bonus_select_caption'))
					->controller($window_Admin_Form_Controller);

				$oAdmin_Form_Entity_Select_Bonus_Checkbox = Admin_Form_Entity::factory('Checkbox')
					->name('flag_delete_bonus')
					->caption(Core::_('Shop_Item.flag_delete_bonus'));
					
				$oCore_Html_Entity_Form
					->add($oAdmin_Form_Entity_Select_Bonus)
					->add($oAdmin_Form_Entity_Select_Bonus_Checkbox);
			}

			// Идентификаторы переносимых указываем скрытыми полями в форме, чтобы не превысить лимит GET
			$aChecked = $this->_Admin_Form_Controller->getChecked();

			// Clear checked list
			$this->_Admin_Form_Controller->clearChecked();

			foreach ($aChecked as $datasetKey => $checkedItems)
			{
				foreach ($checkedItems as $key => $value)
				{
					$oCore_Html_Entity_Form->add(
						 Core::factory('Core_Html_Entity_Input')
							->name('hostcms[checked][' . $datasetKey . '][' . $key . ']')
							->value(1)
							->type('hidden')
					);
				}
			}

			$oAdmin_Form_Entity_Button = Admin_Form_Entity::factory('Button')
				->name('apply')
				->type('submit')
				->class('applyButton btn btn-blue')
				->value($this->buttonName)
				->onclick(
					'$("#' . $newWindowId . '").remove(); '
					. $this->_Admin_Form_Controller->getAdminSendForm(NULL, 'apply')
				)
				->controller($this->_Admin_Form_Controller);

			$oCore_Html_Entity_Form
				->add(
					Admin_Form_Entity::factory('Div')
						->class('form-group col-xs-12')
						->add($oAdmin_Form_Entity_Button)
				);

			$oCore_Html_Entity_Div->execute();

			ob_start();

			Core::factory('Core_Html_Entity_Script')
				->type("text/javascript")
				->value("$(function() {
				$('#{$newWindowId}').HostCMSWindow({ autoOpen: true, destroyOnClose: false, title: '" . $this->title . "', AppendTo: '#{$windowId}', width: 750, height: 260, addContentPadding: true, modal: false, Maximize: false, Minimize: false }); });")
				->execute();

			$this->addMessage(ob_get_clean());

			// Break execution for other
			return TRUE;
		}
		else
		{
			$iDiscountID = Core_Array::getPost('discount_id');
			$iBonusID = Core_Array::getPost('bonus_id');

			$oShop_Item = $this->_object;

			if ($iDiscountID)
			{
				$oShop_Discount = Core_Entity::factory('Shop_Discount', $iDiscountID);

				if (!is_null(Core_Array::getPost('flag_delete_discount')))
				{
					$oShop_Item->remove($oShop_Discount);
				}
				else
				{
					// Устанавливаем скидку товару
					is_null($oShop_Item->Shop_Item_Discounts->getByShop_discount_id($iDiscountID))
						&& $oShop_Item->add($oShop_Discount);
				}
			}

			if (Core::moduleIsActive('siteuser') && $iBonusID)
			{
				$oShop_Bonus = Core_Entity::factory('Shop_Bonus', $iBonusID);

				if (!is_null(Core_Array::getPost('flag_delete_bonus')))
				{
					$oShop_Item->remove($oShop_Bonus);
				}
				else
				{
					// Устанавливаем бонус товару
					$oShop_Item->add($oShop_Bonus)
						&& is_null($oShop_Item->Shop_Item_Bonuses->getByShop_bonus_id($iBonusID));
				}
			}
		}

		return $this;
	}
}