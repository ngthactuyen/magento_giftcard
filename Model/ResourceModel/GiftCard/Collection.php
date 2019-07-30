<?php
/**
 * Created by PhpStorm.
 * User: Tuyen
 * Date: 14-Jun-19
 * Time: 10:35 AM
 */
namespace Mageplaza\GiftCard\Model\ResourceModel\GiftCard;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'giftcard_id';
    protected $_eventPrefix = 'mageplaza_giftcard_collection';
    protected $_eventObject = 'giftcard_collection';

    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\GiftCard', 'Mageplaza\GiftCard\Model\ResourceModel\GiftCard');
    }
}