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
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['nc_contact_form'] = '{type_legend},type,headline;{config_legend},nc_contact_form_site,nc_contact_form_template,nc_contact_form_disable_captcha;{redirect_legend},nc_contact_form_jump_to;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_contact_form_site'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_contact_form_site'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('NC\NcContactForm', 'getForms'),
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
	'sql'                     => 'int(10) NOT NULL default \'0\''
);	
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_contact_form_disable_captcha'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_contact_form_disable_captcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'sql'                     => 'char(1) NOT NULL default \'\''
);
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_contact_form_jump_to'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_contact_form_jump_to'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => 'int(10) unsigned NOT NULL default \'0\'',
	'relation'                => array('type'=>'hasOne', 'load'=>'eager')
);
$GLOBALS['TL_DCA']['tl_content']['fields']['nc_contact_form_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['nc_contact_form_template'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('NC\NcContactForm', 'getTemplates'),
	'sql'                     => 'varchar(64) NOT NULL default \'\''
);

?>