<?php
/**
 * Copyright © Wizzaro. All rights reserved.
 */
namespace Wizzaro\MagentoFileWithAttachmentCustomOptionType\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions;

use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\DataType\Media;

use Wizzaro\MagentoFileWithAttachmentCustomOptionType\Component\Form\Element\File;

class FileWithAttachmentCustomOptions extends AbstractModifier
{
    const FIELD_ATTACHMENT_INFO_NAME = 'attachment_info';
    const FIELD_ATTACHMENT_LABEL_NAME = 'attachment_label';
    const FIELD_ATTACHMENT_PATH_NAME = 'attachment_path';

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $this->createFilewithattachmentCustomOptions();

        return $this->meta;
    }

    /**
     * Create filewithattachment panel
     *
     * @return $this
     */
    protected function createFilewithattachmentCustomOptions()
    {
        if (isset($this->meta[CustomOptions::GROUP_CUSTOM_OPTIONS_NAME])) {
            $this->meta = array_replace_recursive(
                $this->meta,
                [
                    CustomOptions::GROUP_CUSTOM_OPTIONS_NAME => [
                        'children' => [
                            CustomOptions::GRID_OPTIONS_NAME => $this->getOptionsGridConfig()
                        ]
                    ]
                ]
            );
        }

        return $this;
    }

    /**
     * Get config for the whole grid
     *
     * @return array
     */
    protected function getOptionsGridConfig()
    {
        return [
            'children' => [
                'record' => [
                    'children' => [
                        CustomOptions::CONTAINER_OPTION => [
                            'children' => [
                                CustomOptions::CONTAINER_COMMON_NAME => $this->getCommonContainerConfig(),
                                CustomOptions::CONTAINER_TYPE_STATIC_NAME => $this->getStaticTypeContainerConfig(20),
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * Get config for container with common fields for any type
     *
     * @return array
     */
    protected function getCommonContainerConfig()
    {
        return [
            'children' => [
                CustomOptions::FIELD_TYPE_NAME => $this->getTypeFieldConfig()
            ]
        ];
    }

    /**
     * Get config for "Option Type" field
     *
     * @return array
     */
    protected function getTypeFieldConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'groupsConfig' => [
                            'filewithattachment' => [
                                'values' => ['filewithattachment'],
                                'indexes' => [
                                    CustomOptions::CONTAINER_TYPE_STATIC_NAME,
                                    self::FIELD_ATTACHMENT_INFO_NAME,
                                    self::FIELD_ATTACHMENT_LABEL_NAME,
                                    self::FIELD_ATTACHMENT_PATH_NAME,
                                    \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions::FIELD_FILE_EXTENSION_NAME
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for container with fields for all types except "select"
     *
     * @param int $sortOrder
     * @return array
     * @since 101.0.0
     */
    protected function getStaticTypeContainerConfig($sortOrder)
    {
        return [
            'children' => [
                self::FIELD_ATTACHMENT_INFO_NAME => $this->getAttachmentInfoFieldConfig(10),
                static::FIELD_ATTACHMENT_LABEL_NAME => $this->getAttachmentLabelFieldConfig(20),
                static::FIELD_ATTACHMENT_PATH_NAME => $this->getAttachmentPathFieldConfig(30),
            ]
        ];
    }

    /**
     * Get config for "File Extension" field
     *
     * @param int $sortOrder
     * @return array
     * @since 101.0.0
     */
    protected function getAttachmentInfoFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Attachment Info'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_ATTACHMENT_INFO_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "File Extension" field
     *
     * @param int $sortOrder
     * @return array
     * @since 101.0.0
     */
    protected function getAttachmentLabelFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Attachment Label'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_ATTACHMENT_LABEL_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "File Extension" field
     *
     * @param int $sortOrder
     * @return array
     * @since 101.0.0
     */
    protected function getAttachmentPathFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Attachment'),
                        'componentType' => Field::NAME,
                        'formElement' => File::NAME,
                        'dataScope' => static::FIELD_ATTACHMENT_PATH_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder
                    ],
                ],
            ],
        ];
    }
}