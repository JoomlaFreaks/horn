<?php
/* ======================================================
# Web357 Framework - Joomla! System Plugin v1.5.2
# -------------------------------------------------------
# For Joomla! 3.x
# Author: Yiannis Christodoulou (yiannis@web357.eu)
# Copyright (©) 2009-2018 Web357. All rights reserved.
# License: GNU/GPLv3, http://www.gnu.org/licenses/gpl-3.0.html
# Website: https://www.web357.eu/
# Support: support@web357.eu
# Last modified: 28 Feb 2018, 17:31:50
========================================================= */

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldVmmanufacturersp extends JFormField
{
	var $type = 'vmmanufacturersp';

    function getInput() 
    {
		if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		$model = VmModel::getModel('Manufacturer');
		$manufacturers = $model->getManufacturers(true, true, false);
		return JHtml::_('select.genericlist', $manufacturers, $this->name, 'class="inputbox"  size="1" multiple="multiple"', 'virtuemart_manufacturer_id', 'mf_name', $this->value, $this->id);
	}
}