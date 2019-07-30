<?php
namespace Mageplaza\GiftCard\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getConfigValue($fieldTree){
        return $this->scopeConfig->getValue($fieldTree, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}