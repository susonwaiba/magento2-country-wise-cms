<?php

namespace Suson\CountryWiseCms\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PageCountryLink extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cms_page_country', null);
    }

    /**
     * Load and return countries associated with page
     *
     * @param int $pageId
     *
     * @return array
     * @throws LocalizedException
     */
    public function loadPageCountries(int $pageId): array
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getMainTable(), 'country_id')
            ->where('page_id = ?', $pageId);
        $countries = $this->getConnection()->fetchAll($select);
        if (is_array($countries) && count($countries)) {
            return array_keys(array_column($countries, null, 'country_id'));
        }
        return [];
    }

    /**
     * Update countries associated with page
     *
     * @param int $pageId
     * @param array $countries
     *
     * @return void
     * @throws LocalizedException
     */
    public function savePageSegments(int $pageId, array $countries)
    {
        foreach ($countries as $country) {
            if ($countries) {
                $this->getConnection()->insertOnDuplicate(
                    $this->getMainTable(),
                    ['page_id' => $pageId, 'country_id' => $country],
                    ['page_id']
                );
            }
        }

        if (count($countries)) {
            $this->getConnection()->delete(
                $this->getMainTable(),
                ['page_id = ?' => $pageId, 'country_id NOT IN (?)' => $countries]
            );
        }
    }

    /**
     * Limit scope of a select object
     *
     * @param Select $select
     * @param array $countries
     *
     * @throws LocalizedException
     */
    public function addPageCountriesFilter(Select $select, array $countries)
    {
        $select->joinLeft(
            ['page_countries' => $this->getMainTable()],
            'page_countries.page_id = main_table.page_id',
            []
        );

        if ($countries) {
            $select->where('page_countries.country_id IS NULL OR page_countries.country_id IN (?)', $countries);
        } else {
            $select->where('page_countries.country_id IS NULL');
        }
    }
}