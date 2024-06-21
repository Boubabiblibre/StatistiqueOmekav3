<?php

namespace SitesStatis\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Entity\Site;


class IndexController extends AbstractActionController
{

    protected $em;

    public function __construct($em)
    {
        $this->em = $em;        
    }

    public function indexAction()
    {

        $statistics = [
            'totalItems' => 150,
            'totalCollections' => 20,
            'totalUsers' => 10,
        ];
        
        $sites = $this->em->getRepository('Omeka\Entity\Site')->findAll();


        $view = new ViewModel();
        $view->setVariable('stats', $statistics);
        $view->setVariable('sites', $sites);
        return $view;
    }
}
