<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   NC Contact Form
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2013
 * @website   https://www.noltecomputer.com
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff 
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */


/**
 * Table tl_nc_contact_form
 */
$GLOBALS['TL_DCA']['tl_nc_contact_form'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'                      => true,
		'onsubmit_callback' => array
		(
			array('tl_nc_contact_form', 'storeDateAdded')
		),
        'ptable'                      => 'tl_nc_contact_form_sites',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
            'mode'                    => 4,
			'flag'                    => 8,
			'fields'                  => array('date'),
			'panelLayout'             => 'search,filter,limit',
			'headerFields'            => array('title'),
			'child_record_callback'   => array('tl_nc_contact_form', 'listContacts')
		),
		'label' => array
		(
			'fields'                  => array('name', 'email', 'date'),
			'format'                  => '%s <%s> am %s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{sender_legend},name,email,phone,message;{data_legend:hide},date,ip',
	),


	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => 'int(10) unsigned NOT NULL auto_increment'
		),
		'pid' => array
		(
			'sql'                     => 'int(10) unsigned NOT NULL default \'0\''
		),
		'tstamp' => array
		(
			'sql'                     => 'int(10) unsigned NOT NULL default \'0\''
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'                     => 'varchar(255) NOT NULL default \'\''
		),
		'phone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['phone'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'phone', 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'                     => 'varchar(255) NOT NULL default \'\''
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'decodeEntities'=>true, 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'                     => 'varchar(255) NOT NULL default \'\''
		),
		'message' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['message'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'feEditable'=>true, 'tl_class'=>'clr'),
			'sql'                     => 'text NULL'
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['date'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>17, 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'                     => 'varchar(17) NOT NULL default \'\''
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['ip'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>15, 'feEditable'=>true, 'tl_class'=>'w50'),
			'sql'                     => 'varchar(15) NOT NULL default \'\''
		),
	)
);


/**
 * Class tl_nc_contact_form
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_nc_contact_form extends Backend
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
	 * Store the date when the message has been added
	 * @param DataContainer
	 */
	public function storeDateAdded(DataContainer $dc)
	{
		// Return if there is no active record (override all)
		if (!$dc->activeRecord || !empty($dc->activeRecord->date))
		{
			return;
		}
		$this->Database->prepare("UPDATE tl_nc_contact_form SET date=? WHERE id=?")->execute(date("d.m.Y H:i"), $dc->id);
	}
	
	/**
	 * Get the item row
	 * @param array
	 * @return string
	 */
	public function listContacts($arrRow)
	{
		return sprintf("%s <%s> am %s", $arrRow['name'], $arrRow['email'], $arrRow['date']);
	}
}

?>