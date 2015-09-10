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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'NC',
));

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