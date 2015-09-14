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
			'child_record_callback'   => array('tl_nc_contact_form_messages', 'getLabel')
        ),
        'label' => array
        (
            'fields'                  => array('name', 'email', 'date'),
            'format'                  => '%s&lt;%s&gt; am %s'
        ),
        'global_operations' => array(),
        'operations' => array
        (
			'markMessageRead' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['markMessageRead'],
				'icon'                => 'system/modules/nc_contact_form/assets/unread.png',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleNcContactFormRead(this,%s)"',
				'button_callback'     => array('tl_nc_contact_form_messages', 'getReadIcon')
			),
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
		'default'                     => '{sender_legend},name,gender,firstname,lastname,street,postal,city,callRequest,email,phone,message;{data_legend:hide},date,ip',
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
		'gender' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('male', 'female'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender'],
			'eval'                    => array('includeBlankOption'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'firstname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['firstname'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lastname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['lastname'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'street' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['street'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'postal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['postal'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>32, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'city' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['city'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'callRequest' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['callRequest'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr', 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'address'),
			'sql'                     => "char(1) NOT NULL default ''"
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
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>17, 'tl_class'=>'w50'),
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
			'eval'                    => array('mandatory'=>true, 'maxlength'=>15, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'messageRead' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['messageRead'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);



/**
 * Class tl_nc_contact_form_messages
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright Marcel Mathias Nolte 2015
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
	 * Return the item label
	 * @return array
	 */
	public function getLabel($arrRow)
	{
		$token = array(
			'###contact_form###' => $this->Database->prepare("SELECT title FROM tl_nc_contact_form WHERE id=?")->execute($arrRow['pid'])->next()->title,
			'###fmt###' => !$arrRow['messageRead'] ? 'strong' : 'i'
		);
		foreach ($arrRow as $key => $value) {
			$token['###' . $key . '###'] = $value;
		}
		return strtr($GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['label'], $token);
	}


	/**
	 * Return the "toggle read" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function getReadIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (!$this->User->hasAccess('tl_nc_contact_form_messages::messageRead', 'alexf'))
		{
			return '';
		}
		$href .= '&amp;item='.$row['id'].'&amp;read_state='.$row['messageRead'];
		if ($row['messageRead'])
		{
			$icon = 'system/modules/nc_contact_form/assets/read.png';
		}
		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}
}