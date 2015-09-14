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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['name']             = array('Full name', 'Please enter the full name.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['phone']            = array('Phone number', 'Please enter the phone number.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['email']            = array('E-mail address', 'Please enter a valid e-mail address.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender']           = array('Salutation', 'Please select the salutation.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender']['male']   = 'Mr.';
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender']['female'] = 'Mrs.';
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['firstname']        = array('First name', 'Please enter the first name.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['lastname']         = array('Last name', 'Please enter the last name.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['street']           = array('Street', 'Please enter the street name and number.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['postal']           = array('Postal code', 'Please enter the postal code.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['city']             = array('City', 'Plase enter the name of the city.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['callRequest']      = array('Call requested', 'Wheter a call is requested.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['message']          = array('Message', 'Please enter a message.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['date']             = array('Date', '');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['ip']               = array('IP address', '');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['messageRead']      = array('Message read', 'Wheter the message was read.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['sender_legend']    = 'Message';
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['data_legend']      = 'Meta data';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['markMessageRead']  = array('Mark message (un-)read', 'Mark message ID %s (un-)read');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['show']             = array('Message details', 'Show the details of message ID %s');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['delete']           = array('Delete message', 'Delete message ID %s');


/**
 * Frontend
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['submit']           = 'Submit message';


/**
 * Label
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['label']           = '<###fmt###>###name### &lt;###email###&gt; on ###date###  <span style="color:#b3b3b3; padding-left:3px;">[###id###]</span></###fmt###>';