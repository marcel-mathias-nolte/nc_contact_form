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
 * Run in a custom namespace, so the class can be replaced
 */
namespace NC;

/**
 * Class ContentNcContactForm 
 *
 * Front end module "contact form".
 */
class ContentNcContactForm extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nc_contact_default';


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
		if ($this->nc_contact_form_template)
		{
			$this->strTemplate = $this->nc_contact_form_template;
		}
		return parent::generate();
	}
	
	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $objPage;

		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('tl_nc_contact_form');
		$this->loadDataContainer('tl_nc_contact_form');

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA']['tl_nc_contact_form']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_nc_contact_form']['config']['onload_callback'] as $callback)
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
		if (!$this->nc_contact_form_disable_captcha)
		{
			$arrCaptcha = array
			(
				'id' => 'nc_contact_form',
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
		
		$fields = $this->disableCaptcha ? array('name', 'phone', 'email', 'message') : array('name', 'phone', 'email', 'captcha', 'message');
		$i = 0;
				
		// Build form
		foreach ($fields as $field)
		{
			if ($field == 'captcha')
			{
				$objCaptcha->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');
				$arrFields['captcha'] = $objCaptcha;
			}
			else
			{
				$arrData = $GLOBALS['TL_DCA']['tl_nc_contact_form']['fields'][$field];
	
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
				if ($this->Input->post('FORM_SUBMIT') == 'tl_nc_contact_form')
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
						$objUnique = $this->Database->prepare("SELECT * FROM tl_nc_contact_form WHERE " . $field . "=?")
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
	
				$arrFields[$field] = $objWidget;
	
				++$i;
			}
		}

		$this->Template->rowLast = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->hasError = $doNotSubmit;

		// Create new user if there are no errors
		if ($this->Input->post('FORM_SUBMIT') == 'tl_nc_contact_form' && !$doNotSubmit)
		{
			$this->import('NcContactForm');
			$this->NcContactForm->saveMessage(array(
				'name' => $this->Input->post('name'),
				'email' => $this->Input->post('email'),
				'phone' => $this->Input->post('phone'),
				'message' => $this->Input->post('message'),
				'pid' => $this->nc_contact_form_site
			));
			// Redirect to the jumpTo page
			$objNextPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id = ?")->limit(1)->execute($this->nc_contact_form_jump_to);
			$target = $objNextPage->numRows ? $this->generateFrontendUrl($objNextPage->fetchAssoc()) : '';
			if (!empty($target))
			{
				$this->redirect($target);
			}
			else
			{
				$this->reload();
			}
		}
		
		$this->Template->fields = $arrFields;
		$this->Template->formId = 'tl_nc_contact_form';
		$this->Template->slabel = specialchars($GLOBALS['TL_LANG']['MSC']['nc_contact_form']['submit']);
		$this->Template->action = $this->getIndexFreeRequest();
	}
}

?>