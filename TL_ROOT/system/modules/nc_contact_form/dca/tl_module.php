<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   NC Contact Form
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2013
 * @website	  https://www.noltecomputer.com
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff 
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */


/**
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['ncContactForm'] = '{title_legend},name,headline,type;{config_legend},disableCaptcha,nc_contact_form,nc_contact_form_template,nc_contact_form_fields;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_fields'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('name', 'phone', 'email', 'message'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_fields'],
	'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_nc_contact_form', 'getContactForms'),
	'eval'                    => array('tl_class'=>'w50', 'mandatory' => true),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_template'],
	'default'                 => 'nc_contact_form_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_nc_contact_form', 'getContactFormTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

/**
 * Class tl_module_nc_contact_form
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright Marcel Mathias Nolte 2013
 * @author    Marcel Mathias Nolte
 * @package   NC Contact Form
 */
class tl_module_nc_contact_form extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Get all news archives and return them as array
	 *
	 * @return array
	 */
	public function getContactForms()
	{
		if (!$this->User->isAdmin && !is_array($this->User->news))
		{
			return array();
		}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_nc_contact_form ORDER BY title");

		while ($objArchives->next())
		{
			if ($this->User->hasAccess($objArchives->id, 'nc_contact_form'))
			{
				$arrArchives[$objArchives->id] = $objArchives->title;
			}
		}

		return $arrArchives;
	}


	/**
	 * Return all news templates as array
	 *
	 * @return array
	 */
	public function getContactFormTemplates()
	{
		return $this->getTemplateGroup('nc_contact_form_');
	}
}