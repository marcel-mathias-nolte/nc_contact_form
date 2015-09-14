<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   NC Contact Form
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 * @website	  https://www.noltecomputer.com
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff 
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */


/**
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'nc_contact_form_mail_admin';

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'nc_contact_form_mail_user';

$GLOBALS['TL_DCA']['tl_module']['palettes']['ncContactForm'] = '{title_legend},name,headline,type;{config_legend},disableCaptcha,nc_contact_form,nc_contact_form_template,nc_contact_form_use_feuser,nc_contact_form_fields,nc_contact_form_mail_admin,nc_contact_form_mail_user;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['nc_contact_form_mail_admin'] = 'nc_contact_form_mail_admin_original_sender,nc_contact_form_mail_admin_address,nc_contact_form_mail_admin_subject,nc_contact_form_mail_admin_text';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['nc_contact_form_mail_user'] = 'nc_contact_form_mail_user_original_sender,nc_contact_form_mail_user_subject,nc_contact_form_mail_user_text';

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_fields'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('name', 'gender', 'firstname', 'lastname', 'street', 'postal', 'city', 'callRequest', 'phone', 'email', 'message'),
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

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_use_feuser'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_use_feuser'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_admin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_admin_original_sender'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin_original_sender'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_admin_address'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin_address'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
	'load_callback' => array
	(
		array('tl_module_nc_contact_form', 'getDefaultAdminAddress')
	),
	'sql'                     => "varchar(255) COLLATE utf8_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_admin_subject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin_subject'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
	'load_callback' => array
	(
		array('tl_module_nc_contact_form', 'getDefaultAdminSubject')
	),
	'sql'                     => "varchar(255) COLLATE utf8_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_admin_text'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin_text'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' => array
	(
		array('tl_module_nc_contact_form', 'getDefaultAdminText')
	),
	'sql'                     => "text NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_user'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_user'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_user_original_sender'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_user_original_sender'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_user_subject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_user_subject'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
	'load_callback' => array
	(
		array('tl_module_nc_contact_form', 'getDefaultUserSubject')
	),
	'sql'                     => "varchar(255) COLLATE utf8_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nc_contact_form_mail_user_text'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_user_text'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' => array
	(
		array('tl_module_nc_contact_form', 'getDefaultUserText')
	),
	'sql'                     => "text NULL"
);

/**
 * Class tl_module_nc_contact_form
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright Marcel Mathias Nolte 2015
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


	/**
	 * Load the default recipient address
	 *
	 * @param mixed $varValue
	 *
	 * @return mixed
	 */
	public function getDefaultAdminAddress($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		return $varValue;
	}


	/**
	 * Load the default mail subject for the admin mail
	 *
	 * @param mixed $varValue
	 *
	 * @return mixed
	 */
	public function getDefaultAdminSubject($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin_subject']['default'];
		}

		return $varValue;
	}


	/**
	 * Load the default mail text for the admin mail
	 *
	 * @param mixed $varValue
	 *
	 * @return mixed
	 */
	public function getDefaultAdminText($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_admin_text']['default'];
		}

		return $varValue;
	}


	/**
	 * Load the default mail subject for the user mail
	 *
	 * @param mixed $varValue
	 *
	 * @return mixed
	 */
	public function getDefaultUserSubject($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_user_subject']['default'];
		}

		return $varValue;
	}


	/**
	 * Load the default mail text for the user mail
	 *
	 * @param mixed $varValue
	 *
	 * @return mixed
	 */
	public function getDefaultUserText($varValue)
	{
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_module']['nc_contact_form_mail_user_text']['default'];
		}

		return $varValue;
	}
}