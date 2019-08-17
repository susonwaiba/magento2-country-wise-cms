<?php

namespace Suson\CountryWiseCms\Observer;

use Magento\Cms\Model\Page;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Suson\CountryWiseCms\Helper\ConfigHelper;
use Suson\CountryWiseCms\Model\ResourceModel\PageCountryLink;

class PageLoadObserver implements ObserverInterface
{
    /**
     * @var ConfigHelper
     */
    protected $configHelper;
    /**
     * @var PageCountryLink
     */
    protected $pageCountryLink;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        ConfigHelper $configHelper,
        PageCountryLink $pageCountryLink,
        LoggerInterface $logger
    )
    {
        $this->configHelper = $configHelper;
        $this->pageCountryLink = $pageCountryLink;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->configHelper->isEnabledInPage()) {
            return;
        }

        try {
            /** @var Page $page */
            $page = $observer->getEvent()->getObject();
            $countries = $this->pageCountryLink->loadPageCountries((int)$page->getId());
            $page->setData('countries', $countries);
        } catch (\Exception $e) {
            $this->logger->info('Suson_CountryWiseCms::Observer_PageLoadObserver: ' . $e->getMessage());
        }
    }
}