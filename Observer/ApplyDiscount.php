<?php
namespace Mageplaza\GiftCard\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;

class ApplyDiscount implements ObserverInterface
{
    protected $request;
    protected $giftcardFactory;
    protected $message;
    protected $url;
    protected $redirect;
    protected $actionFlag;
    protected $checkoutSession;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\RequestInterface $request,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->actionFlag = $actionFlag;
        $this->url = $urlInterface;
        $this->redirect = $redirect;
        $this->message = $messageManager;
        $this->giftcardFactory = $giftCardFactory;
        $this->request = $request;
    }

    public function execute(Observer $observer)
    {
        $this->checkoutSession->unsetData('giftcard_code');
        $this->checkoutSession->unsetData('giftcard_discount');

        $coupon_code = $this->request->getParam('coupon_code');
        $giftcardList = $this->giftcardFactory->create()->getCollection();
        $flag = 0;
        foreach ($giftcardList as $giftcard){
            if ($coupon_code == $giftcard->getCode()){
                $flag = 1;
                $this->checkoutSession->setData('giftcard_code', $coupon_code);
                $this->checkoutSession->setData('giftcard_discount', $giftcard->getBalance() - $giftcard->getAmountUsed());
            }
        }

        if ($flag == 1){
            $this->actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            $this->message->addSuccessMessage('Gift code applied successfully');
            $redirectUrl = $this->url->getUrl('checkout/cart');
            $observer->getControllerAction()->getResponse()->setRedirect($redirectUrl);
        }

    }



}
