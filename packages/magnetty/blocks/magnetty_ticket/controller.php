<?php
namespace Concrete\Package\Magnetty\Block\MagnettyTicket;

use Loader;
use Page;
use Permissions;
use BlockType;
use User;
use UserInfo;
use Concrete\Core\Validation\CSRF\Token;
use Route;
use URL;
use Exception;
use Package;
use Config;
use Core;
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
    
    private $debugMode = '1';
    private $errorMsg = 'Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message: ';
    
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
        $pkgSettings = array (
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
		$pkgSettings = $this->getPackageDefaultSettings();
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
	function getEmailWaitlistCancellationSubject() 	{return $this->emailWaitlistCancellationSubject;}
	function getEmailWaitlistCancellationBody()		{return $this->emailWaitlistCancellationBody;}
	function getEmailPaymentSubject()		{return $this->emailPaymentSubject;}
	function getEmailPaymentBody()			{return $this->emailPaymentBody;}

    public function view() {
	    $debugMode = $this->debugMode;
		$errorMsg = t($this->errorMsg);
	    
		$c = Page::getCurrentPage();
		$cID =  $c->getCollectionID();
		if (!is_object($c)) {
			throw new Exception($errorMsg . t('Error at the beginning of controller view'));
		}
		$cp = new Permissions($c);
		//$bID;
		$viewMode = '';
		
		$bID = $this->bID;
		
		// Loading Magnetty Models
		$Magnetty = new MagnettyEvent ();
		// Get the max number of tickets
		$magnettyTicketNum = $this->getTicketNum();
		// Get the current number of tickets RSVPed
		$magnettyTicketCount = $Magnetty->getRSVPnum($bID);

		$u = new User();
		
		 $nh = Loader::helper('navigation');
		 $ticketURL = $nh->getCollectionURL($c);
		
		if ($debugMode) {
			echo "<p><b>View Step 1: Initial Setup</b><br />";
			echo 'TicketNum; '; var_dump($magnettyTicketNum); echo '<br />';
			echo 'TicketRSVP; '; var_dump($magnettyTicketCount); echo '<br />';
			echo 'ViewMode; '; var_dump($viewMode); echo '<br />';
			echo 'bID; '; var_dump($bID); echo '<br />';
			echo 'cID; '; var_dump($cID); echo '<br />';
			echo 'Status; '; var_dump($magnettyStatus); echo '<br />';
			echo 'Date; '; echo Date('Y-m-d H:i:s'); echo '<br />';
			echo 'Current URL: '; echo $ticketURL; echo '<br />';
			echo '</p>';
		}

		$canViewToolbar = (isset($cp) && is_object($cp) && $cp->canViewToolbar());
		if ($canViewToolbar) {
			$viewMode = 'Admin';				

		} else if ($u->isRegistered()) {
			$viewMode = 'Registered';

		} else {
			$viewMode = 'Unregistered';
		}

		if ($debugMode) {
			echo "<p><b>View Step 2: Get login or guest </b><br />";
			echo 'TicketNum; '; var_dump($magnettyTicketNum); echo '<br />';
			echo 'TicketRSVP; '; var_dump($magnettyTicketCount); echo '<br />';
			echo 'ViewMode; '; var_dump($viewMode); echo '<br />';
			echo 'bID; '; var_dump($bID); echo '<br />';
			echo 'cID; '; var_dump($cID); echo '<br />';
			echo 'Status; '; var_dump($magnettyStatus); echo '<br />';
			echo '</p>';
		}

		if ($viewMode == 'Registered' || $viewMode == 'Admin' ) {
			$uID = $u->getUserID();

			$magnettyStatus = $Magnetty->getRSVPstatus($bID, $uID);

			if ($debugMode) {
				echo "<p><b>View Step 3: Get Current Status</b><br />";
				echo 'TicketNum; '; var_dump($magnettyTicketNum); echo '<br />';
				echo 'TicketRSVP; '; var_dump($magnettyTicketCount); echo '<br />';
				echo 'ViewMode; '; var_dump($viewMode); echo '<br />';
				echo 'bID; '; var_dump($bID); echo '<br />';
				echo 'cID; '; var_dump($cID); echo '<br />';
				echo 'Status; '; var_dump($magnettyStatus); echo '<br />';
				echo '</p>';
			}

			if ($magnettyStatus['rsvp'] && !$magnettyStatus['rsvp'] !== "0000-00-00 00:00:00") {
				$viewMode = 'RSVPed';
			}
			if ($magnettyStatus['checkin'] && $magnettyStatus['checkin'] !== "0000-00-00 00:00:00") {
				$viewMode = 'RSVPed'; // Needs to be changed
			}
			if ($magnettyStatus['paid'] && $magnettyStatus['paid'] !== "0000-00-00 00:00:00") {
				$viewMode = 'Paid';
			}
			if ($magnettyStatus['waitlist'] && $magnettyStatus['waitlist'] !== '0000-00-00 00:00:00') {
				$viewMode = 'Waitlist';
			}
			if ($magnettyStatus['cancel'] && $magnettyStatus['cancel'] !== "0000-00-00 00:00:00") {
				$viewMode = 'Cancelled';
			}
			if ($magnettyStatus['waitlistcancel'] && $magnettyStatus['waitlistcancel'] !== "0000-00-00 00:00:00") {
				$viewMode = 'WaitlistCancelled';
			}

			if ($debugMode) {
				echo "<p><b>View Step 4: Get Status Detail</b><br />";
				echo 'TicketNum; '; var_dump($magnettyTicketNum); echo '<br />';
				echo 'TicketRSVP; '; var_dump($magnettyTicketCount); echo '<br />';
				echo 'ViewMode; '; var_dump($viewMode); echo '<br />';
				echo 'bID; '; var_dump($bID); echo '<br />';
				echo 'cID; '; var_dump($cID); echo '<br />';
				echo 'Status; '; var_dump($magnettyStatus); echo '<br />';
				echo 'RSVP: ' . $magnettyStatus['rsvp'] . '<br />';
				echo 'Check-in: ' . $magnettyStatus['checkin'] . '<br />';
				echo 'Paid: ' . $magnettyStatus['paid'] . '<br />';
				echo 'Cancelled: ' . $magnettyStatus['cancel'] . '<br />';
				echo 'Waitlist: ' . $magnettyStatus['waitlist'] . '<br />';
				echo '</p>';
			}


			if ($viewMove == 'Registered') {
				if ( $magnettyTicketCount >= $magnettyTicketNum) {
					$viewMode = 'Full';
				}
			}
			
			if (($viewMode == 'Cancelled') || ($viewMode == 'WaitlistCancelled') ) {
				if ( $magnettyTicketCount >= $magnettyTicketNum) {
					$viewMode = 'Cancelled_Full';					
				}

			}
		
			if ($debugMode) {
				echo "<p><b>View Step 5: Get Availabilty Check</b><br />";
				echo 'TicketNum; '; var_dump($magnettyTicketNum); echo '<br />';
				echo 'TicketRSVP; '; var_dump($magnettyTicketCount); echo '<br />';
				echo 'TicketAvailable; '; echo ($magnettyTicketNum-$magnettyTicketCount); echo '<br />';
				echo 'ViewMode; '; var_dump($viewMode); echo '<br />';
				echo 'bID; '; var_dump($bID); echo '<br />';
				echo 'cID; '; var_dump($cID); echo '<br />';
				echo 'Status; '; var_dump($magnettyStatus); echo '<br />';
				echo 'RSVP: ' . $magnettyStatus['rsvp'] . '<br />';
				echo 'Check-in: ' . $magnettyStatus['checkin'] . '<br />';
				echo 'Paid: ' . $magnettyStatus['paid'] . '<br />';
				echo 'Cancelled: ' . $magnettyStatus['cancel'] . '<br />';
				echo 'Waitlist: ' . $magnettyStatus['waitlist'] . '<br />';
				echo 'Waitlist Cancelled: ' . $magnettyStatus['waitlistcancel'] . '<br />';
				echo '</p>';
			}

			$this->set('uID', $uID);
		}
		//$canCancel = $this->getCanCancel();
		$token = new Token();
		$this->set('token', $token);
		$this->set('magnettyTicketNum', $magnettyTicketNum);
		$this->set('magnettyTicketCount', $magnettyTicketCount);
		$this->set('viewMode', $viewMode);
		$this->set('debugMode', $debugMode);

		if ($viewMode == null) {
			throw new Exception($errorMsg . t('Error while setting viewMode'));
		}

    }

    public function action_rsvp()  {
	    $debugMode = $this->debugMode;
		$errorMsg = t($this->errorMsg);

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

		if ($debugMode) {
			echo "<p><b>Action Step 1: Entering Action</b><br />";
			echo '</p>';
		}
		if (!$this->isPost()) {
			view();
			return;
		}
		$post = $this->post();
	
		if ($debugMode) {
			echo "<p><b>Action Step 2: Has post data</b><br />";
			echo 'post; '; var_dump($post); echo '<br />';
			echo '</p>';
		}

		$token = new Token();
		$tokenSubmitted = h($post['token']);
		if ($tokenSubmitted == $token->validate('rsvp', $tokenSubmitted)) {
	
			if (!($post['MagnettybID'] && $post['MagnettyuID'])) {
				return;
			}
	
			if ($debugMode) {
				echo "<p><b>Action Step 3: Has correct post </b><br />";
				echo 'post; '; var_dump($post); echo '<br />';
				echo 'MagnettybID; '; echo $post['MagnettybID']; echo '<br />';
				echo 'MagnettyuID; '; echo $post['MagnettyuID']; echo '<br />';
				echo '</p>';
			}
			$u = new User();
			$uID = $u->getUserID();
			$bID = $this->bID;
			$c = Page::getCurrentPage();
			$cID =  $c->getCollectionID();
	
			if ($debugMode) {
				echo "<p><b>Action Step 4: Basic info such as uID, bID, cID</b><br />";
				echo 'post; '; var_dump($post); echo '<br />';
				echo 'MagnettybID; '; echo $post['MagnettybID']; echo '<br />';
				echo 'MagnettyuID; '; echo $post['MagnettyuID']; echo '<br />';
				echo 'uID; '; echo $uID; echo '<br />';
				echo 'bID; '; echo $bID; echo '<br />';
				echo 'cID; '; echo $cID; echo '<br />';
				echo '</p>';
			}
	
			if ($u->isRegistered() && $bID == $post['MagnettybID'] && $post['MagnettyuID'] == $uID ) {
	
				// Loading Magnetty Models
				$Magnetty = new MagnettyEvent ();
	
				// Get cuurent date and time
				$date = Date('Y-m-d H:i:s');
				
				$magnettyStatus = $Magnetty->getRSVPstatus($bID, $uID);
	
	
				if ($post['MagnettyStatus']=='rsvp') {
	
	
					//
					//
					//  I Should Add DOUBLE RSVP CHECK
					//
					//
					//
	
					// Get the max number of tickets
					$magnettyTicketNum = $this->getTicketNum();
					// Get the current number of tickets RSVPed
					$magnettyTicketCount = $Magnetty->getRSVPnum($bID);
	
					if ( $magnettyTicketCount >= $magnettyTicketNum) {
						$Magnetty->addWaitlist($cID, $bID, $uID, $date);
						$emailStatus = 'Waitlist';
					} else {
						$Magnetty->addRSVP($cID, $bID, $uID, $date);
						$emailStatus = 'RSVPed';
					}
	
				} else if ($post['MagnettyStatus']=='cancel') {
					$Magnetty->cancelRSVP($bID, $uID, $date);
					$emailStatus = 'Cancelled';
					// Need to add waitlist recover function
	
				} else if ($post['MagnettyStatus']=='cancelwaitlist') {
					$Magnetty->cancelWaitlistRSVP($bID, $uID, $date);
					$emailStatus = 'WaitlistCancelled';
	
				} else if ($post['MagnettyStatus']=='paid') {
					// In development
					$emailStatus = 'Paid';
					// Need to add recover function
					
				} else {
					$emailStatus = 'Invalid';
				}
				
				if ($debugMode) {
					echo "<p><b>Action Step 5: Email Flag</b><br />";
					echo 'post; '; var_dump($post); echo '<br />';
					echo 'MagnettybID; '; echo $post['MagnettybID']; echo '<br />';
					echo 'MagnettyuID; '; echo $post['MagnettyuID']; echo '<br />';
					echo 'uID; '; echo $uID; echo '<br />';
					echo 'bID; '; echo $bID; echo '<br />';
					echo 'cID; '; echo $cID; echo '<br />';
					echo 'emailStatus: '; echo $emailStatus; echo '<br />';
					echo '</p>';
				}
				if ($emailStatus) {
					$this->MagnettySendEmail($emailStatus);
					$this->redirect($c->getCollectionPath());
					return;
				} else {
					throw new Exception($errorMsg . t('Error while action_rsvp'));
				}
	
			} else {
				if ($debugMode) {
					echo '<p><b>Action Step 4: The the actions was for different block</b></p>';
				}
				$this->view();
				return;
			}
		} else {
			if ($debugMode) {
				echo '<p><b>Action Step 1: Security Token not validated</b></p>';
			}
			throw new Exception($errorMsg . t('Error has occurred'));
			return;
		}
    }




    public function MagnettySendEmail($email)  {
	    $debugMode = $this->debugMode;
		$errorMsg = t($this->errorMsg);

		// Email Flag: $email
			// RSVPed
			// Waitlist
			// Cancelled
			// WaitlistCancelled
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
		$pkgSettings = $this->getPackageDefaultSettings();

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
			$userName = $ui->getUserID();
		} else {
			$userName = $ui->getUserEmail();
		}
		if (!$userName){
			throw new Exception($errorMsg . t('Error while setting userName.'));
		}
		
		$ticketName = $this->getTicketName();
		if (!$ticketName) {
			throw new Exception($errorMsg . t('TicketName is not set'));
		}

		

		 $nh = Loader::helper('navigation');
		 $ticketURL = $nh->getCollectionURL($c);
		if (!$ticketURL){
			throw new Exception($errorMsg . t('Error while setting ticketURL.'));
		}


		if ($debugMode) {
			echo "<p><b>Email Step 1: Iniial Info related info</b><br />";
			echo 'uID; '; echo $uID; echo '<br />';
			echo 'bID; '; echo $bID; echo '<br />';
			echo 'toEmail; '; echo $toEmail; echo '<br />';
			echo 'userName: '; echo $userName; echo '<br />';
			echo 'ticketName: '; echo $ticketName; echo '<br />';
			echo 'ticketURL: '; echo $ticketURL; echo '<br />';
			echo '</p>';
		}



		// PREPARE TO SEND EMAIL - INITIAL SETTING		
		if (!$pkgSettings['adminEmail']) {
			$adminUser = UserInfo::getByID(USER_SUPER_ID);
			if (is_object($adminUser)) {
	        	$adminUserEmail = $adminUser->getUserEmail();
	        	$fromEmail = $adminUserEmail;
        	} else {
	        	throw new Exception($errorMsg . t('From Email address is not set'));
        	}
		} else {
			$fromEmail = $pkgSettings['adminEmail'];

			if (!$fromEmail) {
				throw new Exception($errorMsg . t('From Email address is not set'));
			}
		}

		$siteName = Config::get('concrete.site');


		if ($debugMode) {
			echo "<p><b>Email Step 2: Setup FROM related info</b><br />";
			echo 'uID; '; echo $uID; echo '<br />';
			echo 'bID; '; echo $bID; echo '<br />';
			echo 'fromEmail; '; echo $fromEmail; echo '<br />';
			echo 'siteName; '; echo $siteName; echo '<br />';
			echo 'toEmail; '; echo $toEmail; echo '<br />';
			echo 'userName: '; echo $userName; echo '<br />';
			echo 'ticketName: '; echo $ticketName; echo '<br />';
			echo 'ticketURL: '; echo $ticketURL; echo '<br />';
			echo '</p>';
		}

		$mh->to($toEmail); 
		$mh->from($fromEmail); 
		$mh->replyto($fromEmail); 		

		// Prepare to send RSVP Confirmation Email.
		
		if ($email == 'RSVPed') {
			
			$emailSubject = $this->getEmailConfirmationSubject();
			if (!$emailSubject) {
				$emailSubject = t('RSVP Confirmation: ') . $this->getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Subject is not set'));
			}
			
			$emailBody = $this->getEmailConfirmationBody();
			if (!$emailBody) {
				$emailBody = $pkgSettings['emailConfirmationText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_rsvp';
			
		// Prepare to send Waitlist Confirmation Email.

		} else if ($email == 'Waitlist') {

			$emailSubject = $this->getEmailWaitlistSubject();
			if (!$emailSubject) {
				$emailSubject = t('Waitlist Confirmation: ') . $this->getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('Waitlist Confirmation Email Subject is not set'));
			}
			
			$emailBody = $this->getEmailWaitlistBody();
			if (!$emailBody) {
				$emailBody = $pkgSettings['emailWaitlistText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('Confirmation Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_waitlist';

			
		// Prepare to send Cancelled Confirmation Email.

		} else if ($email == 'Canceled') {

			$emailSubject = $this->getEmailCancellationSubject();
			if (!$emailSubject) {
				$emailSubject = t('Cancel Confirmation: ') . $this->getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('Cancel Confirmation Email Subject is not set'));
			}
			
			$emailBody = $this->getEmailCancellationBody();
			if (!$emailBody) {
				$emailBody = $pkgSettings['emailCancelText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('Cancel Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_cancel';


		// Prepare to send Waitlist Cancelled Confirmation Email.

		} else if ($email == 'WaitlistCanceled') {

			$emailSubject = $this->getEmailWaitlistCancellationSubject();
			if (!$emailSubject) {
				$emailSubject = t('Waitlist Cancel Confirmation: ') . $this->getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('Waitlist Cancel Confirmation Email Subject is not set'));
			}
			
			$emailBody = $this->getEmailCancellationBody();
			if (!$emailBody) {
				$emailBody = $pkgSettings['emailWaitlistCancelText'];
			}
			if (!$emailBody) {
				throw new Exception($errorMsg . t('Waitlist Cancel Email Body is not set'));
			}			
			$emailTemplate = 'magnetty_event_waitlist_cancel';


		// Prepare to send Waitlist_RSVP Confirmation Email.
		
		} else if ($email == 'Waitlist_RSVPed') {

			$emailSubject = $this->getEmailConfirmationSubject();
			if (!$emailSubject) {
				$emailSubject = t('RSVP Confirmation: ') . $this->getTicketName();
			}
			if (!$emailSubject) {
				throw new Exception($errorMsg . t('RSVP Confirmation Email Subject is not set'));
			}
			
			$emailBody = $this->getEmailConfirmationBody();
			if (!$emailBody) {
				$emailBody = $pkgSettings['emailConfirmationText'];
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
		$th = Loader::helper('text');
		$emailBody = h($emailBody);
		$emailHTMLBody = $th->autolink($emailBody, 1);
		$emailHTMLBody = nl2br($emailBody);
				
		// Load Mail Template and send an email.

		if (!$emailBody) {
			echo 'Email Template: '; var_dump($emailTemplate); echo '<br />';
			throw new Exception($emailTemplate . t('Cancel Email Body is not set'));
		}

		
		$mh->addParameter('emailSubject', $emailSubject);
		$mh->addParameter('ticketName', $ticketName); 
		$mh->addParameter('ticketURL', $ticketURL); 
		$mh->addParameter('siteName', $siteName); 
		$mh->addParameter('userName', $userName); 
		$mh->addParameter('emailBodyPlain', $emailBody);
		$mh->addParameter('emailBodyHTML', $emailHTMLBody);
		$mh->load($emailTemplate, 'magnetty');
		@$mh->sendMail();
	}

}
