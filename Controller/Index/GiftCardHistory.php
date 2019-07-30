<?php
namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Context;

class GiftCardHistory extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_customer;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
    )
    {
        $this->_customer = $currentCustomer;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
//        var_dump($this->getRequest()->getFullActionName());
        if ($this->_customer->getCustomerId() == null){
//            $this->messageManager->addErrorMessage('Fail');
            $this->_redirect('customer/account/login');
        } else {
            return $this->_resultPageFactory->create();
        }
        // TODO: Implement execute() method.
    }
}