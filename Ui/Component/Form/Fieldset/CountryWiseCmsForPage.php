<?php

namespace Suson\CountryWiseCms\Ui\Component\Form\Fieldset;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\Fieldset;
use Suson\CountryWiseCms\Helper\ConfigHelper;

class CountryWiseCmsForPage extends Fieldset
{
    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    public function __construct(
        ConfigHelper $configHelper,
        ContextInterface $context,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $components, $data);
        $this->configHelper = $configHelper;
    }

    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        parent::prepare();
        $this->_data['config']['componentDisabled'] = !$this->configHelper->isEnabledInPage();
    }
}