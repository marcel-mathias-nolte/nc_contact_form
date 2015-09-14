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

namespace NC;


/**
 * Front end module "contact form".
 *
 * @package   NC Contact Form
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 */
class ModuleNcContactForm extends \Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### CONTACT FORM ###';
			return $objTemplate->parse();
		}
		$this->fields = @unserialize($this->nc_contact_form_fields);
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
		$arrData['date'] = date($GLOBALS['TL_CONFIG']['datimFormat']);
		$arrData['ip'] = $_SERVER['REMOTE_ADDR'];
		$arrData['pid'] = $this->nc_contact_form;
		if (!in_array('name', $this->fields) && in_array('firstname', $this->fields) && in_array('lastname', $this->fields)) {
			$arrData['name'] = $arrData['firstname'] . ' ' . $arrData['lastname'];
		} else if (in_array('name', $this->fields) && !in_array('firstname', $this->fields) && !in_array('lastname', $this->fields)) {
			$tmp = explode(' ', $arrData['name']);
			$arrData['lastname'] = array_pop($tmp);
			$arrData['firstname'] = implode(' ', $tmp);
		}
		unset($arrData['captcha']);
		unset($arrData['label']);
		$objNewMessage = $this->Database->prepare("INSERT INTO tl_nc_contact_form_messages %s")->set($arrData)->execute();
		$insertId = $objNewMessage->insertId;
		if ($this->Database->tableExists('tl_nc_notifications')) {
			$this->Database->prepare("INSERT INTO tl_nc_notifications (tstamp, sid, source, href) VALUES (?, ?, ?, ?)")->execute($arrData['tstamp'], $insertId, 'tl_nc_contact_form_messages', 'main.php?do=ncContactForm&table=tl_nc_contact_form_messages&act=show&id=' . $insertId);
		}
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
		$token = array(
			'###domain###' => $this->Environment->host
		);
		foreach ($arrData as $key => $value) {
			$token['###' . $key . '###'] = $value;
		}
		
		$objEmail = new \Email();
		
		if ($this->nc_contact_form_mail_admin) {
			if ($this->nc_contact_form_mail_admin_original_sender) {
				$objEmail->from = $arrData['email'];
				$objEmail->fromName = $arrData['name'];
			} else {
				$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
				$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			}
			$objEmail->subject = strtr(html_entity_decode($this->nc_contact_form_mail_admin_subject), $token);
			$objEmail->text = strtr($this->nc_contact_form_mail_admin_text, $token);
			$blnSend = false;
			$strMailTo = $this->nc_contact_form_mail_admin_address;
			foreach (explode(',', $strMailTo) as $strRecipient) {
				if (trim($strRecipient) != '') {
					$objEmail->sendTo($strRecipient);
					$blnSend = true;
				}
			}
			if (!$blnSend) {
				$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);
			}
		}
		
		if ($this->nc_contact_form_mail_user) {
			if ($this->nc_contact_form_mail_user_original_sender) {
				$objEmail->from = $arrData['email'];
				$objEmail->fromName = $arrData['name'];
			} else {
				$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
				$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			}
			$objEmail->subject = strtr(html_entity_decode($this->nc_contact_form_mail_user_subject), $token);
			$objEmail->text = strtr($this->nc_contact_form_mail_user_text, $token);
			$blnSend = false;
			$strMailTo = trim($arrData['email']);
			$objEmail->sendTo($strMailTo);
		}
	}	
}