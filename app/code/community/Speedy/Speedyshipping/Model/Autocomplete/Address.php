<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Address
 *
 * @author killer
 */
class Speedy_Speedyshipping_Model_Autocomplete_Address extends Mage_Core_Model_Abstract {

    //put your code here

    protected $_speedyEPSInterfaceImplementaion;
    protected $_speedyEPS;
    protected $_speedySessionId;
    protected $_city_id;
    protected $_request;

    function __construct() {
        $this->_request = Mage::app()->getRequest();
        $this->_initSpeedyService();
    }

    /**
     * This method loads information about particular site(s), based either on 
     * user input or when the customer is editing an existing address on siteID.
     * 
     * @param type $siteID
     * @return boolean
     */
    public function getSite($siteID = null) {
        $session = Mage::getSingleton('checkout/session');
        $cityName = $this->_request->getParam('term');
        $cityName = Mage::helper('speedyshippingmodule/transliterate')->transliterate($cityName);
        //$city = strtoupper($address->getCity());
        try {
            //Customer is editing an existing address
            if (!is_null($siteID)) {
                $sites = $this->_speedyEPS->getSiteById($siteID);
            } else {
                $sites = $this->_speedyEPS->listSites(null, $cityName);
            }
        } catch (ServerException $se) {
            Mage::log($se->getMessage(),null,'speedyLog.log');
        }
        if (isset($sites)) {
            $tpl = array();

            if (count($sites) == 1 && is_null($siteID)) {
                $nomenclature = $sites[0]->getAddrNomen()->getValue();
                $tpl[] = array('value' => $sites[0]->getId(),
                    'label' => $sites[0]->getType() . ' ' . $sites[0]->getName() . ', ' .
                    ' общ. ' . $sites[0]->getMunicipality() . ', ' . ' обл. ' . $sites[0]->getRegion(),
                    'post_code' => $sites[0]->getPostCode(),
                    'region' => $sites[0]->getRegion(),
                    'is_full_nomenclature' => $sites[0]->getAddrNomen()->getValue(),
                    'is_full_nomenclature' => $nomenclature,
                    'post_code' => $sites[0]->getPostCode(),
                    'region' => $sites[0]->getRegion());
            }else if(count($sites) == 1){
                $nomenclature = $sites->getAddrNomen()->getValue();
                $tpl[] = array('value' => $sites->getId(),
                    'label' => $sites->getType() . ' ' . $sites->getName() . ', ' .
                    ' общ. ' . $sites->getMunicipality() . ', ' . ' обл. ' . $sites->getRegion(),
                    'post_code' => $sites->getPostCode(),
                    'region' => $sites->getRegion(),
                    'is_full_nomenclature' => $sites->getAddrNomen()->getValue(),
                    'is_full_nomenclature' => $nomenclature,
                    'post_code' => $sites->getPostCode(),
                    'region' => $sites->getRegion());
            }
            else {

                foreach ($sites as $site) {

                    $nomenclature = $site->getAddrNomen()->getValue();
                    $tpl[] = array('value' => $site->getId(),
                        'label' => $site->getType() . ' ' . $site->getName() .
                        ', общ. ' . $site->getMunicipality() . ', обл. ' . $site->getRegion(),
                        'post_code' => $site->getPostCode(),
                        'region' => $site->getRegion(),
                        'is_full_nomenclature' => $site->getAddrNomen()->getValue(),
                        'is_full_nomenclature' => $nomenclature,
                        'post_code' => $site->getPostCode(),
                        'region' => $site->getRegion());
                }
            }
            $jsonData = json_encode($tpl);
            //header("Content-Type: text/html; charset=UTF-8");
            return $jsonData;
        } else {
            return FALSE;
        }
    }

    /**
     * This method loads information about available Speedy office(s) (if any) in 
     * particular site. Please note that in order to retriver anything the
     * currently selected site must have either FULL nomenclature.
     * @return boolean
     */
    public function getOffices() {
        //Retrieve the currently selected site id from the request
        $cityId = (int) $this->_request->getParam('cityid', null);
        $officeName = $this->_request->getParam('term');

        $officeName = Mage::helper('speedyshippingmodule/transliterate')->transliterate($officeName);
       if($cityId){
        try {
            $offices = $this->_speedyEPS->listOfficesEx($officeName, $cityId);
        } catch (Exception $e) {
            
        }
       }
        if (isset($offices)) {
            $tpl = array();

            foreach ($offices as $office) {

                
                $label = '';

                $city = '';
                $address = '';
                $note = '';

                $label .= $office->getId() . ' ' . $office->getName();

                /*
                $city .= $office->getAddress()->getSiteType() . ' ' .
                        $office->getAddress()->getSiteName();
*/
                if ($office->getAddress()->getQuarterType()) {


                    $address .= $office->getAddress()->getQuarterType() . ' ';


                    if ($office->getAddress()->getQuarterName()) {
                        $address .= $office->getAddress()->getQuarterName();
                    }
                }



                if ($office->getAddress()->getStreetType()) {


                    $address .= ' ' . $office->getAddress()->getStreetType() . ' ';

                    if ($office->getAddress()->getStreetName()) {
                        $address .= $office->getAddress()->getStreetName();
                    } else if ($office->getAddress()->getStreetName()) {
                        $address .=' ' . $office->getAddress()->getStreetName();
                    }

                    if ($office->getAddress()->getStreetNo()) {
                        $address .= ' № ' . $office->getAddress()->getStreetNo();
                    }
                }

                if ($office->getAddress()->getBlockNo()) {
                    $address .= ' бл. ' . $office->getAddress()->getBlockNo();
                }

                if ($office->getAddress()->getFloorNo()) {
                    $address .= ' ет. ' . $office->getAddress()->getFloorNo();
                }

                if ($office->getAddress()->getApartmentNo()) {
                    $address .= ' ап. ' . $office->getAddress()->getApartmentNo();
                }




                if ($office->getAddress()->getAddressNote()) {

                    $note .= $office->getAddress()->getAddressNote();
                }
/*
                if ($city != '') {

                    $label = $label . ', ' . $city;
                }
*/
                if ($address != '') {
                    $label = $label . ', ' . $address;
                }
                if ($note != '') {
                    $label = $label . ', ' . $note;
                }

                $tpl[] = array('label' =>$office->getId().' '.$office->getName().', '. $office->getAddress()->getFullAddressString(),
                    'value' => $office->getId(),
                    'street_label'=>$label,
                    'site_id' => $office->getAddress()->getResultSite()->getId(),
                    'site_name'=> $office->getAddress()->getResultSite()->getType() . ' ' . $office->getAddress()->getResultSite()->getName() .
                        ', общ. ' . $office->getAddress()->getResultSite()->getMunicipality() . ', обл. ' . $office->getAddress()->getResultSite()->getRegion(),
                    'site_municipality'=>$office->getAddress()->getResultSite()->getMunicipality(),
                    'post_code'=>$office->getAddress()->getPostCode(),
                    'region'=>$office->getAddress()->getResultSite()->getRegion(),
                    'is_full_nomenclature' =>$office->getAddress()->getResultSite()->getAddrNomen()->getValue());
            }

            $jsonData = json_encode($tpl);
            return $jsonData;
        } else {
            return FALSE;
        }
    }

    /**
     * This method loads information about living quarters (if any) in 
     * particular site. Please note that in order to retriver anything the
     * currently selected site must have either FULL or PARTIAL nomenclature.
     * @return boolean
     */
    public function getQuarter() {
        // $address = $this->getOnepage()->getQuote()->getShippingAddress();
        $session = Mage::getSingleton('checkout/session');
        $cityId = (int) $this->_request->getParam('cityid');
        $quarterName = $this->_request->getParam('term');
        $quarterName = Mage::helper('speedyshippingmodule/transliterate')->transliterate($quarterName);
        $currentSpeedyAddress = $session->getSpeedyAddress();
        //$city = strtoupper($address->getCity());
        try {
            $quarters = $this->_speedyEPS->listQuarters($quarterName, $cityId);
        } catch (ServerException $se) {
            Mage::log($se->getMessage(),null,'speedyLog.log');
        }
        if ($quarters) {
            $tpl = array();

            foreach ($quarters as $quarter) {
                $label = '';
                if ($quarter->getType()) {
                    $label .= $quarter->getType() . ' ';
                }
                if ($quarter->getName()) {
                    $label .= $quarter->getName();
                }

                $tpl[] = array('value' => $quarter->getId(), 'label' => $label);
            }

            $jsonData = json_encode($tpl);
            return $jsonData;
        } else {
            return FALSE;
        }
    }

    
    /**
     * This method loads information about streets (if any) in 
     * particular site. Please note that in order to retriver anything the
     * currently selected site must have either FULL or PARTIAL nomenclature.
     * @return boolean
     */
    public function getStreets() {
        $cityId = (int) $this->_request->getParam('cityid');
        $streetName = $this->_request->getParam('term');
        $streetName = Mage::helper('speedyshippingmodule/transliterate')->transliterate($streetName);
        //Initialize empty array
        $streets = array();
        try {
            $streets = $this->_speedyEPS->listStreets($streetName, $cityId);
        } catch (Exception $e) {
            
        }
        if ($streets) {
            $tpl = array();

            foreach ($streets as $street) {

                $tpl[] = array('label' => $street->getType() . ' ' . $street->getName(), 'value' => $street->getId());
            }

            $jsonData = json_encode($tpl);
            return $jsonData;
        } else {
            return FALSE;
        }
    }

    /**
     * This method loads information about living blocks (if any) in 
     * particular site. Please note that in order to retriver anything the
     * currently selected site must have either FULL or PARTIAL nomenclature.
     * @return boolean
     */
    public function getBlock() {
        $cityId = (int) $this->_request->getParam('cityid');
        $blockName = $this->_request->getParam('term');

        // $streetName = Mage::helper('speedyshippingmodule/transliterate')->transliterate($streetName);
        try {
            $blocks = $this->_speedyEPS->listBlocks($blockName, $cityId);
        } catch (Exception $e) {
            
        }
        if (isset($blocks)) {
            $tpl = array();

            foreach ($blocks as $block) {

                $tpl[] = array('label' => $block, 'value' => $block);
            }

            $jsonData = json_encode($tpl);
            return $jsonData;
        } else {
            return FALSE;
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
        } catch (ServerException $se) {
            Mage::log($se->getMessage(),null,'speedyLog.log');
        }
    }

}

?>
