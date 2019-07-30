<?php
namespace Mageplaza\GiftCard\Model\Total\Quote;
use Magento\Checkout\Model\Session;

/**
 * Class Custom
 * @package Mageplaza\HelloWorld\Model\Total\Quote
 */
class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $_priceCurrency;
    protected $checkoutSession;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ){
        $this->checkoutSession = $checkoutSession;
        $this->_priceCurrency = $priceCurrency;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $baseDiscount = $this->checkoutSession->getData('giftcard_discount');
        $discount =  $this->_priceCurrency->convert($baseDiscount);
        $total->addTotalAmount('custom_discount', - $discount);
        $total->addBaseTotalAmount('custom_discount', - $baseDiscount);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
        $quote->setCustomDiscount(- $discount);

//        $writer = new \Zend\Log\Writer\Stream(BP.'/var/log/testCustomDiscount.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info(
//            'test Custom Discount',
//            [
//                'total amount' => $total->getTotalAmount('custom_discount'),
//                'base total amount' => $total->getBaseTotalAmount('custom_discount'),
//                'base grand total' => $total->getBaseGrandTotal(),
//                'custom discount' => $quote->getCustomDiscount(),
//                'total amount of grand_total' => $total->getTotalAmount('grand_total')
////                'all total amount' => $total->getAllTotalAmounts(),
////                'all base total amount' => $total->getAllBaseTotalAmounts()
////                'method class quote' => get_class_methods($quote)
//            ]
//        );

        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $result = null;
        $amount = $this->checkoutSession->getData('giftcard_discount');
        if ($amount != 0) {
            $result = [
                'code' => 'customer_discount',
                'title' => __('Gift Card'),
                'value' => $amount
            ];
        }
        return $result;
    }
}