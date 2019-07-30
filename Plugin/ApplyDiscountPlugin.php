<?php
namespace Mageplaza\GiftCard\Plugin;

class ApplyDiscountPlugin
{
    protected $checkoutSession;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
    }


    public function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $subject, $result){
        if ($this->checkoutSession->getData('giftcard_code')){
            $result = $this->checkoutSession->getData('giftcard_code');
        }
        return $result;
    }
}