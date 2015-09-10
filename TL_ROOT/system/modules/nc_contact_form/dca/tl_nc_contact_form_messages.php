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
 * Table tl_nc_contact_form_messages
 */
$GLOBALS['TL_DCA']['tl_nc_contact_form_messages'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'closed'                      => true,
		'notEditable'                 => true,
        'ptable'                      => 'tl_nc_contact_form',
		'onsubmit_callback' => array
		(
			array('tl_nc_contact_form_messages', 'storeDateAdded')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
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
			'fields'                  => array('tstamp'),
			'panelLayout'             => 'search,filter,limit',
			'headerFields'            => array('title'),
			'child_record_callback'   => array('tl_nc_contact_form_messages', 'listMessages')
        ),
        'label' => array
        (
            'fields'                  => array('name', 'email', 'date'),
            'format'                  => '%s&lt;%s&gt; am %s'
        ),
        'global_operations' => array(),
        'operations' => array
        (
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['show'],
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
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_nc_contact_form.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'phone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['phone'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'rgxp'=>'phone', 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'unique'=>true, 'decodeEntities'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'message' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['message'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:60px;', 'feEditable'=>true, 'feViewable'=>true, 'tl_class'=>'clr'),
			'sql'                     => "mediumtext NULL"
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['date'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>17, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['ip'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>15, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
	)
);


/**
 * Class tl_nc_contact_form_messages
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright Marcel Mathias Nolte 2013
 * @author    Marcel Mathias Nolte
 * @package   NC Contact Form
 */
class tl_nc_contact_form_messages extends Backend
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
	 * Store the date when the account has been added
	 * @param DataContainer
	 */
	public function storeDateAdded(DataContainer $dc)
	{
		// Return if there is no active record (override all)
		if (!$dc->activeRecord || !empty($dc->activeRecord->date))
		{
			return;
		}

		$this->Database->prepare("UPDATE tl_nc_contact_form_messages SET date=? WHERE id=?")
					   ->execute(date("d.m.Y H:i"), $dc->id);
	}
	

	/**
	 * Return all surveys as array
	 * @return array
	 */
	public function listMessages($arrRow)
	{
		return sprintf("%s&lt;%s&gt; am %s", $arrRow['name'], $arrRow['email'], $arrRow['date']);
	}
}