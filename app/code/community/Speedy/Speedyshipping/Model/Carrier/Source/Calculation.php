<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Calculation
 *
 * @author killer
 */
class Speedy_Speedyshipping_Model_Carrier_Source_Calculation {

    //put your code here

    public function toOptionArray() {
        $arr = array(1=>'Спиди калкулатор',
                     2=>'Фиксирана цена',
                     3=>'Спиди калкулатор + надбавка за обработка');
        $options = array();

        foreach ($arr as $key=>$value) {
            $options[] = array('value' => $key, 'label' => $value);
        }

        return $options;
    }

}

?>
