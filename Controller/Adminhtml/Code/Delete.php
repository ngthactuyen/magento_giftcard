<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use Codeception\Lib\Framework;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Delete extends \Magento\Backend\App\Action
{
    protected $_giftcardFactory;

    public function __construct(
        Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory
    )
    {
        $this->_giftcardFactory = $giftCardFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        // TODO: Implement execute() method.
//        echo '<pre>';
//        var_dump($this->getRequest()->getParams());
//        die('1234');

        $giftcard_id = $this->getRequest()->getParam('id');
        $this->delete($giftcard_id);
        $this->messageManager->addSuccessMessage('Delete Successfully');
        $this->_redirect('giftcard/code/index');
    }

    public function delete($giftcard_id){
        $model = $this->_giftcardFactory->create();
        $model->load($giftcard_id)->delete();
    }
}
