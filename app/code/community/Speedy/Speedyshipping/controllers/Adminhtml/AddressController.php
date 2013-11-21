<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author killer
 */
class Speedy_Speedyshipping_Adminhtml_AddressController extends Mage_Adminhtml_Controller_Action {

    protected $_speedyEPSInterfaceImplementaion;
    protected $_speedyEPS;
    protected $_speedySessionId;
    protected $_city_id;
    protected $_addressModel;

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {

        parent::__construct($request, $response, $invokeArgs);

        $this->_addressModel = Mage::getModel('speedyshippingmodule/autocomplete_address');
    }

    public function getSiteAction() {
        $result = $this->_addressModel->getSite();

        if (isset($result)) {
            $this->getResponse()->setBody($result);
        }
    }

    public function getOfficesAction() {
        $result = $this->_addressModel->getOffices();

        if (isset($result)) {
            $this->getResponse()->setBody($result);
        }
    }

    public function getQuarterAction() {
        $result = $this->_addressModel->getQuarter();

        if (isset($result)) {
            $this->getResponse()->setBody($result);
        }
    }

    public function getStreetsAction() {
        $result = $this->_addressModel->getStreets();

        if (isset($result)) {
            $this->getResponse()->setBody($result);
        }
    }

    public function getBlockAction() {
        $result = $this->_addressModel->getBlock();

        if (isset($result)) {
            $this->getResponse()->setBody($result);
        }
    }

    protected function _initSpeedyService() {
        $speedyUtil = Mage::getBaseDir('lib') . DS . 'SpeedyEPS' . DS . 'util' . DS . 'Util.class.php';
        $speedyEPSFacade = Mage::getBaseDir('lib') . DS . 'SpeedyEPS' . DS . 'ver01' . DS . 'EPSFacade.class.php';
        $speedyEPSImplementation = Mage::getBaseDir('lib') . DS . 'SpeedyEPS' . DS . 'ver01' . DS . 'soap' . DS . 'EPSSOAPInterfaceImpl.class.php';
        $speedyResultSite = Mage::getBaseDir('lib') . DS . 'SpeedyEPS' . DS . 'ver01' . DS . 'ResultSite.class.php';
        $speedyAddressNomen = Mage::getBaseDir('lib') . DS . 'SpeedyEPS' . DS . 'ver01' . DS . 'AddrNomen.class.php';


        require_once $speedyUtil;
        require_once $speedyEPSFacade;
        require_once $speedyEPSImplementation;
        require_once $speedyResultSite;
        require_once $speedyAddressNomen;


        $user = Mage::getStoreConfig('carriers/speedyshippingmodule/username');
        $pass = Mage::helper('core')->decrypt(Mage::getStoreConfig('carriers/speedyshippingmodule/password'));

        if (!$user || !$pass) {
            return false;
        }

        try {

            $this->_speedyEPSInterfaceImplementaion =
                    new EPSSOAPInterfaceImpl(Mage::getStoreConfig('carriers/speedyshippingmodule/server'));

            $this->_speedyEPS = new EPSFacade($this->_speedyEPSInterfaceImplementaion, $user, $pass);
        } catch (Exception $e) {
            throw new Exception('An error has occured while connecting Speedy');
        }
    }

}

?>
