<?php
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Component\Form\Element;

use Magento\Ui\Component\Form\Element\AbstractElement;

/**
 * @api
 * @since 100.0.2
 */
class File extends AbstractElement
{
    const NAME = 'file';

    /**
     * Get component name
     *
     * @return string
     */
    public function getComponentName()
    {
        return static::NAME;
    }
}