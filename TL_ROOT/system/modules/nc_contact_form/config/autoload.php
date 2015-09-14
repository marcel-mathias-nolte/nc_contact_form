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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'NC',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'NC\\ModuleNcContactFormHelper'   => 'system/modules/nc_contact_form/classes/ModuleNcContactFormHelper.php',
	// Modules
	'NC\\ModuleNcContactForm'         => 'system/modules/nc_contact_form/modules/ModuleNcContactForm.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nc_contact_form_default'         => 'system/modules/nc_contact_form/templates/nc_contact_form',
));