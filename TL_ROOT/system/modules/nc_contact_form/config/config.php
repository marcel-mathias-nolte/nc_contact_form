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
 * Back end modules
 */
if (!isset($GLOBALS['BE_MOD']['messages'])) {
	array_insert($GLOBALS['BE_MOD'], 2, array('messages' => array()));
}
$GLOBALS['BE_MOD']['messages']['ncContactForm'] = array(
	'tables' => array('tl_nc_contact_form', 'tl_nc_contact_form_messages'),
	'icon' => 'system/modules/nc_contact_form/assets/icon.gif'
);

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['forms']['ncContactForm'] = 'NC\\ModuleNcContactForm';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getNotificationLabel']['tl_nc_contact_form_messages'] = array('ModuleNcContactFormHelper', 'getNotificationLabel');

if (isset($_REQUEST['table']) && $_REQUEST['table'] == 'tl_nc_contact_form_messages') 
{
	if (isset($_REQUEST['read_state'])) 
	{
    	ModuleNcContactFormHelper::getInstance()->toggleRead((int)$_REQUEST['item'], (int)$_REQUEST['read_state'] > 0);
	}
	if (TL_MODE == 'BE')
	{
   		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/nc_contact_form/assets/backend.js';
	}
}