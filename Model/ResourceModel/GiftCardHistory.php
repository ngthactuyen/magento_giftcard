<?php
namespace Mageplaza\GiftCard\Model\ResourceModel;

class GiftCardHistory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context)
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        // TODO: Implement _construct() method.
        $this->_init('giftcard_history', 'history_id');
    }
}
