<?php
namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Context;

class RedeemSave extends \Magento\Framework\App\Action\Action
{
    protected $_giftcardHistoryFactory;
    protected $_customerFactory;
    protected $_giftcardFactory;
    protected $_currentCustomer;
    protected $_resourceCustomer;

    public function __construct(
        Context $context,
        \Mageplaza\GiftCard\Model\GiftCardHistoryFactory $giftcardHistoryFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Model\ResourceModel\Customer $resourceCustomer,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftcardFactory
    )
    {
        $this->_giftcardHistoryFactory = $giftcardHistoryFactory;
        $this->_customerFactory = $customerFactory;
        $this->_giftcardFactory = $giftcardFactory;
        $this->_currentCustomer = $currentCustomer;
        $this->_resourceCustomer = $resourceCustomer;
        parent::__construct($context);
    }

    public function execute()
    {
        $writer = new \Zend\Log\Writer\Stream(BP.'/var/log/testRedeemSave.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        // TODO: Implement execute() method.
        $dataRequest = $this->getRequest()->getParams();
        $customer_id = $this->_currentCustomer->getCustomerId();
        $giftcard = $this->_giftcardFactory->create()->load($dataRequest['code'], 'code');
        $flag = 0;

//        Check giftcard exist or not
        if (!$giftcard->getData()) {
            $this->messageManager->addErrorMessage('Gift Card Code '.$dataRequest['code'].' does not exist');
        } else {
//            Check balance of giftcard can be used
            if ($giftcard['balance'] - $giftcard['amount_used'] == 0){
                $this->messageManager->addErrorMessage('Gift Card Code '.$giftcard['code'].' was used');
            } else {
//                update data giftcard_code: amount_used new = amount_used old + redeem amount
                $giftcard->addData([
                    'amount_used' => $giftcard['amount_used'] + $giftcard['balance']
                ]);
                $giftcard->save();

//                update data customer_entity: giftcard_balance new = giftcard_balance old + redeem amount
                $customer = $this->_customerFactory->create()->load($customer_id);
                $this->_resourceCustomer->getConnection()->update(
                    $this->_resourceCustomer->getTable('customer_entity'),
                    [
                        'giftcard_balance' => $customer->getData('giftcard_balance') + $giftcard['balance']
                    ],
                    $this->_resourceCustomer->getConnection()->quoteInto('entity_id = ?', $customer_id)
                );

                $giftcardHistoryWithGiftCard_Id = $this->_giftcardHistoryFactory->create()->load($giftcard->getId(), 'giftcard_id');
//                Check giftcard in giftcard_history exist or not
                if (!$giftcardHistoryWithGiftCard_Id->getData()){
//                    add data gifcard_history
                    $data = [
                        'giftcard_id' => $giftcard['giftcard_id'],
                        'customer_id' => $customer_id,
                        'amount' => $giftcard['balance'],
                        'action' => 'redeem',
                    ];
                    $giftcardHistory = $this->_giftcardHistoryFactory->create();
                    $giftcardHistory->addData($data);
                    $giftcardHistory->save();
                    $flag = 1;
                } else {
//                    check owner of giftcard, update data gifcard_history
                    if ($giftcardHistoryWithGiftCard_Id->getAction() == 'create' && $giftcardHistoryWithGiftCard_Id->getCustomerId() == $customer_id){
//                        update data gifcard_history
                        $giftcardHistoryWithGiftCard_Id->addData(
                            [
                                'action' => 'redeem'
                            ]
                        )->save();
                        $flag = 1;
                    }
                    if ($giftcardHistoryWithGiftCard_Id->getAction() == 'create' && $giftcardHistoryWithGiftCard_Id->getCustomerId() != $customer_id){
//                        add data gifcard_history
                        $this->_giftcardHistoryFactory->create()->addData(
                            [
                                'giftcard_id' => $giftcard['giftcard_id'],
                                'customer_id' => $customer_id,
                                'amount' => $giftcard['balance'],
                                'action' => 'redeem',
                            ]
                        )->save();
                        $flag = 1;
                    }
                }

                if ($flag == 1){
                    $this->messageManager->addSuccessMessage('Redeem Code '.$giftcard['code'].' Successfully ');
                } else {
                    $this->messageManager->addErrorMessage('Redeem Fail');
                }
            }
        }
        $this->_redirect('frontgiftcard/index/giftcardhistory');
    }
}
