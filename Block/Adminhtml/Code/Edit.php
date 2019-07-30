<?php
namespace Mageplaza\GiftCard\Block\Adminhtml\Code;
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'giftcard_id';
//        $this->_objectId = 'id';
        $this->_blockGroup = 'Mageplaza_GiftCard';
        $this->_controller = 'adminhtml_code';

        $this->buttonList->update('save', 'label', __('Save  Gift Card'));
        $this->buttonList->add(
            'save_and_continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ]

        );
    }
}
