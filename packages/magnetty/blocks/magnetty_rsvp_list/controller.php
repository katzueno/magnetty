<?php
namespace Concrete\Package\Magnetty\Block\MagnettyRsvpList;

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
use \Concrete\Package\Magnetty\Model\MagnettyEvent as MagnettyEvent;

/**
 * Magnetty Event Ticket List Block
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
    protected $btTable = 'btMagnettyRSVPList';

    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;

    /**
     * Used for localization. If we want to localize the name/description we have to include this
     */
    public function getBlockTypeDescription() {
        return t("Adds profile listing to your events");
    }

    public function getBlockTypeName() {
        return t("Magnetty Ticket RSVP List");
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
	$errorMsg = t("Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message: ");
		$c = Page::getCurrentPage();

		if (!is_object($c)) {
			throw new Exception($errorMsg . t('Error at the beginning of controller view'));
		}
		$cp = new Permissions($c);
		$bID = $block->getBlockID();
		$viewMode = '';
		
		// Loading Magnetty Models
		$Magnetty = new MagnettyEvent ();
		// Get the max number of tickets
		$magnettyTicketNum = getTicketNum();
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

    }

	public function action_admin_add ()  {
	$errorMsg = t("Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message: ");

	}

	public function action_admin_remove ()  {
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
		$bID = $block->getBlockID();

		if ($u->isRegistered() && $bID == $post['MagnettybID'] && $post['MagnettyuID'] == $uID ) {
			// Loading Magnetty Models
			$Magnetty = new MagnettyEvent ();

			if ($post['MagnettyStatus']=='rsvp') {

				// Get the max number of tickets
				$magnettyTicketNum = getTicketNum();
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
    }
     
    function duplicate($newBID) {
	    
	}


}
