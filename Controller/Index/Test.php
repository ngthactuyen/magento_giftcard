<?php
/**
 * Created by PhpStorm.
 * User: Tuyen
 * Date: 14-Jun-19
 * Time: 10:49 AM
 */
namespace Mageplaza\GiftCard\Controller\Index;
use Mageplaza\GiftCard\Model\GiftCardFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Test extends \Magento\Framework\App\Action\Action
{
    protected $_giftcardFactory;
    protected $_pageFactory;
    public function __construct(
        Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftcardFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        parent::__construct($context);
        $this->_giftcardFactory = $giftcardFactory;
        $this->_pageFactory = $pageFactory;
    }

    public function execute()
    {
        $dataUrl = $this->getRequest()->getParams();
        echo '<pre>';
        var_dump($dataUrl);
        echo '</pre>';

        if (!isset($dataUrl['code'])){
            $dataUrl['code'] = null;
        }
        if (!isset($dataUrl['balance'])){
            $dataUrl['balance'] = null;
        }
        if (!isset($dataUrl['amount_used'])){
            $dataUrl['amount_used'] = null;
        }
        if (!isset($dataUrl['create_from'])){
            $dataUrl['create_from'] = null;
        }
        $data = [
            'code' => $dataUrl['code'],
            'balance' => $dataUrl['balance'],
            'amount_used' => $dataUrl['amount_used'],
            'create_from' => $dataUrl['create_from']
        ];

        if (isset($dataUrl['action']) && $dataUrl['action'] == 'insert' && $data['code'] != null && $data['balance'] != null && $data['create_from'] != null ){
            $this->insert($data);
        } elseif (isset($dataUrl['action']) && $dataUrl['action'] == 'insert' && $data['code'] == null && $data['balance'] == null && $data['create_from'] == null ){
                        $this->_redirect('frontgiftcard/index/test/');

        }
        if (isset($dataUrl['action']) && $dataUrl['action'] == 'delete'){
            $this->delete($dataUrl['giftcard_id']);
        }
        if (isset($dataUrl['action']) && $dataUrl['action'] == 'update'){
            $this->update($dataUrl['giftcard_id'], $data);
        }
        return $this->_pageFactory->create();

    }

    public function insert($data = []){
        $model = $this->_giftcardFactory->create();
        $model->addData($data);
        $model->save();
        if ($model->save()){
            echo 'insert successfully';
        }
    }

    public function delete($giftcard_id){
        $model = $this->_giftcardFactory->create()->load($giftcard_id);
        if (!$model->getData()){
            echo 'Can not find row with id = '.$giftcard_id.'<br>';
        } else {
//            echo $giftcard['code'].'<br>';
            $this->_giftcardFactory->create()->load($giftcard_id)->delete();
            echo 'delete sucessfully';
        }

//        Lay ra ten class cua doi tuong
//        print_r(get_class($model));

//        echo '<pre>';
//      Lay ra tat ca cac function cua doi tuong
//        print_r(get_class_methods($model));
//        echo '</pre>';

//        Lay ra id cua ban ghi
//        $model->getData('giftcard_id');
//        $model->getId();
//        $model->getGiftcardId();

    }

    public function update($giftcard_id, $updateData = []){
        $model = $this->_giftcardFactory->create()->load($giftcard_id);
        if (!$model->getData()){
            echo 'Can not find row with id = '.$giftcard_id;
        } else {
            if (!isset($updateData['code'])){
                $updateData['code'] = $model->getCode();
            }
            if (!isset($updateData['balance'])){
                $updateData['balance'] = $model->getBalance();
            }
            if (!isset($updateData['amount_used'])){
                $updateData['amount_used'] = $model->getData('amount_used');
            }
            if (!isset($updateData['create_from'])){
                $updateData['create_from'] = $model->getData('create_from');
            }
            $model->addData($updateData);
            $model->save();
            echo 'update successfully';
        }
    }
}