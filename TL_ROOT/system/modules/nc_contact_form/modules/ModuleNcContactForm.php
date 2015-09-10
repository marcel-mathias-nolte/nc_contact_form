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

namespace NC;


/**
 * Front end module "contact form".
 *
 * @package   NC Contact Form
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2013
 */
class ModuleNcContactForm extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nc_contact_form_default';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### CONTACT FORM ###';
			return $objTemplate->parse();
		}
		$this->fields = @unserialize($this->nc_contact_form_fields);
		$strScriptfile = 'system/modules/nc_contact_form/assets/default.js';
		if (!in_array($strScriptfile, $GLOBALS['TL_JAVASCRIPT'])) {
			$GLOBALS['TL_JAVASCRIPT'][] = $strScriptfile;
		}
		return parent::generate();
	}
	
	/**
	 * Generate the module
	 */
	protected function compile()
	{
		global $objPage;

		$this->strTemplate = $this->nc_contact_form_template;
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('tl_nc_contact_form_messages');
		$this->loadDataContainer('tl_nc_contact_form_messages');

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA']['tl_nc_contact_form_messages']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_nc_contact_form_messages']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]();
				}
			}
		}
		
		$this->Template->fields = '';
		$doNotSubmit = false;
		
		// Captcha
		if (!$this->disableCaptcha)
		{
			$arrCaptcha = array
			(
				'id' => 'registration',
				'label' => $GLOBALS['TL_LANG']['MSC']['securityQuestion'],
				'type' => 'captcha',
				'mandatory' => true,
				'required' => true,
				'tableless' => $this->tableless
			);

			$strClass = $GLOBALS['TL_FFL']['captcha'];

			// Fallback to default if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				$strClass = 'FormCaptcha';
			}

			$objCaptcha = new $strClass($arrCaptcha);

			if ($this->Input->post('FORM_SUBMIT') == 'tl_nc_contact_form')
			{
				$objCaptcha->validate();

				if ($objCaptcha->hasErrors())
				{
					$doNotSubmit = true;
				}
			}
		}
		
		$arrMessage = array();
		$arrFields = array();
		$hasUpload = false;
		$fields = $this->fields;
		$i = 0;
				
		// Build form
		foreach ($fields as $field)
		{
			$arrData = $GLOBALS['TL_DCA']['tl_nc_contact_form_messages']['fields'][$field];

			// Map checkboxWizard to regular checkbox widget
			if ($arrData['inputType'] == 'checkboxWizard')
			{
				$arrData['inputType'] = 'checkbox';
			}

			$strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}
			
			$arrData['eval']['required'] = $arrData['eval']['mandatory'];
			
			$objWidget = new $strClass($this->prepareForWidget($arrData, $field, $arrData['default']));
			$objWidget->storeValues = true;
			$objWidget->rowClass = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

			// Increase the row count if its a password field
			if ($objWidget instanceof FormPassword)
			{
				$objWidget->rowClassConfirm = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
			}
			
			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == 'tl_nc_contact_form_messages')
			{
				$objWidget->validate();
				$varValue = $objWidget->value;

				// Check whether the password matches the username
				if ($objWidget instanceof FormPassword && $varValue == $this->Input->post('username'))
				{
					$objWidget->addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
				}

				$rgxp = $arrData['eval']['rgxp'];

				// Convert date formats into timestamps (check the eval setting first -> #3063)
				if (($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim') && $varValue != '')
				{
					// Use the numeric back end format here!
					$objDate = new Date($varValue, $GLOBALS['TL_CONFIG'][$rgxp.'Format']);
					$varValue = $objDate->tstamp;
				}

				// Make sure that unique fields are unique (check the eval setting first -> #3063)
				if ($arrData['eval']['unique'] && $varValue != '')
				{
					$objUnique = $this->Database->prepare("SELECT * FROM tl_nc_contact_form_messages WHERE " . $field . "=?")
												->limit(1)
												->execute($varValue);

					if ($objUnique->numRows)
					{
						$objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], (strlen($arrData['label'][0]) ? $arrData['label'][0] : $field)));
					}
				}

				// Save callback
				if (is_array($arrData['save_callback']))
				{
					foreach ($arrData['save_callback'] as $callback)
					{
						$this->import($callback[0]);

						try
						{
							$varValue = $this->$callback[0]->$callback[1]($varValue, $this->User);
						}
						catch (Exception $e)
						{
							$objWidget->class = 'error';
							$objWidget->addError($e->getMessage());
						}
					}
				}

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}

				// Store current value
				elseif ($objWidget->submitInput())
				{
					$arrMessage[$field] = $varValue;
				}
			}
			if ($objWidget instanceof uploadable)
			{
				$hasUpload = true;
			}

			$temp = $objWidget->parse() . "<br />";

			$this->Template->fields .= $temp;
			$arrFields[$field] .= $temp;

			++$i;
		}

		// Captcha
		if (!$this->disableCaptcha)
		{
			$objCaptcha->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');
			$strCaptcha = $objCaptcha->parse();

			$this->Template->fields .= $strCaptcha;
			$arrFields['captcha'] .= $strCaptcha;
		}

		$this->Template->rowLast = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->hasError = $doNotSubmit;

		// Create new user if there are no errors
		if ($this->Input->post('FORM_SUBMIT') == 'tl_nc_contact_form' && !$doNotSubmit)
		{
			$data = array();
			foreach ($arrFields as $key => $value) {
				$data[$key] = $_REQUEST[$key];
			}
			$this->saveMessage($data);
		}
		
		$this->Template->Fields = $arrFields;
		$this->Template->formId = 'tl_nc_contact_form';
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['tl_nc_contact_form_messages']['submit']);
		$this->Template->action = $this->getIndexFreeRequest();
	}
	
	/**
	 * Create a new message and redirect
	 * @param array
	 */
	protected function saveMessage($arrData)
	{
		$arrData['tstamp'] = time();
		$arrData['date'] = date("d.m.Y H:i");
		$arrData['ip'] = $_SERVER['REMOTE_ADDR'];
		$arrData['pid'] = $this->contact_form;
		unset($arrData['captcha']);
		unset($arrData['label']);

		$arrChunks = array();
		// Create message
		$objNewMessage = $this->Database->prepare("INSERT INTO tl_nc_contact_form_messages %s")->set($arrData)->execute();
		$insertId = $objNewMessage->insertId;

		// Inform admin 
		$this->sendAdminNotification($insertId, $arrData);
		$this->jumpToOrReload($this->jumpTo);
	}

	/**
	 * Send an admin notification e-mail
	 * @param integer
	 * @param array
	 */
	protected function sendAdminNotification($intId, $arrData)
	{
		$objEmail = new \Email();

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = 'Eine neue Nachricht wurde über das Kontaktformular auf ' . $this->Environment->host . ' übermittelt.';

		$strData = sprintf("\n\nEine neue Nachricht wurde über das Kontaktformular übermittelt:\n\nAbsender: %s <%s>\n\nTelefonnr.: %s\n\nZeitpunkt: %s (von IP %s)\n\nNachricht:\n\n%s\n\n", $arrData['name'] , $arrData['email'], $arrData['phone'], $arrData['date'], $arrData['ip'], $arrData['message']);

		$objEmail->text = $strData; //sprintf($GLOBALS['TL_LANG']['MSC']['adminText'], $intId, $strData . "\n") . "\n";
		$blnSend = false;
		$strMailTo = $this->Database->prepare("SELECT mailto FROM tl_nc_contact_form WHERE id=?")->limit(1)->execute($this->nc_contact_form)->next()->mailto;
		foreach (explode(',', $strMailTo) as $strRecipient) {
			if (trim($strRecipient) != '') {
				$objEmail->sendTo($strRecipient);
				$blnSend = true;
			}
		}
		if ($blnSend) {
			$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);
		}
	}	
}
