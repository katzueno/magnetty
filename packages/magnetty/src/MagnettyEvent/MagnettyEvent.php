<?php
namespace Concrete\Package\Magnetty\Src\MagnettyEvent;

use Database;
use User;

/**
 * Magnetty Event Model
 *
 * Event RSVP and ticketing system
 *
 * LICENSE: concrete5 Marketplace Commercial Lisence
 *
 * @category   Social Networking
 * @package    Magnetty
 * @author     Katz Ueno <iam@katzueno.com>
 * @copyright  2015 Katz Ueno
 * @license    concrete5 Marketplace Commercial Lisence
 */
class MagnettyEvent {


    /**
     * @param int $ID Collection ID of a page
     * @param int $cID Collection ID of a page
     * @param int $bID Block ID
     * @param int $tID Ticket ID
     * @param int $uID logged-in user
     * @param date $rsvp RSVPed dates
     * @param date $checkin checked-in dates
     * @param date $waitlist when he or she joined the wait-list
     * @param date $cancel cancelled dates
     * @param date $paid payment confirmed dates and time
     * @return $status
     */

	 /**
	  *
	  * FUNCTION LISTS
	  *
	  * getRSVPstatus($tID, $uID)
	  *
	  * getRSVPnum($tID)
	  * getCancelnum($tID)
	  *
	  * addRSVP($cID, $tID, $uID)
	  * addWaitlist($cID, $tID, $uID)
	  * checkinRSVP($tID, $uID)
	  * paidRSVP($tID, $uID)
	  * cancelRSVP($tID, $uID)
	  * recoverRSVP($tID, $uID)
	  *
	  * getRSVPTicketList($tID)
	  * getWaitList($tID)
	  * getCancelTicketList($tID)
	  */

    public static function getRSVPstatus($tID, $uID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetRow('SELECT * from MagnettyEventAttend WHERE tID = ? AND uID = ?', array($tID, $uID));
        return $query;
    }

    public static function getRSVPnum($tID)
    {
        $db = Database::getActiveConnection();
        $nulldate = '0000-00-00 00:00:00';
        $count1 = $db->GetOne('SELECT COUNT(*) from MagnettyEventAttend WHERE tID = ? AND rsvp IS NOT NULL', array($tID));
        $count1 = intval($count1);
        $count2 = $db->GetOne('SELECT COUNT(*) from MagnettyEventAttend WHERE tID = ? AND cancel IS NOT NULL', array($tID));
        $count2 = intval($count2);
        //$count3 = $db->GetOne('SELECT COUNT(*) from MagnettyEventAttend WHERE tID = ? AND waitlistcancel IS NOT NULL', array($tID));
        //$count3 = intval($count3);
        //$count = $count1-$count2-$count3;
        $count = $count1-$count2;
        return $count;
    }

    public static function getCancelnum($tID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetAll('SELECT COUNT(*) from MagnettyEventAttend WHERE tID = ? AND waitlistcancel IS NOT NULL', array($tID));
        return $query;
    }

    public static function getWaitlistCancelnum($tID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetAll('SELECT COUNT(*) from MagnettyEventAttend WHERE tID = ? AND cancel IS NOT NULL', array($tID));
        return $query;
    }

    public static function addRSVP($cID, $tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $args = array(
            'cID' => $cID,
            'tID' => $tID,
            'uID' => $uID,
            'rsvp' => $date,
            'waitlist' => null,
            'cancel' => null,
            'checkin' => null,
            'paid' => null,
        );
        $db->insert('MagnettyEventAttend', $args);
        return;
    }

    public static function addWaitlist($cID, $tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $args = array(
            'cID' => $cID,
            'tID' => $tID,
            'uID' => $uID,
			'rsvp' => null,
            'waitlist' => $date,
            'cancel' => null,
            'checkin' => null,
            'paid' => null,
        );
        $db = Database::getActiveConnection();
        $db->insert('MagnettyEventAttend', $args);
        return;
    }

    public static function checkinRSVP($tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $data = array (
	        'checkin' => $date,
        );
        $where = array (
	        'tID' =>$tID,
	        'uID' =>$uID,
        );
        $db->update('MagnettyEventAttend', $data, $where);
        //$db-> update('MagnettyEventAttend SET checkin = ?, WHERE tID = ? AND uID = ?', array($date, $tID, $uID));
        return;
    }

    public static function paidRSVP($tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $data = array (
	        'paid' => $date,
        );
        $where = array (
	        'tID' =>$tID,
	        'uID' =>$uID,
        );
        //$db-> update($table, $data, array('id' => 17));
        //$db-> update('MagnettyEventAttend SET paid = ?, WHERE tID = ? AND uID = ?', array($date, $tID, $uID));
        $db->update('MagnettyEventAttend', $data, $where);
        return;
    }

    public static function cancelRSVP($tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $data = array (
	        'cancel' => $date,
        );
        $where = array (
	        'tID' =>$tID,
	        'uID' =>$uID,
        );
        $db->update('MagnettyEventAttend', $data, $where);
        //$db-> update('MagnettyEventAttend SET cancel = ?, WHERE tID = ? AND uID = ?', array($date, $tID, $uID));
        return;
    }

    public static function cancelWaitlistRSVP($tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $data = array (
	        'waitlistcancel' => $date,
        );
        $where = array (
	        'tID' =>$tID,
	        'uID' =>$uID,
        );
        $db->update('MagnettyEventAttend', $data, $where);
        //$db-> update('MagnettyEventAttend SET cancel = ?, WHERE tID = ? AND uID = ?', array($date, $tID, $uID));
        return;
    }

    public static function recoverRSVP($tID, $uID, $date)
    {
        $db = Database::getActiveConnection();
        $null = null;
        $data = array (
	        'rsvp' => $date,
	        'waitlistcancel' => $null,
	        'cancel' => $null,
        );
        $where = array (
	        'tID' =>$tID,
	        'uID' =>$uID,
        );
        $db->update('MagnettyEventAttend', $data, $where);
        return;
    }

    public static function getTicketList($cID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE cID = ? AND cancel IS NULL ORDER BY sortOrder', array($this->tID));
        $this->set('rows', $query);
        return $query;
    }

    public static function getTicketRSVPList($tID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE tID = ? AND cancel IS NULL ORDER BY sortOrder', array($this->tID));
        $this->set('rows', $query);
        return $query;
    }

    public static function getWaitList($tID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE tID = ? AND waitlist IS NOT NULL ORDER BY sortOrder', array($this->tID));
        $this->set('rows', $query);
        return $query;
    }

    public static function getCancelTicketList($tID)
    {
        $db = Database::getActiveConnection();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE tID = ? AND cancel IS NOT NULL ORDER BY sortOrder', array($this->tID));
        $this->set('rows', $query);
        return $query;
    }



}
