<?php 
namespace Concrete\Package\Magnetty;

use Package;
use BlockType;
use SinglePage;
use Page;
use View;
use Loader;
use User;
use UserInfo;
use Config;
use Token
use \Concrete\Package\Magnetty\Models\Magnetty;

/**
 * Magnetty
 *
 * Event RSVP and ticketing system
 *
 * concrete5.7.3 and higher
 *
 * LICENSE: concrete5 Marketplace Commercial Lisence
 *
 * @category   Social Networking
 * @package    Magnetty
 * @author     Katz Ueno <iam@katzueno.com>
 * @copyright  2014 Katz Ueno
 * @license    concrete5 Marketplace Commercial Lisence
 * @version    0.0.1
 */

class Controller extends Package
{

    protected $pkgHandle = 'magnetty';
    protected $appVersionRequired = '5.7.2';
    protected $pkgVersion = '0.0.1';

    public function getPackageDescription()
    {
        return t("Event RSVP and ticketing system for concrete5");
    }

    public function getPackageName()
    {
        return t("Magnetty Events");
    }

    public function install()
    {

        $pkg = parent::install();

        //install blocks
        BlockType::installBlockTypeFromPackage('magnetty_ticket', $pkg);
        BlockType::installBlockTypeFromPackage('magnetty_rsvp_list', $pkg);

        $this->install_attributes($pkg);


        // install pages
        $cp = SinglePage::add('/dashboard/magnetty', $pkg);
        $cp = Page::getByPath('/dashboard/magnetty');
        $cp->update(array('cName' => t('Magnetty'), 'cDescription' => t('Event RSVP and ticketing system for concrete5')));

        $pes = SinglePage::add('/dashboard/magnetty/settings', $pkg);
        $pes = Page::getByPath('/dashboard/magnetty/settings');
        $pes->setAttribute($iak, 'icon-wrench');


        $this->setDefaults();

    }

    public function uninstall()
    {
        /*
        $results= Page::getByPath('/event');
        $results->delete();
        $db= Loader::db();
        $db->Execute("DELETE from btProEventDates");
        */

        parent::uninstall();
    }

    public function upgrade()
    {

        $db = Loader::db();
        $pkg = Package::getByHandle('magnetty');
        parent::upgrade();

    }



    function setDefaults()
    {

        /*$args = array(
            'AdminGroups' => '3',
            'AllowCancel' => '1',
            'showTooltips' => true,
            'emailConfirmationText' => '',
            'emailCancelText' => '',
        );

        $db = Loader::db();

        $db->Execute("DELETE FROM MagnettyEventConfig");

        $db->insert('MagnettyEventConfig', $args);*/

		$adminUser = UserInfo::getByID(USER_SUPER_ID);
		if (is_object($adminUser)) {
        	$adminUserEmail = $adminUser->getUserEmail();
    	} else {
        	throw new Exception(t("Oops, something is wrong with the Magnetty Ticket Block. Please tell your webmaster the following error message:") . t('From Email address cannot be set'));
    	}
    	
    	$defaultConfirmationText = t("You have successfully RSVPed the event. Thank you.");
    	$defaultWaitlistText = t("We're afraid that the event that you are trying to RSVP was full. We've added you to the wait list. If someone cancelled, we will add you to the RSVP list. Thank you.");
    	$defaultCancelTextText = t("You have successfully cancelled the event. Thank you.");

        $pkg = Package::getByHandle('magnetty');
        $pkg->getConfig()->save('magnetty.allowCancel', true);
        $pkg->getConfig()->save('magnetty.adminEmail', $adminUserEmail);
        $pkg->getConfig()->save('magnetty.emailConfirmationText', $defaultConfirmationText);
        $pkg->getConfig()->save('magnetty.emailWaitlistText', $defaultWaitlistText);
        $pkg->getConfig()->save('magnetty.emailCancelText', $defaultCancelTextText);

    }

}

?>