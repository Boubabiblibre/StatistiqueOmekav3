<?php

namespace SitesStatis\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function indexAction()
    {
        $itemsSetsResp = $this->api()->search('item_sets')->getContent();
        $itemsResp = $this->api()->search('items')->getContent();
        $usersResp = $this->api()->search('users')->getContent();
        $mediaResp = $this->api()->search('media')->getContent();

        $vocabulariesResp = $this->api()->search('vocabularies')->getContent();
        $resourceTemplatesResp = $this->api()->search('resource_templates')->getContent();


        $statistics = [
            'totalItems' => count($itemsResp),
            'totalItemSets' => count($itemsSetsResp),
            'totalUsers' => count($usersResp),
            'totalMedia' => count($mediaResp),
            'totalVocabularies' => count($vocabulariesResp),
            'totalResourceTemplates' => count($resourceTemplatesResp),
        ];

        $directories = [
            OMEKA_PATH . '/application',
            OMEKA_PATH . '/modules',
            OMEKA_PATH . '/themes',
            OMEKA_PATH . '/config',
            OMEKA_PATH . '/vendor',
        ];

        $sites = $this->em->getRepository('Omeka\Entity\Site')->findAll();
        $siteStats = [];
        foreach ($sites as $site) {
            $siteId = $site->getId();
            $siteItems = $this->api()->search('items', ['site_id' => $siteId])->getContent();
            $siteItemSets = $this->api()->search('item_sets', ['site_id' => $siteId])->getContent();
            $siteUsers = $this->api()->search('users', ['site_id' => $siteId])->getContent();
            $siteMedia = $this->api()->search('media', ['site_id' => $siteId])->getContent();
            $totalMediaSize = 0;
            foreach ($siteMedia as $media) {
                $totalMediaSize += $media->size();
            }
            $totalMediaSizeInMB = $totalMediaSize / 1048576;

            $siteStats[] = [
                'site' => $site,
                'totalItems' => count($siteItems),
                'totalItemSets' => count($siteItemSets),
                'totalUsers' => count($siteUsers),
                'totalMedia' => count($siteMedia),
                'totalMediaSize' => number_format($totalMediaSizeInMB, 2) . ' MB',
            ];
        }

        $connection = $this->em->getConnection();
        $query = 'SELECT * FROM module';
        $modules = $connection->fetchAllAssociative($query);

        $enabledModules = [];
        $installedModules = count($modules); 
        $disabledModules = [];

        foreach ($modules as $module) {
            if ($module['is_active'] == 1) { 
                $enabledModules[] = $module['id'];
            } else {
                $disabledModules[] = $module['id'];
            }
        }

        $moduleStatistics = [
            'totalModules' => count($modules),
            'installedModules' => $installedModules,
            'enabledModules' => count($enabledModules),
            'disabledModules' => count($disabledModules),
        ];
        $storageStats = $this->getStorageStatistics();

        $view = new ViewModel();
        $view->setVariable('stats', $statistics);
        $view->setVariable('modules', $moduleStatistics);
        $view->setVariable('sites', $siteStats);
        $view->setVariable('storage', $storageStats);
        return $view;
    }
    private function getStorageStatistics()
    {
        $totalSpace = disk_total_space(OMEKA_PATH);
        $freeSpace = disk_free_space(OMEKA_PATH); 
        $usedSpace = $totalSpace - $freeSpace; 

        return [
            'totalSpace' => number_format($totalSpace / 1048576, 2) . ' MB',
            'freeSpace' => number_format($freeSpace / 1048576, 2) . ' MB',
            'usedSpace' => number_format($usedSpace / 1048576, 2) . ' MB',
        ];
    }   
}