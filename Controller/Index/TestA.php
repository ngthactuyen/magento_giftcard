<?php
namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Context;

class TestA extends \Magento\Framework\App\Action\Action
{
    protected $_giftcardHistoryFactory;

    public function __construct(
        Context $context,
        \Mageplaza\GiftCard\Model\GiftCardHistoryFactory $giftcardHistoryFactory
    )
    {
        $this->_giftcardHistoryFactory = $giftcardHistoryFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $history = $this->_giftcardHistoryFactory->create()->load(1);
        $giftcard_id = $history->getData('giftcard_id');

        echo '<pre>';
        var_dump($giftcard_id);
        echo '</pre>';

        die('testA');
    }
}