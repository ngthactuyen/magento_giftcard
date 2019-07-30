<?php
namespace Mageplaza\GiftCard\Observer;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Math\Random;

class BuyGiftCard implements ObserverInterface
{
    protected $giftcardFactory;
    protected $productFactory;
    protected $helperData;
    protected $giftcardHistoryFactory;
    protected $customerFactory;
    protected $resourceCustomer;

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Mageplaza\GiftCard\Model\GiftCardHistoryFactory $giftCardHistoryFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Customer $resourceCustomer
    )
    {
        $this->resourceCustomer = $resourceCustomer;
        $this->customerFactory = $customerFactory;
        $this->giftcardHistoryFactory = $giftCardHistoryFactory;
        $this->helperData = $helperData;
        $this->productFactory = $productFactory;
        $this->giftcardFactory = $giftCardFactory;
    }

    public function execute(Observer $observer)
    {
        // TODO: Implement execute() method.

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/testObserverBuyGift.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);


        $itemsList = $observer->getQuote()->getAllItems();
        $code_length = $this->helperData->getConfigValue('giftcard/code/code_length');
        $random = new \Magento\Framework\Math\Random();
        $customer_id = $observer->getOrder()->getCustomerId();

        foreach ($itemsList as $item)
        {
            $giftcard_amount = $this->productFactory->create()->load($item->getProductId())->getDataByKey('giftcard_amount');
            if ($item->getProductType() == 'virtual' && $giftcard_amount > 0){
                for ($i = 0; $i < $item->getQty(); $i++){
//                  add data giftcard_code
                    $giftcard = $this->giftcardFactory->create()->addData(
                        [
                            'code' => $random->getRandomString($code_length, Random::CHARS_DIGITS.Random::CHARS_UPPERS),
                            'balance' => $giftcard_amount,
                            'create_from' => $observer->getOrder()->getIncrementId()
                        ]
                    )->save();

//                    add data giftcard_histiory
                    $this->giftcardHistoryFactory->create()->addData(
                        [
                            'giftcard_id' => $giftcard->getGiftcardId(),
                            'customer_id' => $customer_id,
                            'amount' => $giftcard_amount,
                            'action' => 'create'
                        ]
                    )->save();
                }
            }

        }


    }

}
