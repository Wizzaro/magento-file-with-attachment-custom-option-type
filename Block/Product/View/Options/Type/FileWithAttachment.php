<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Block\Product\View\Options\Type;

class FileWithAttachment extends \Magento\Catalog\Block\Product\View\Options\Type\File
{
    /**
     * Url for attachment download controller
     * @var string
     */
    protected $_attachmentDownloadUrl = 'wizzaro_magentofilewithattachmentcustomoptiontype/download/attachment';

    /**
     * Url
     *
     * @var \Magento\Catalog\Model\Product\Option\UrlBuilder
     */
    protected $_urlBuilder;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Magento\Catalog\Helper\Data $catalogData,
     * @param \Magento\Catalog\Model\Product\Option\UrlBuilder $urlBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Catalog\Model\Product\Option\UrlBuilder $urlBuilder,
        array $data = []
    ) {
        parent::__construct($context, $pricingHelper, $catalogData, $data);
        $this->_urlBuilder = $urlBuilder;
    }

    public function getAttachmentDownloadUrl()
    {
        if (mb_strlen($this->getOption()->getAttachmentPath()) > 0) {
            return $this->_urlBuilder->getUrl($this->_attachmentDownloadUrl, [
                'id' => $this->getOption()->getId()
            ]);
        }

        return null;
    }

    public function getAttachmentTitle()
    {
        $attachmentPath = $this->getOption()->getAttachmentPath();
        $attachmentTitle = explode('/', $attachmentPath);
        $attachmentTitle = $this->escapeHtml(end($attachmentTitle));

        return mb_strlen($attachmentTitle) > 0 ? $attachmentTitle : null;
    }
}
