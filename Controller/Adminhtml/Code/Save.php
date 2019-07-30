<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use JsonSchema\Constraints\EnumConstraint;
use Magento\Backend\App\Action;
use Magento\TestFramework\Event\Magento;

class Save extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $_giftcardFactory;
    protected $_random;

    public function __construct(
        Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\Math\Random $random
    )
    {
        $this->_giftcardFactory = $giftCardFactory;
        $this->_random = $random;
        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $dataRequest = $this->getRequest()->getParams();

//        echo '<pre>'

        if (isset($dataRequest['giftcard_id'])){
//            Edit
            if (!isset($dataRequest['code'])){
                $dataRequest['code'] = null;
            }
            if (!isset($dataRequest['balance'])){
                $dataRequest['balance'] = null;
            }
            if (!isset($dataRequest['amount_used'])){
                $dataRequest['amount_used'] = null;
            }
            if (!isset($dataRequest['create_from'])){
                $dataRequest['create_from'] = null;
            }

            $data = [
                'code' => $dataRequest['code'],
                'balance' => $dataRequest['balance'],
                'amount_used' => $dataRequest['amount_used'],
                'create_from' => $dataRequest['create_from']
            ];
            $this->update($dataRequest['giftcard_id'], $data);
            $this->messageManager->addSuccessMessage('Edit successfully');
            if (isset($dataRequest['back']) && $dataRequest['back'] == 'edit'){
//            Save And Edit
                $this->_redirect('giftcard/code/edit/id/'.$dataRequest['giftcard_id']);
            } elseif (!isset($dataRequest['back'])){
//            Save
                $this->_redirect('giftcard/code/index');
            }
        } else {
//          Create New
            $code_length = $this->getRequest()->getParam('code_length');
            $code = $this->_random->getRandomString($code_length, 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789');
//            $code = $this->random_code($code_length);
            $data = [
                'code' => $code,
                'balance' => $dataRequest['balance'],
                'create_from' => 'admin'
            ];
            if (isset($dataRequest['back']) && $dataRequest['back'] == 'edit'){
//            Save And Edit
                $id = $this->insertAndReturnId($data);
                $this->messageManager->addSuccessMessage('Add Success');
                $this->_redirect('giftcard/code/edit/id/'.$id);
            } elseif (!isset($dataRequest['back'])){
//            Save
                $this->insert($data);
                $this->messageManager->addSuccessMessage('Add Success');
                $this->_redirect('giftcard/code/index');
            }
        }
    }

    public function insert($data = []){
        $model = $this->_giftcardFactory->create();
        $model->addData($data);
        $model->save();
    }

    public function insertAndReturnId($data = []){
        $model = $this->_giftcardFactory->create();
        $model->addData($data);
        $model->save();
        return $model->getData('giftcard_id');
    }

    public function update($giftcard_id, $updateData = []){
        $model = $this->_giftcardFactory->create();
        $model->load($giftcard_id);
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


    }
}