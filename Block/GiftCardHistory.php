<?php
namespace Mageplaza\GiftCard\Block;

use Magento\Framework\View\Element\Template;

class GiftCardHistory extends \Magento\Framework\View\Element\Template
{
    protected $_currentCustomer;
    protected $_customerFactory;
    protected $_formatCurrency;
    protected $_configData;
    protected $_giftcardHistoryFactory;
    protected $_resourceConnect;

    public function __construct(
        Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $formatCurrency,
        \Mageplaza\GiftCard\Helper\Data $configData,
        \Mageplaza\GiftCard\Model\GiftCardHistoryFactory $giftCardHistoryFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        array $data = []
    )
    {
        $this->_resourceConnect = $resourceConnection;
        $this->_giftcardHistoryFactory = $giftCardHistoryFactory;
        $this->_formatCurrency = $formatCurrency;
        $this->_currentCustomer = $currentCustomer;
        $this->_customerFactory = $customerFactory;
        $this->_configData = $configData;
        parent::__construct($context, $data);
        $this->pageConfig->getTitle()->set('My Gift Card');
    }
    public function getCustomer(){
        return $this->_currentCustomer->getCustomer();
    }

    public function getBalance(){
        $model = $this->_customerFactory->create()->load($this->getCustomer()->getId());
        return $model->getData('giftcard_balance');
    }

    public function getFormatBalance($balance){
        return $this->_formatCurrency->convertAndFormat($balance);
    }

    public function getConfigData(){
        return $this->_configData->getConfigValue('giftcard/general/allow_redeem');
    }

    public function getGiftCardHistoryCollection(){
        $giftcardHistoryCollection = $this->_giftcardHistoryFactory->create()->getCollection();
        $giftcardHistoryCollection->getSelect()->join(
            ['second_table' => $this->_resourceConnect->getTableName('giftcard_code')],
            'main_table.giftcard_id = second_table.giftcard_id'
        )->where(
            'main_table.customer_id = ?',
            $this->getCustomer()->getId()
        );
        return $giftcardHistoryCollection;
    }
}