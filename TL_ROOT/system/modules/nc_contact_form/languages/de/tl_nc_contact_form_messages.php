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
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['name']             = array('Vor- und Familienname', 'Bitte geben Sie den vollst. Namen ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['phone']            = array('Telefonnummer', 'Bitte geben Sie die Telefonnummer ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['email']            = array('E-Mail-Adresse', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender']           = array('Anrede', 'Bitte wählen Sie eine Anrede aus.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender']['male']   = 'Herr';
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['gender']['female'] = 'Frau';
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['firstname']        = array('Vorname', 'Bitte geben Sie den Vornamen ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['lastname']         = array('Nachname', 'Bitte geben Sie den Nachnamen ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['street']           = array('Straße', 'Bitte geben Sie den Straßennamen und die Hausnummer ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['postal']           = array('Postleitzahl', 'Bitte geben Sie die Postleitzahl ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['city']             = array('Ort', 'Bitte geben Sie den Namen des Ortes ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['callRequest']      = array('Rückruf erwünscht', 'Ob ein Rückruf erwünscht ist.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['message']          = array('Nachricht', 'Bitte geben Sie eine Nachricht ein.');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['date']             = array('Datum', '');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['ip']               = array('IP-Adresse', '');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['messageRead']      = array('Nachricht gelesen', 'Ob die Nachricht gelesen wurde.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['sender_legend']    = 'Nachricht';
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['data_legend']      = 'Metadaten';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['markMessageRead']  = array('Nachricht als (un-)gelesen markieren', 'Nachricht ID %s als (un-)gelesen markieren');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['show']             = array('Nachricht anzeigen', 'Nachricht ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['delete']           = array('Nachricht löschen', 'Nachricht ID %s löschen');


/**
 * Frontend
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['submit']           = 'Nachricht übermitteln';


/**
 * Label
 */
$GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['label']           = '<###fmt###>###name### &lt;###email###&gt; am ###date### <span style="color:#b3b3b3; padding-left:3px;">[###id###]</span></###fmt###>';