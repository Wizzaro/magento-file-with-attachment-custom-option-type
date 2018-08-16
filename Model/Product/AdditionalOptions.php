<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Model\Product;

class AdditionalOptions
{
    /**
     * Product filewithattachment options group.
     */
    const OPTION_GROUP_FILEWITHATTACHMENT = 'filewithattachment';

    /**
     * Product filewithattachment option type.
     */
    const OPTION_TYPE_FILEWITHATTACHMENT = 'filewithattachment';

    /**
     * @return array
     */
    public function getGroups()
    {
        return [
            self::OPTION_TYPE_FILEWITHATTACHMENT => self::OPTION_GROUP_FILEWITHATTACHMENT
        ];
    }
}
