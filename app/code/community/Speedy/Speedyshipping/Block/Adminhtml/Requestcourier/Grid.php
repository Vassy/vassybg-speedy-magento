<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 *
 * @author killer
 */
class Speedy_Speedyshipping_Block_Adminhtml_Requestcourier_Grid extends
Mage_Adminhtml_Block_Widget_Grid {

    //put your code here

    public function __construct() {
        parent::__construct();
        $this->setId('BolGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {

        $dateInfo = getdate(time());

        $collection = Mage::getModel('speedyshippingmodule/saveorder')->getCollection();
        $collection->addFieldToFilter('bol_created_day', $dateInfo['mday']);
        $collection->addFieldToFilter('bol_created_month', $dateInfo['mon']);
        $collection->addFieldToFilter('bol_created_year', $dateInfo['year']);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {



        $this->addColumn('bol_id', array(
            'header' => $this->__("View Bill of lading number"),
            'align' => 'right',
            'width' => '10px',
            'index' => 'bol_id',
        ));

        $this->addColumn('bol_created_at', array(
            'header' => $this->__("Time and creation date"),
            'align' => 'left',
            'index' => 'bol_created_at',
            'width' => '50px',
            'renderer' => 'speedyshippingmodule/adminhtml_requestcourier_renderer_created'
        ));

        $this->addColumn('image', array(
            'header' => '',
            'align' => 'left',
            'index' => 'image',
            'renderer' => 'speedyshippingmodule/adminhtml_requestcourier_renderer_cancelbutton'
        ));

        if (!Mage::getStoreConfig('carriers/speedyshippingmodule/bring_to_office') ||
                !Mage::getStoreConfig('carriers/speedyshippingmodule/choose_office')) {
            $this->addColumn('images', array(
                'header' => '',
                'align' => 'left',
                'index' => 'images',
                'renderer' => 'speedyshippingmodule/adminhtml_requestcourier_renderer_requestbutton'
            ));
        }

        $this->addColumn('view_bol', array(
            'header' => '',
            'align' => 'left',
            'index' => 'view_bol',
            'renderer' => 'speedyshippingmodule/adminhtml_requestcourier_renderer_viewbol'
        ));



        $this->addColumn('order', array(
            'header' => '',
            'align' => 'left',
            'index' => 'order',
            'renderer' => 'speedyshippingmodule/adminhtml_requestcourier_renderer_vieworder'
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {

        if (!Mage::getStoreConfig('carriers/speedyshippingmodule/bring_to_office') ||
                !Mage::getStoreConfig('carriers/speedyshippingmodule/choose_office')) {

            $this->setMassactionIdField('speedy_order_id');
            $this->getMassactionBlock()->setFormFieldName('speedy_order_id');

            $this->getMassactionBlock()->addItem('requestcourier', array(
                'label' => $this->__("Request a courier"),
                'url' => $this->getUrl('*/*/massRequest'),
                'confirm' => $this->__("Are you sure, that you want to make a couriter request")
            ));
        }
        return $this;
    }

}

?>
