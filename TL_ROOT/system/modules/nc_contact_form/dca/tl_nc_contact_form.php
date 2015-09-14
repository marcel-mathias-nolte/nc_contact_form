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
 * Table tl_nc_contact_form
 */
$GLOBALS['TL_DCA']['tl_nc_contact_form'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
        'switchToEdit'                => false,
		'ctable'                      => array('tl_nc_contact_form_messages'),
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
            'mode'                    => 2,
			'flag'                    => 1,
			'fields'                  => array('title'),
			'panelLayout'             => 'filter,limit'
        ),
        'label' => array
        (
			'fields'                  => array('id'),
            'label_callback'          => array('tl_nc_contact_form', 'getLabel')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array
        (
            'messages' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['messages'],
				'href'                => 'table=tl_nc_contact_form_messages',
                'icon'                => 'system/modules/nc_contact_form/assets/icon.gif'
            ),
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
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
		'default'                     => '{title_legend},title;',
	),


	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
			'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		)
	)
);


/**
 * Class tl_nc_contact_form
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright Marcel Mathias Nolte 2013
 * @author    Marcel Mathias Nolte
 * @package   NC Contact Form
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
	 * Generate item label
	 * @return array
	 */
	public function getLabel($arrRow)
	{
		$count = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_nc_contact_form_messages WHERE pid=? AND messageRead=''")->execute($arrRow['id'])->next()->count;
		return sprintf('%s (%u)<span style="color:#b3b3b3; padding-left:3px;">[%u]</span>', $arrRow['title'], $count, $arrRow['id']);
	}

}