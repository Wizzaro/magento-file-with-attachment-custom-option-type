<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Model\Product;

use Magento\Framework\App\Filesystem\DirectoryList;
use Wizzaro\MagentoFileWithAttachmentCustomOptionType\Model\Product\AdditionalOptions;
use Exception;

class OptionPlugin
{
    /**
     * @var string
     */
    private $filesDirectory = 'wizzaro/product/options/file/attachments';

    /**
     * @var AdditionalOptions
     */
    private $additionalOptions;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param AdditionalOptions $additionalOptions
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        AdditionalOptions $additionalOptions,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->additionalOptions = $additionalOptions;
        $this->filesystem = $filesystem;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetGroupByType(
        \Magento\Catalog\Model\Product\Option $subject,
        callable $proceed,
        $type = null
    ) {
        $return = $proceed($type);
        if (!$return) {
            $optionGroupsToTypes = $this->additionalOptions->getGroups();
            $return = isset($optionGroupsToTypes[$type]) ? $optionGroupsToTypes[$type] : '';
        }
        return $return;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option $subject
     * @return $this
     */
    public function afterBeforeSave(
        \Magento\Catalog\Model\Product\Option $subject,
        $result
    ) {
        $optionGroupsToTypes = $this->additionalOptions->getGroups();
        if (array_key_exists($subject->getType(), $optionGroupsToTypes)) {

            $attachmentFile = $this->getAttachmentFile();
            
            if (is_array($attachmentFile) && $attachmentFile['error'] == 0) {
                try {
                    $uploader = $this->fileUploaderFactory->create(['fileId' => $attachmentFile]);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->setAllowCreateFolders(true);

                    $path = $this->filesystem->getDirectoryRead(
                        DirectoryList::MEDIA
                    )->getAbsolutePath($this->filesDirectory);

                    $result = $uploader->save($path);

                    $currentAttachmentFilePath = $this->filesystem->getDirectoryRead(
                        DirectoryList::MEDIA
                    )->getAbsolutePath($subject->getData('attachment_path'));

                    if (file_exists($currentAttachmentFilePath) && !is_dir($currentAttachmentFilePath)) {
                        unlink($currentAttachmentFilePath);
                    }

                    $subject->setData('attachment_path', rtrim($this->filesDirectory, '/') . '/' . ltrim($result['file'], '/'));
                } catch (Exception $e) {
                    if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                        throw new Exception($e->getMessage());
                    } else {
                        throw new Exception('TMP NAME EMPTY ERROR');
                    }
                }
            }
        }

        return $result;
    }

    private function getAttachmentFile()
    {
        if (
            array_key_exists('product', $_FILES) &&
            is_array($_FILES['product']) &&
            array_key_exists('name', $_FILES['product']) &&
            is_array($_FILES['product']['name']) &&
            array_key_exists('options', $_FILES['product']['name']) &&
            is_array($_FILES['product']['name']['options'])
        ) {
            foreach ($_FILES['product']['name']['options'] as $key => $value) {
                if (is_array($value) && array_key_exists('attachment_path', $value)) {
                    return [
                        'name' => $_FILES['product']['name']['options'][$key]['attachment_path'],
                        'type' => $_FILES['product']['type']['options'][$key]['attachment_path'],
                        'tmp_name' => $_FILES['product']['tmp_name']['options'][$key]['attachment_path'],
                        'error' => $_FILES['product']['error']['options'][$key]['attachment_path'],
                        'size' => $_FILES['product']['size']['options'][$key]['attachment_path'],
                    ];
                }
            }
        }

        return null;
    }
}
