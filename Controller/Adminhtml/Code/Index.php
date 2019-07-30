<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend('Gift Cards');
//        $resultPage->getConfig()->getTitle()->set('Gift Cards');

        return $resultPage;
    }
}
