<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Controller\Download;

use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Framework\Controller\Result\ForwardFactory;

/**
 * Class DownloadCustomOption
 * @package Wizzaro\MagentoFileWithAttachmentCustomOptionType\Controller\Download
 */
class Attachment extends \Magento\Framework\App\Action\Action
{
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Sales\Model\Download
     */
    protected $download;

    /**
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param \Magento\Sales\Model\Download $download
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        \Magento\Sales\Model\Download $download
    ) {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->download = $download;
    }

    /**
     * Custom options download action
     *
     * @return void|\Magento\Framework\Controller\Result\Forward
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $quoteItemOptionId = $this->getRequest()->getParam('id');
 
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();

        $productOption = $this->_objectManager->create(
            \Magento\Catalog\Model\Product\Option::class
        )->load($quoteItemOptionId);

        if (!$productOption || !$productOption->getId() || $productOption->getType() != 'filewithattachment') {
            return $resultForward->forward('noroute');
        }

        $attachmentPath = $productOption->getAttachmentPath();

        if (mb_strlen($attachmentPath) <= 0) {
            return $resultForward->forward('noroute');
        }

        try {
            $attachmentTitle = explode('/', $attachmentPath);
            $attachmentTitle = end($attachmentTitle);

            $info = [
                'order_path' => $attachmentPath,
                'quote_path' => $attachmentPath,
                'title' => $attachmentTitle
            ];

            $this->download->downloadFile($info);
        } catch (\Exception $e) {
            return $resultForward->forward('noroute');
        }

        $this->endExecute();
    }

    /**
     * Ends execution process
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    protected function endExecute()
    {
        exit(0);
    }
}
