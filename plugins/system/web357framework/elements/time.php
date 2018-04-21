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

class JFormFieldtime extends JFormField {
	
	protected $type = 'time';

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
		$this->doc->addStyleSheet(JURI::root(true).'/plugins/system/web357framework/elements/assets/time/css/jquery-clockpicker.min.css');
		$this->doc->addScript(JURI::root(true) . '/plugins/system/web357framework/elements/assets/time/js/jquery-clockpicker.min.js');

		static $run;
		// Run once to initialize it
		if (!$run)
		{
			$this->doc->addScriptDeclaration('
				jQuery(function($) {
					$(".clockpicker").clockpicker();
				});
        	');

			// Fix in template.css
			$this->doc->addStyleDeclaration('
				.clockpicker-align-left.popover > .arrow {
				    left: 25px;
				}
			');

			$run = true;
		}

		$html = '';
		$html .= '<div class="input-group input-append clockpicker" data-donetext="'.$this->donetext.'" data-default="'.$this->default.'" data-placement="'.$this->placement.'" data-align="'.$this->align.'" data-autoclose="'.$this->autoclose.'">
		<input class="'.$this->class.'" placeholder="'.$this->hint.'" name="' . $this->name . '" type="text" class="form-control" value="' . $this->value . '"><span class="add-on"><span class="icon-clock">&nbsp;</span></span></div>';

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