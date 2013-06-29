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
 * Class NcContactForm
 *
 * Various callbacks for all elements.
 */
class NcContactForm extends \Backend
{
	/**
	 * Return all forms as array
	 * @return array
	 */
	public function getForms()
	{
		$forms = array();
		$objLister = $this->Database->execute("SELECT * FROM tl_nc_contact_form_sites");
		while ($objLister->next())
		{
			$forms[$objLister->id] = $objLister->title;
		}
		return $forms;
	}

	/**
	 * Return all templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getTemplates(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;
		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}
		return $this->getTemplateGroup('nc_contact_', $intPid);
	}

	/**
	 * Return all templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getMailTemplates(\DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;
		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}
		return $this->getTemplateGroup('nc_contactmail_', $intPid);
	}

	/**
	 * Create a new message and redirect
	 * @param array
	 * @param boolean
	 */
	public function saveMessage($arrData, $blnSendNotification = true)
	{
		$arrData['tstamp'] = time();
		$arrData['date'] = date("d.m.Y H:i");
		$arrData['ip'] = $_SERVER['REMOTE_ADDR'];

		$objNewMessage = $this->Database->prepare("INSERT INTO tl_nc_contact_form %s")->set($arrData)->execute();
		$insertId = $objNewMessage->insertId;

		if ($blnSendNotification)
		{
			$this->sendAdminNotification($insertId, $arrData);
		}
		
	}

	/**
	 * Send an admin notification e-mail
	 * @param integer
	 * @param array
	 */
	public function sendAdminNotification($intId, $arrData)
	{
		$objForm = $this->Database->prepare("SELECT * FROM `tl_nc_contact_form_sites` WHERE `id` = ?")->execute($arrData['pid']);
		if ($objForm->next())
		{
			$objEmail = new \Email();
			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = $objForm->subject;
			$objTemplate = new \FrontendTemplate($objForm->mail_template ? $objForm->mail_template : 'nc_contactmail_default');
			$objTemplate->domain = $this->Environment->host;
			foreach ($arrData as $key => $value)
			{
				$objTemplate->$key = $value;
			}
			$objEmail->text = $objTemplate->parse();
			$objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);
		}
	}
}

?>