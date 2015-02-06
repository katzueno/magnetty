<?php 
namespace Concrete\Package\Magnetty\Controller\SinglePage\Dashboard\Magnetty;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Package;

class Settings extends DashboardPageController {

    public function view() {
		
        $pkg = Package::getByHandle('magnetty');
        $adminEmail = $pkg->getConfig()->get('magnetty.adminEmail');
        $allowCancel = $pkg->getConfig()->get('magnetty.allowCancel');
        $emailConfirmationText = $pkg->getConfig()->get('magnetty.emailConfirmationText');
        $emailWaitlistText = $pkg->getConfig()->get('magnetty.emailWaitlistText');
        $emailCancelText = $pkg->getConfig()->get('magnetty.emailCancelText');
		$this->set('adminEmail', $adminEmail);
		$this->set('allowCancel', $allowCancel);
		$this->set('emailWaitlistText', $emailConfirmationText);
		$this->set('emailConfirmationText', $emailConfirmationText);
		$this->set('adminemailCancelText', $emailCancelText);

    }

    public function updated()
    {
        $this->set('message', t("Settings saved."));    
        $this->view();
    }

    public function save_settings()
    {
        //if ($this->token->validate("save_settings")) {
            if ($this->isPost()) {
		        $adminEmail = $this->post('adminEmail');
		        $allowCancel = $this->post('allowCancel');
		        $emailConfirmationText = $this->post('emailConfirmationText');
		        $emailCancelText = $this->post('emailCancelText');
                $pkg = Package::getByHandle('magnetty');
                $pkg->getConfig()->save('magnetty.adminEmail', $adminEmail);
                $pkg->getConfig()->save('magnetty.allowCancel', $allowCancel);
                $pkg->getConfig()->save('magnetty.emailConfirmationText', $emailConfirmationText);
                $pkg->getConfig()->save('magnetty.emailWaitlistText', $emailWaitlistText);
                $pkg->getConfig()->save('magnetty.emailCancelText', $emailCancelText);
                $this->redirect('/dashboard/magnetty/settings','updated');
            }
        // } else {
        //     $this->set('error', array($this->token->getErrorMessage()));
        }
    }

}