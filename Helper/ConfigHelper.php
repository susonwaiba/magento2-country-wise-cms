<?php

namespace Suson\CountryWiseCms\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class ConfigHelper extends AbstractHelper
{

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue('country_wise_cms/general/' . $code, $storeId);
    }

    /**
     * Returns if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getGeneralConfig('enable');
    }

    /**
     * Returns if module and page effect is enabled
     *
     * @return bool
     */
    public function isEnabledInPage()
    {
        if ($this->isEnabled()) {
            return $this->getGeneralConfig('enable_in_page');
        }
        return false;
    }

    /**
     * Returns if module and block effect is enabled
     *
     * @return bool
     */
    public function isEnabledInBlock()
    {
        if ($this->isEnabled()) {
            return $this->getGeneralConfig('enable_in_block');
        }
        return false;
    }
}