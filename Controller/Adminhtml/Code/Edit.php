<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use Mageplaza\GiftCard\Model\GiftCardFactory;

class Edit extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;
    protected $_giftcardFactory;
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_giftcardFactory = $giftCardFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    public function execute()
    {
//        die('123');
        $resultPage = $this->_resultPageFactory->create();
        $dataRequest = $this->getRequest()->getParams();
        if (isset($dataRequest['id']) || isset($dataRequest['back'])){
            $model = $this->_giftcardFactory->create();
            $code = $model->load($dataRequest['id'])->getData('code');
            $resultPage->getConfig()->getTitle()->prepend('Edit Gift Card Code '.$code);
        }
        if (!isset($dataRequest['id']) && !isset($dataRequest['back'])){
            $resultPage->getConfig()->getTitle()->prepend('New Gift Card');
        }
        return $resultPage;
    }
}
