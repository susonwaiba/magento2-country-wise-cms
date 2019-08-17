<?php

namespace Suson\CountryWiseCms\Model\Ui\Source;

class Countries extends \Magento\Directory\Model\Config\Source\Country
{
    const DEFAULT = '0';

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray($isMultiselect = false, $foregroundCountries = '')
    {
        $data = parent::toOptionArray($isMultiselect, $foregroundCountries);
        if (isset($data['0'])) {
            unset($data[0]);
        }
        array_unshift($data, [
            'value' => self::DEFAULT,
            'label' => __('All Countries')
        ]);
        return $data;
    }
}