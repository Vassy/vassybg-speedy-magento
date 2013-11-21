<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Created
 *
 * @author killer
 */
class Speedy_Speedyshipping_Block_Adminhtml_Requestcourier_Renderer_Created
extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    //put your code here
    
    public function render(Varien_Object $row)
    {
        //if($row->getBolCreatedTime()){
            return $row->getBolCreatedDay().
                    '/'.
                    $row->getBolCreatedMonth().
                    '/'.
                    $row->getBolCreatedYear().
                    '/'.
                    $row->getBolCreatedTime();
       // }
    }
}

?>
