<?php

namespace Suson\CountryWiseCms\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Psr\Log\LoggerInterface;
use Suson\CountryWiseCms\Helper\ConfigHelper;
use Suson\CountryWiseCms\Model\ResourceModel\PageCountryLink;

class PageCollectionFilterObserver implements ObserverInterface
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
            $event = $observer->getEvent();

            /** @var AbstractCollection $collection */
            $collection = $event->getPageCollection() ?: $event->getCollection();
            $this->pageCountryLink->addPageCountriesFilter($collection->getSelect(), ['ALL']);
        } catch (\Exception $e) {
            $this->logger->info('Suson_CountryWiseCms::Observer_PageCollectionFilterObserver: ' . $e->getMessage());
        }
    }
}