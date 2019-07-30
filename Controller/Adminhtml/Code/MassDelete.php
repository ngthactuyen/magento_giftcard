<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;


/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var \Mageplaza\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param \Mageplaza\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter,
        \Mageplaza\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory $collectionFactory
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

//        echo '<pre>';
//        var_dump(get_class_methods($collection));
//        var_dump($collection->getData());
//        var_dump($this->_request->getParams());
//        die('1234');

        foreach ($collection as $page) {
            $page->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('giftcard/code/index');
    }
}
