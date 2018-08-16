<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Model\Product\Option\Type;

use Magento\Framework\Exception\LocalizedException;

class FileWithAttachment extends \Magento\Catalog\Model\Product\Option\Type\File
{
    /**
     * Url for custom option download controller
     * @var string
     */
    protected $_customOptionDownloadUrl = 'wizzaro_magentofilewithattachmentcustomoptiontype/download/customOption';

    /**
     * Format File option html
     *
     * @param string|array $optionValue Serialized string of option data or its data array
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getOptionHtml($optionValue)
    {
        $value = $this->_unserializeValue($optionValue);
        try {
            $urlRoute = !empty($value['url']['route']) ? $value['url']['route'] : '';
            $urlParams = !empty($value['url']['params']) ? $value['url']['params'] : '';
            $title = !empty($value['title']) ? $value['title'] : '';

            return sprintf(
                '<a href="%s" target="_blank">%s</a>',
                $this->_getOptionDownloadUrl($urlRoute, $urlParams),
                $this->_escaper->escapeHtml($title)
            );
        } catch (\Exception $e) {
            throw new LocalizedException(__('The file options format is not valid.'));
        }
    }
}