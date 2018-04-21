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

require_once('elements_helper.php');

jimport('joomla.form.formfield');

class JFormFieldinternationaltelephone extends JFormField {
	
	protected $type = 'internationaltelephone';

	/**
	 *  Document object
	 *
	 *  @var  object
	 */
	public $doc;

	/**
	 *  Database object
	 *
	 *  @var  object
	 */
	public $db;

	/**
	 *  Application Object
	 *
	 *  @var  object
	 */
	protected $app;

	/**
	 *  Class constructor
	 */
	function __construct()
	{
		$this->doc = JFactory::getDocument();
		$this->app = JFactory::getApplication();
		$this->db = JFactory::getDbo();

		parent::__construct();
	}
	
	public function getInput()
	{
		// Setup properties
		$this->hint      = $this->get('hint', '00:00');
		$this->class     = $this->get('class', "input-mini");
		$this->placement = $this->get('placement', "top");
		$this->align     = $this->get('align', "left");
		$this->autoclose = $this->get('autoclose', "true");
		$this->default   = $this->get('default', "now");
		$this->donetext  = $this->get('donetext', "Done");

		// Add styles and scripts to DOM
		JHtml::_('jquery.framework');

		// intlTelInput
		$this->doc->addStyleSheet(JURI::root(true).'/plugins/system/web357framework/elements/assets/internationaltelephone/intl-tel-input-12.1.0/css/demo.css');
		$this->doc->addStyleSheet(JURI::root(true).'/plugins/system/web357framework/elements/assets/internationaltelephone/intl-tel-input-12.1.0/css/intlTelInput.css');
		$this->doc->addScript(JURI::root(true).'/plugins/system/web357framework/elements/assets/internationaltelephone/intl-tel-input-12.1.0/js/intlTelInput.js');
		$this->doc->addScriptDeclaration('
			jQuery(function($) {
				var input = $("#'.$this->id.'");

				input.intlTelInput({
					nationalMode: true,
					preferredCountries: ["gr", "cy", "gb", "us"],
					utilsScript: "'.JURI::root(true).'/plugins/system/web357framework/elements/assets/internationaltelephone/intl-tel-input-12.1.0/js/utils.js"
				});

				// listen to "keyup", but also "change" to update when the user selects a country
				input.on("keyup change", function() {
				var intlNumber = input.intlTelInput("getNumber");
				if (intlNumber) {
					$("#jform_tel").val(intlNumber);
				} 
				});
  
			});
		');
			
		static $run;
		// Run once to initialize it
		if (!$run)
		{
			$this->doc->addScriptDeclaration('
				jQuery(function($) {
					$(".clockpicker").clockpicker();
				});
        	');

			// Fuck you template.css
			$this->doc->addStyleDeclaration('
				.clockpicker-align-left.popover > .arrow {
				    left: 25px;
				}
			');

			$run = true;
		}

		$html = '';
		$html .= '<input id="'.$this->id.'" name="'.$this->name.'" type="tel" style="height:100%;" value="'.$this->value.'"> ';

		return $html;
	
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		$label = $this->get("label");
		if (empty($label))
		{
			return "";
		}

		return parent::getLabel();
	}

	/**
	 *  Prepares string through JText
	 *
	 *  @param   string  $string
	 *
	 *  @return  string
	 */
	public function prepareText($string = '')
	{
		$string = trim($string);

		if ($string == '')
		{
			return '';
		}

		return JText::_($string);
	}

	/**
	 *  Method to get field parameters
	 *
	 *  @param   string  $val      Field parameter
	 *  @param   string  $default  The default value
	 *
	 *  @return  string
	 */
	public function get($val, $default = '')
	{
		return (isset($this->element[$val]) && (string) $this->element[$val] != '') ? (string) $this->element[$val] : $default;
	}
}