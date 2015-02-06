<?php 
namespace Concrete\Package\Magnetty\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;

class MagnettyEvent extends DashboardPageController {

    public function view()
    {
        $this->redirect('/dashboard/magnetty/settings');
    }

}