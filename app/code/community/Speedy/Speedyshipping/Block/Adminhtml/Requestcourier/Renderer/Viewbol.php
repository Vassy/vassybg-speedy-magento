<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vieworder
 *
 * @author killer
 */
class Speedy_Speedyshipping_Block_Adminhtml_Requestcourier_Renderer_Viewbol 
extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
    //put your code here
    
    public function render(Varien_Object $row)
    {
        
        $targetUrl = Mage::helper("adminhtml")->getUrl('speedyshipping/print/printLabel/',
                                         array(
                                             'order_id'=>(int)$row->getOrderId()));
        $funcName = "popWin('".$targetUrl."')";
         return $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => $this->__("View Bill Of Lading"),
                'onclick' => $funcName
            ))
            ->toHtml();
    }
}

?>
