<?php
namespace Concrete\Package\Magnetty\Block\MagnettyTicket;

use Loader;
use Page;
use Permissions;
use User;
use UserInfo;
use Token;
use Route;
use URL;
use Exception;
use Package;
use Config;
use View;
use \Concrete\Core\Block\BlockController;
use \Concrete\Package\Magnetty\Models\MagnettyEvent as MagnettyEvent;

/**
 * Magnetty Event Ticket Block
 *
 * Event RSVP and ticketing system
 *
 * LICENSE: concrete5 Marketplace Commercial Lisence
 *
 * @category   Social Networking
 * @package    Magnetty
 * @author     Katz Ueno <iam@katzueno.com>
 * @copyright  2014 Katz Ueno
 * @license    concrete5 Marketplace Commercial Lisence
 */

// $viewMode Status:
	// Unregistered,
	// Registered
	// RSVPed
	// Paid
	// Cancelled
	// Cancelled_Full
	// Waitlist
	// Full
	// NotAllowed
	// Admin
// Options: $canCancel
	// 1-> Users can cancel RSVP and re-register
	// 2-> Users can cancel RSVP but cannot re-register
	// 3-> Users cannot cancel RSVP at all
 /**
  * getRSVPstatus($bID, $uID)
  *
  * getRSVPnum($bID)
  * getCancelnum($bID)
  *
  * addRSVP($cID, $bID, $uID)
  * addWaitlist($cID, $bID, $uID)
  * checkinRSVP($bID, $uID)
  * paidRSVP($bID, $uID)
  * cancelRSVP($bID, $uID)
  * recoverRSVP($bID, $uID)
  *
  * getRSVPTicketList($bID)
  * getWaitList($bID)
  * getCancelTicketList($bID)
  */



class Controller extends BlockController {

    protected $btInterfaceWidth = 640;
    protected $btInterfaceHeight = 550;
    protected $btTable = 'btMagnettyTicket';

    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;


    /**
     * Used for localization. If we want to localize the name/description we have to include this
     */
    public function getBlockTypeDescription() {
        return t("Adds ticket to your events");
    }

    public function getBlockTypeName() {
        return t("Magnetty Ticket");
    }

/*
    public function registerViewAssets() {
        // Ensure we have JQuery if we have an onState image
        if(is_object($this->getFileOnstateObject())) {
            $this->requireAsset('javascript', 'jquery');
        }
    }
*/

/*
	public function getJavaScriptStrings() {
		return array(
			'delete-question' => t('Are you sure you want to delete this question?'),
			'form-name' => t('Your form must have a name.'),
			'complete-required' => t('Please complete all required fields.'),
			'ajax-error' => t('AJAX Error.'),
			'form-min-1' => t('Please add at least one question to your form.')			
		);
	}
*/

	function getPackageDefaultSettings() {
		$pkg = Package::getByHandle('magnetty');
        $adminEmail = $pkg->getConfig()->get('magnetty.adminEmail');
        $allowCancel = $pkg->getConfig()->get('magnetty.allowCancel');
        $emailConfirmationText = $pkg->getConfig()->get('magnetty.emailConfirmationText');
        $emailWaitlistText = $pkg->getConfig()->get('magnetty.emailWaitlistText');
        $emailCancelText = $pkg->getConfig()->get('magnetty.emailCancelText');
        $packageSettings = array (
	        'adminEmail' => $adminEmail,
	        'allowCancel' => $allowCancel,
	        'emailConfirmationText' => $emailCancelText,
	        'emailWaitlistText' => $emailWaitlistText,
	        'emailCancelText' => $emailCancelText
        );
        return $pkgSettings;
	}

    function getTicketName() 				{return $this->ticketName;}
    function getTicketNum() 				{return $this->ticketNum;}
    function getTicketPrice()				{return $this->ticketPrice;}
    // function getAllowedGroups() 			{return $this->allowedGroups;} // Let's use permission
    function getCanCancel() 				{
		$pkgSettings = getPackageDefaultSettings();
	    if ($pkgSettings['allowCancel']) {
		    return $this->canCancel;
	    } else {
		    return '3';
	    }
	}
    function getEmailConfirmationSubject() 	{return $this->emailConfirmationSubject;}
	function getEmailConfirmationBody() 	{return $this->emailConfirmationBody;}
	function getEmailWaitlistSubject()		{return $this->emailWaitlistSubject;}
	function getEmailWaitlistBody()			{return $this->emailWaitlistBody;}
	function getEmailCancellationSubject() 	{return $this->emailCancellationSubject;}
	function getEmailCancellationBody()		{return $this->emailCancellationBody;}
	function getEmailPaymentSubject()		{return $this->emailPaymentSubject;}
	function getEmailPaymentBody()			{return $this->emailPaymentBody;}

    public function view() {
		$c = Page::getCurrentPage();
		$errorMsg = t("Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message: ");
		if (!is_object($c)) {
			throw new Exception($errorMsg . t('Error at the beginning of controller view'));
		}
		$cp = new Permissions($c);
		//$bID;
		$viewMode = '';
		
		// Loading Magnetty Models
		$Magnetty = new MagnettyEvent ();
		// Get the max number of tickets
		$magnettyTicketNum = $this->getTicketNum();
		// Get the current number of tickets RSVPed
		$magnettyTicketCount = $Magnetty->getRSVPnum($bID);

		$u = new User();

		$canViewToolbar = (isset($cp) && is_object($cp) && $cp->canViewToolbar());
		if ($canViewToolbar) {
			$viewMode = 'Admin';				

		} else if ($u->isRegistered()) {
			$viewMode = 'Registered';

		} else {
			$viewMode = 'Unregistered';
		}



		if ($viewMode == 'Registered' || $viewMode == 'Admin' ) {
			$uID = $u->getUserID();

			$magnettyStatus = $Magnetty->getRSVPstatus($bID, $uID);

			if ($magnettyStatus['checkin']) {
				$viewMode = 'RSVPed';
			}
			if ($magnettyStatus['paid']) {
				$viewMode = 'Paid';
			}
			if ($magnettyStatus['cancel']) {
				$viewMode = 'Cancelled';
			}
			if ($magnettyStatus['waitlist']) {
				$viewMode = 'Waitlist';
			}
			if ($viewMove == 'Registered') {
				if ( $magnettyTicketCount >= $magnettyTicketNum) {
					$viewMode = 'Full';
				}
			}
			if ($viewMode == 'Cancelled') {
				if ( $magnettyTicketCount >= $magnettyTicketNum) {
					$viewMode = 'Cancelled_Full';					
			}
			
			
			$canCancel = getCanCancel();
			
		}
			
			if (!isset($viewMode)) {
				throw new Exception($errorMsg . t('Error while setting viewMode'));
			}

				
		}
    }

    public function action_rsvp()  {
	$errorMsg = t("Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message: ");

	// SubmitStatus: MagnettyStatus
		// rsvp
		// cancel
		// recover
		// pay (TBA)

	// Email Flag: $email
		// RSVPed
		// Waitlist
		// Cancelled
		// Waitlist->RSVPed
		// Invalid

	    if (!$this->isPost()) {
			return;
		}
		$post = $this->post();

		if (!($post['bID'] && $post['uID'])) {
			return;
		}

		$u = new User();
		$uID = $u->getUserID();
		$c = Page::getCurrentPage();

		if ($u->isRegistered() && $bID == $post['MagnettybID'] && $post['MagnettyuID'] == $uID ) {
			// Loading Magnetty Models
			$Magnetty = new MagnettyEvent ();

			if ($post['MagnettyStatus']=='rsvp') {

				// Get the max number of tickets
				$magnettyTicketNum = $this->getTicketNum();
				// Get the current number of tickets RSVPed
				$magnettyTicketCount = $Magnetty->getRSVPnum($bID);

				if ( $magnettyTicketCount >= $magnettyTicketNum) {
					$Magnetty->addWaitlist($cID, $bID, $uID);
					$emailStatus = 'Waitlist';
				} else {
					$Magnetty->addRSVP($cID, $bID, $uID);
					$emailStatus = 'RSVPed';
				}

			} else if ($post['MagnettyStatus']=='cancel') {
				$Magnetty->cancelRSVP($bID, $uID);
				$emailStatus = 'Cancelled';
				// Need to add recover function

			} else if ($post['MagnettyStatus']=='paid') {
				// In development
				$emailStatus = 'Paid';
				// Need to add recover function
				
			} else {
				$emailStatus = 'Invalid';
			}
			
			if ($emailStatus) {
				$result = MagnettySendEmail($emailStatus);
			} else {
				throw new Exception($errorMsg . t('Error while action_rsvp'));
			}

		} else {
			return;
		}
    }

    public function MagnettySendEmail($email)  {
		$errorMsg = t("Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message: ");

		// Email Flag: $email
			// RSVPed
			// Waitlist
			// Cancelled
			// Waitlist->RSVPed
			// Paid
			// Ivalid

		if ($email == 'Invalid' || !($email)) {
				throw new Exception($errorMsg . t('Error while sending email'));
			return 'Error Sending an email';
		}
		// Load Initial Config and et

		$mh = Core::make('helper/mail');
		$c = Page::getCurrentPage();
		$pkgSettings = getPackageDefaultSettings();

		$u = new User();
		$uID = $u->getUserID();
		$ui = UserInfo::getByID($uID);
		
		if (!$ui){
			throw new Exception($errorMsg . t('Error while sending email at very first initial set-up.'));
		}

		$toEmail = $ui->getUserEmail();
		if (!$toEmail){
			throw new Exception($errorMsg . t('Error while sending email and setting up the To address'));
		}

		if ($ui->getAttribute('name')) {
			$userName = $ui-> getAttribute('name');

		} else if ($ui->getUserID()) {
			$userName = $ui-> $ui->getUserID();
		} else {
			$userName = $ui->getUserEmail();
		}
		if (!$userName){
			throw new Exception($errorMsg . t('Error while setting userName.'));
		}
		
		$ticketName = getTicketName();
		if (!$ticketName) {
			throw new Exception($errorMsg . t('TicketName is not set'));
		}
		

		$ticketURL = View::getViewPath( );
		if (!$ticketURL){
			throw new Exception($errorMsg . t('Error while setting ticketURL.'));
		}

		// PREPARE TO SEND EMAIL - INITIAL SETTING		
		if (!$pkgSettings['adminEmail']) {
			$adminUser = UserInfo::getByID(USER_SUPER_ID);
			if (is_object($adminUser)) {
	        	$adminUserEmail = $adminUser->getUserEmail();
	        	$pkgSettings['adminEmail'] = $adminUserEmail;
        	} else {
	        	throw new Exception($errorMsg . t('From Email address is not set'));
        	}
		}

		$siteName = Config::get('concrete.site');

		$mh->to($toEmail); 
		$mh->from( $pkgSettings['adminEmail'], $siteName); 
		$mh->replyto( $pkgSettings['adminEmail'], $siteName); 		

		// Prepare to send RSVP Confirmation Email.
		
		if ($email == 'RSVPed') {
			
			$emailSubject = getEmailConfirmationSubject();
			if (!$emailSubject) {
				$emailSubject = t('RSVP Confirmation: ') . getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Subject is not set'));
			}
			
			$emailBody = getEmailConfirmationBody();
			if (!$emailBody) {
				$emailBody = $packageSettings['emailConfirmationText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_rsvp';
			
		// Prepare to send Waitlist Confirmation Email.

		} else if ($email == 'Waitlist') {

			$emailSubject = getEmailWaitlistSubject();
			if (!$emailSubject) {
				$emailSubject = t('Waitlist Confirmation: ') . getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('Waitlist Confirmation Email Subject is not set'));
			}
			
			$emailBody = getEmailWaitlistBody();
			if (!$emailBody) {
				$emailBody = $packageSettings['emailWaitlistText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('Confirmation Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_waitlist';

			
		// Prepare to send Cancelled Confirmation Email.

		} else if ($email == 'Canceled') {

			$emailSubject = getEmailCancellationSubject();
			if (!$emailSubject) {
				$emailSubject = t('Cancel Confirmation: ') . getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('Cancel Confirmation Email Subject is not set'));
			}
			
			$emailBody = getEmailCancellationBody();
			if (!$emailBody) {
				$emailBody = $packageSettings['emailCancelText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('Cancel Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_cancel';


		// Prepare to send Waitlist_RSVP Confirmation Email.
		
		} else if ($email == 'Waitlist_RSVPed') {

			$emailSubject = getEmailConfirmationSubject();
			if (!$emailSubject) {
				$emailSubject = t('RSVP Confirmation: ') . getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Subject is not set'));
			}
			
			$emailBody = getEmailConfirmationBody();
			if (!$emailBody) {
				$emailBody = $packageSettings['emailConfirmationText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Body is not set'));
			}

			$emailTemplate = 'magnetty_event_waitlist2rsvp';


		// Prepare to send Paid Confirmation Email.
		
		} else if ($email == 'Paid') {

		// To be added

		}

		// Email Body Sanitize & HTML body
		$emailBody = h($emailBody);
		$emailHTMLBody = autolink($emailBody, 1);
		$emailHTMLBody = nlbr($emailBody);
				
		// Load Mail Template and send an email.
		
		$mh->addParameter('emailSubject', $emailSubject);
		$mh->addParameter('ticketName', $ticketName); 
		$mh->addParameter('ticketURL', $ticketURL); 
		$mh->addParameter('siteName', $siteName); 
		$mh->addParameter('userName', $userName); 
		$mh->addParameter('emailBodyPlain', $emailBody);
		$mh->addParameter('emailBodyHTML', $emailHTMLBody);
		$mh->load($emailTemplate);
		@$mh->sendMail();
	}

}
