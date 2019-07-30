<?php
namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Cache\Frontend\Adapter\Zend;
use Mageplaza\GiftCard\Helper\Data;

class TestEventBuyGiftCard extends \Magento\Framework\App\Action\Action
{
    protected $checkoutSession;
    protected $quantityOfGiftCard;
    protected $balanceOfGiftCard;
    protected $giftcardFactory;
    protected $productFactory;
    protected $giftcardHistoryFactory;
    protected $customerFactory;
    protected $resourceCustomer;
    protected $codelengthConfig;
    protected $resourceGiftCard;
    protected $addGiftCardList = [];

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftcardFactory,
        \Mageplaza\GiftCard\Model\GiftCardHistoryFactory $giftCardHistoryFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Customer $resourceCustomer,
        \Mageplaza\GiftCard\Model\ResourceModel\GiftCard $resourceGiftCard,
        Context $context
    )
    {
        $this->resourceGiftCard = $resourceGiftCard;
        $this->codelengthConfig = $helperData->getConfigValue('giftcard/code/code_length');
        $this->resourceCustomer = $resourceCustomer;
        $this->customerFactory = $customerFactory;
        $this->giftcardHistoryFactory = $giftCardHistoryFactory;
        $this->productFactory = $productFactory;
        $this->giftcardFactory = $giftcardFactory;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }


    public function execute()
    {
        $checkoutSessionData = $this->checkoutSession->getQuote()->getAllItems();
        foreach ($checkoutSessionData as $item){
//            echo '<pre>';
//            var_dump($item->getProductId());
//            var_dump($item->getName());
//            var_dump($item->getProductType());
//            echo 'price: ';
//            var_dump($item->getPrice());
//            echo 'gift card amount: ';
//            var_dump($this->productFactory->create()->load($item->getProductId())->getDataByKey('giftcard_amount'));

            if ($item->getName() == 'Gift Card' && $item->getProductType() == 'virtual'){
//                var_dump($item->getPrice());
                $this->quantityOfGiftCard = $item->getQty();
                $this->balanceOfGiftCard = $this->productFactory->create()->load($item->getProductId())->getDataByKey('giftcard_amount');

            }
//            echo '</pre>';
        }
//        echo 'quantity: '.$this->quantityOfGiftCard.'<br>';
//        echo 'balance: '.$this->balanceOfGiftCard.'<br>';
//        die('12345');


//        $random_code = new \Magento\Framework\Math\Random();
////        $addGiftCardList = [];
//        for ($i = 0; $i< $this->quantityOfGiftCard; $i++){
//            $data = [
//                'code' => $random_code->getRandomString($this->codelengthConfig, 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789'),
//                'balance' => $this->balanceOfGiftCard,
//                'amount_used' => $this->balanceOfGiftCard,
//                'create_from' => 'orderIncrementId'
//            ];
////            $this->resourceGiftCard->getConnection()->insert(
////                $this->resourceGiftCard->getTable('giftcard_code'),
////                [
////                    'code' => $random_code->getRandomString($this->codelengthConfig, 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789'),
////                    'balance' => $this->balanceOfGiftCard,
////                    'amount_used' => $this->balanceOfGiftCard,
////                    'create_from' => $orderIncrementId
////                ]
////            );
//
//            $giftcard = $this->giftcardFactory->create();
//            $giftcard->addData($data);
////            echo $giftcard->getCode();
////            throw new \Exception('Disallowed file type.');
////            die();
//            $giftcard->save();
//            $addGiftCardList[] = $giftcard;
//        }
//        echo '<pre>';
//        foreach ($addGiftCardList as $gift){
//            var_dump($gift->getCode());
//            var_dump($gift->getBalance());
//        }
////        var_dump($addGiftCardList);
//        echo '</pre>';
//
//        die('<br>121');

    }

    public function insertGiftCard($orderIncrementId){
//        Add data gift card code
        $random_code = new \Magento\Framework\Math\Random();
        for ($i = 0; $i< $this->quantityOfGiftCard; $i++){
            $data = [
                'code' => $random_code->getRandomString($this->codelengthConfig, 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789'),
                'balance' => $this->balanceOfGiftCard,
                'amount_used' => $this->balanceOfGiftCard,
                'create_from' => $orderIncrementId
            ];
//            $this->resourceGiftCard->getConnection()->insert(
//                $this->resourceGiftCard->getTable('giftcard_code'),
//                [
//                    'code' => $random_code->getRandomString($this->codelengthConfig, 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789'),
//                    'balance' => $this->balanceOfGiftCard,
//                    'amount_used' => $this->balanceOfGiftCard,
//                    'create_from' => $orderIncrementId
//                ]
//            );

//            $giftcard = $this->giftcardFactory->create();
            $giftcard = $this->giftcardFactory->create()->addData();
            $giftcard->addData($data);
            $giftcard->save();
            $this->addGiftCardList[] = $giftcard;
        }
        $writer = new \Zend\Log\Writer\Stream(BP.'/var/log/test2.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info(
            'test2',
            [
                'add GiftCard List' => $this->addGiftCardList
            ]
        );


//        add data gift card history
//        foreach ($addGiftCardList as $item) {
//            $data = [
//                'giftcard_id' => $item->getGiftCardId(),
//                'customer_id' => $this->checkoutSession->getQuote()->getCustomerId(),
//                'amount' => $item->getBalance(),
//                'action' => 'create'
//            ];
//            $this->giftcardHistoryFactory->create()->addData($data)->save();
//        }


//        update giftcard_balance customer_entity
//        $this->resourceCustomer->getConnection()->update(
//            $this->resourceCustomer->getTable('customer_entity'),
//            [
//                'giftcard_balance' => $this->customerFactory->create()->getGiftcardBalance() + $this->quantityOfGiftCard * $this->balanceOfGiftCard
//            ],
//            $this->resourceCustomer->getConnection()->quoteInto('entity_id = ?', $this->checkoutSession->getQuote()->getCustomerId())
//        );
//        return $addGiftCardList;
    }

    public function test123(){
        return 'test123';
    }
}