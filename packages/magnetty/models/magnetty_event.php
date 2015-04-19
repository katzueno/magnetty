<?php 
namespace Concrete\Package\Magnetty\Models;

use \Concrete\Core\Legacy\Model;
use Loader;
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
 * @copyright  2014 Katz Ueno
 * @license    concrete5 Marketplace Commercial Lisence
 */
class MagnettyEvent extends Model {


    /**
     * @param int $ID Collection ID of a page
     * @param int $cID Collection ID of a page
     * @param int $bID Block ID of a page (ticket)
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

    public static function getRSVPstatus($bID, $uID)
    {
        $db = Loader::db();
        $query = $db->GetRow('SELECT * from MagnettyEventAttend WHERE bID = ? AND uID = ?', array($bID, $uID));
        return $query;
    }

    public static function getRSVPnum($bID)
    {
        $db = Loader::db();
        $nulldate = '0000-00-00 00:00:00';
        $count1 = $db->GetOne('SELECT COUNT(*) from MagnettyEventAttend WHERE bID = ? AND rsvp IS NOT NULL', array($bID));
        $count1 = intval($count1);
        $count2 = $db->GetOne('SELECT COUNT(*) from MagnettyEventAttend WHERE bID = ? AND cancel IS NOT NULL', array($bID));
        $count2 = intval($count2);
        $count = $count1-$count2;
        return $count;
    }

    public static function getCancelnum($bID)
    {
        $db = Loader::db();
        $query = $db->GetAll('SELECT COUNT(*) from MagnettyEventAttend WHERE bID = ? AND cancel IS NOT NULL', array($bID));
        return $query;
    }

    public static function addRSVP($cID, $bID, $uID, $date)
    {
        $db = Loader::db();
        $args = array(
            'cID' => $cID,
            'bID' => $bID,
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

    public static function addWaitlist($cID, $bID, $uID, $date)
    {
        $db = Loader::db();
        $args = array(
            'cID' => $cID,
            'bID' => $bID,
            'uID' => $uID,
			'rsvp' => null,
            'waitlist' => $date,
            'cancel' => null,
            'checkin' => null,
            'paid' => null,
        );
        $db = Loader::db();
        $db->insert('MagnettyEventAttend', $args);
        return;
    }

    public static function checkinRSVP($bID, $uID, $date)
    {
        $db = Loader::db();
        $db-> update('MagnettyEventAttend SET checkin = ?, WHERE bID = ? AND uID = ?', array($date, $bID, $uID));
        return;
    }

    public static function paidRSVP($bID, $uID, $date)
    {
        $db = Loader::db();
        //$db-> update($table, $data, array('id' => 17));
        $db-> update('MagnettyEventAttend SET paid = ?, WHERE bID = ? AND uID = ?', array($date, $bID, $uID));
        return;
    }

    public static function cancelRSVP($bID, $uID, $date)
    {
        $db = Loader::db();
        $data = array (
	        'cancel' => $date,
        );
        $where = array (
	        'bID' =>$bID,
	        'uID' =>$uID,
        );
        $db->update('MagnettyEventAttend', $data, $where);
        //$db-> update('MagnettyEventAttend SET cancel = ?, WHERE bID = ? AND uID = ?', array($date, $bID, $uID));
        return;
    }

    public static function recoverRSVP($bID, $uID, $date)
    {
        $db = Loader::db();
        $null = null;
        $db-> Execute('UPDATE MagnettyEventAttend SET cancel = ? AND rsvp = ? WHERE bID = ? AND uID = ?', array($null, $date, $bID, $uID));
        return;
    }

    public static function getRSVPTicketList($bID)
    {
        $db = Loader::db();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE bID = ? AND cancel IS NULL ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $query);
        return $query;
    }

    public static function getWaitList($bID)
    {
        $db = Loader::db();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE bID = ? AND waitlist IS NOT NULL ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $query);
        return $query;
    }

    public static function getCancelTicketList($bID)
    {
        $db = Loader::db();
        $query = $db->GetAll('SELECT * from MagnettyEventAttend WHERE bID = ? AND cancel IS NOT NULL ORDER BY sortOrder', array($this->bID));
        $this->set('rows', $query);
        return $query;
    }



}
