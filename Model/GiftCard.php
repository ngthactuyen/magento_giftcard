<?php
/**
 * Created by PhpStorm.
 * User: Tuyen
 * Date: 14-Jun-19
 * Time: 10:20 AM
 */
namespace Mageplaza\GiftCard\Model;

class GiftCard extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'mageplaza_giftcard';
    protected $_eventPrefix = 'mageplaza_giftcard';
    protected $_cacheTag = 'mageplaza_giftcard';

    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\ResourceModel\GiftCard');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

}