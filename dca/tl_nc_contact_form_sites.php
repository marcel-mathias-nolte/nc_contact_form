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
 * Table tl_nc_contact_form_sites
 */
$GLOBALS['TL_DCA']['tl_nc_contact_form_sites'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'switchToEdit'                => false,
		'ctable'                      => array('tl_nc_contact_form'),
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
			'panelLayout'             => 'search,filter,limit'
		),
		'label' => array
		(
			'fields'                  => array('title', 'id'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>'
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
			'comments' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['comments'],
				'href'                => 'table=tl_nc_contact_form',
				'icon'                => 'system/modules/nc_contact_form/assets/gb.gif'
			),
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	
	// Palettes
	'palettes' => array
	(
		'default'                     => 'title,mail_template,subject'
	),
	
	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => 'int(10) unsigned NOT NULL auto_increment'
		),
		'tstamp' => array
		(
			'sql'                     => 'int(10) unsigned NOT NULL default \'0\''
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['title'],
			'sorting'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>250, 'tl_class'=>'w50'),
			'sql'                     => 'varchar(255) NOT NULL default \'\''
		),
		'mail_template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['mail_template'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('NC\NcContactForm', 'getMailTemplates'),
			'sql'                     => 'varchar(64) NOT NULL default \'\''
		),
		'subject' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_sites']['subject'],
			'sorting'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>250, 'tl_class'=>'long'),
			'sql'                     => 'varchar(255) NOT NULL default \'\''
		)
	)
);

?>