<?php
class Dannevang_Transfeedisplay_Block_Sales_Order_Totals_Transfeedisplay extends Mage_Core_Block_Abstract
{
    private function _getTransactionFee()
    {
        $orderClass = get_class(Mage::getModel('sales/order'));
        $order = $this->getParentBlock()->getOrder();
        if ($orderClass == get_class($order))
        {
            if ($incrementId = $order->getIncrementId())
            {
                $read = Mage::getSingleton('core/resource')->getConnection('core_read');
                $query = "select * from epay_order_status where orderid = '" . $incrementId . "'";
                $row = $read->fetchRow($query);
                if (count($row))
                {
                    if ($amount = (float)$row['transfee'] / 100)
                    {
                        return $amount;
                    }
                }
            }
        }
        return false;
    }

    public function initTotals()
    {
        if ($amount = $this->_getTransactionFee())
        {
            $total = new Varien_Object(array(
                'code'  => 'transfee',
                'field' => 'transfee_amount',
                'value' => $amount,
                'label' => $this->getLabel()
            ));
            $this->getParentBlock()->addTotal($total, 'tax');
        }
        return $this;
    }

    public function getLabel()
    {
        return $this->__('Card Fee');
    }

    public function getTransactionFee()
    {
        return ($transfee = $this->_getTransactionFee()) ? $transfee : false;
    }
}
