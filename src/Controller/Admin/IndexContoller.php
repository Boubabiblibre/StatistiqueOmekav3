<?php

namespace SitesStatis\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        
        $statistics = [
            'totalItems' => 150,
            'totalCollections' => 20,
            'totalUsers' => 10,
        ];

        $view = new ViewModel($statistics);
        return $view;
    }
}
