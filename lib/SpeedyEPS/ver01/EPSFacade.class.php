<?php

require_once 'ClientException.class.php';
require_once 'EPSInterface.class.php';

/**
 * Speedy EPS Service
 */
class EPSFacade {

    /**
     * EPS Interface implementation
     * @since 1.0
     * @var EPSInterface
     */
    private $_epsInterfaceImpl;

    /**
     * User name
     * @since 1.0
     * @var string
     */
    private $_username;

    /**
     * User password
     * @since 1.0
     * @var string
     */
    private $_password;

    /**
     * Result of login Speedy web service method clall
     * @since 1.0
     * @var ResultLogin
     */
    private $_resultLogin;

    /**
     * Constructs new instance of EPS Facade
     * @since 1.0
     * @param EPSInterface $epsInterfaceImpl EPS interface implementation
     * @param string $username User name
     * @param string $password User password
     */
    function __construct($epsInterfaceImpl, $username, $password) {
        $this->_epsInterfaceImpl = $epsInterfaceImpl;
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Check session state before calling a business web service method
     * @since 1.0
     * @throws ClientException Thrown in case EPS interface implementation is not set
     */
    private function checkStateBeforeCall() {
        if (!isset($this->_epsInterfaceImpl)) {
            throw new ClientException("EPS Interface implementation is not set");
        }
    }

    /**
     * Set EPS interface implementation
     * @since 1.0
     * @param EPSInterface $epsInterfaceImpl
     */
    public function setEPSInterfaceImpl($epsInterfaceImpl) {
        $this->_epsInterfaceImpl = $epsInterfaceImpl;
    }

    /**
     * Return EPS interface implementation
     * @since 1.0
     * @return EPSInterface
     */
    public function getEPSInterfaceImpl() {
        return $this->_epsInterfaceImpl;
    }

    /**
     * Set user name
     * @since 1.0
     * @param string $username
     */
    public function setUsername($username) {
        $this->_username = $username;
    }

    /**
     * Return user name
     * @since 1.0
     * @return string User name
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     * Set user password
     * @since 1.0
     * @param string $password
     */
    public function setPassword($password) {
        $this->_password = $password;
    }

    /**
     * Return user password
     * @since 1.0
     * @return string User password
     */
    public function getPassword() {
        return $this->_password;
    }

    /**
     * Return result login.
     * Optionally tries to open new session in case it is not active
     * @since 1.0
     * @param boolean $openNewIfNotActive Whether to try to open new connection. Default is true
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultLogin
     */
    public function getResultLogin($openNewIfNotActive=true) {
        if ($openNewIfNotActive && !$this->isSessionActive(true)) {
            $this->_resultLogin = $this->login();
        }
        return $this->_resultLogin;
    }

    /**
     * Login web service method
     * @since 1.0
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultLogin Result of login
     */
    public function login() {
        $this->checkStateBeforeCall();
        $this->_resultLogin = $this->_epsInterfaceImpl->login($this->_username, $this->_password);
        return $this->_resultLogin;
    }

    /**
     * Returns flag whether the session is active
     * @since 1.0
     * @param boolean $refreshSession In case the session is active, this parameter specifies if the session should be automatically refreshed
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return boolean Session active flag
     */
    public function isSessionActive($refreshSession) {
        $this->checkStateBeforeCall();
        if (isset($this->_resultLogin)) {
            return $this->_epsInterfaceImpl->isSessionActive($this->_resultLogin->getSessionId(), $refreshSession);
        } else {
            return false;
        }
    }

    /**
     * Returns the list of courier services valid on this date
     * @since 1.0
     * @param date $date
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultCourierService instances
     */
    public function listServices($date) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listServices($this->getResultLogin(true)->getSessionId(), $date);
    }

    /**
     * Returns a list of sites matching the search criteria.
     * The result is limited to 10 records
     * @since 1.0
     * @param string $type Type of site
     * @param string $name Site name or part of it
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultSite instances
     */
    public function listSites($type, $name) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listSites($this->getResultLogin(true)->getSessionId(), $type, $name);
    }

    /**
     * Returns a list of sites. The method aims to find the closest matches.
     * The result is limited to 10 records
     * @since 1.0
     * @param ParamFilterSite $paramFilterSite
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultSiteEx instances
     */
    public function listSitesEx($paramFilterSite) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listSitesEx($this->getResultLogin(true)->getSessionId(), $paramFilterSite);
    }

    /**
     * Returns the list of courier services valid on this date and sites.
     * @since 1.0
     * @param date $date
     * @param integer $senderSiteId Signed 64-bit integer sender's site ID;
     * @param integer $receiverSiteId Signed 64-bit integer receiver's site ID;
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultCourierServiceExt instances
     */
    public function listServicesForSites($date, $senderSiteId, $receiverSiteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listServicesForSites($this->getResultLogin(true)->getSessionId(), $date, $senderSiteId, $receiverSiteId);
    }

    /**
     * Returns the min/max weight allowed for the given shipment parameters
     * @since 1.0
     * @param integer $serviceTypeId Signed 64-bit ID of the courier service
     * @param integer $senderSiteId Signed 64-bit Sender's site ID
     * @param integer $receiverSiteId Signed 64-bit Receiver's site ID
     * @param date $date
     * @param boolean $documents Specifies if the shipment consists of documents
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultMinMaxReal
     */
    public function getWeightInterval($serviceTypeId, $senderSiteId, $receiverSiteId, $date, $documents) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getWeightInterval(
                $this->getResultLogin(true)->getSessionId(), $serviceTypeId, $senderSiteId, $receiverSiteId, $date, $documents
        );
    }

    /**
     * Returns CSV-formatted data (depending on the nomenType value).
     * Column numbers can change in the future so it's recommended to address the data using the column names in the header row.
     * The data for some nomenTypes requires a payed license (additional licensing contract) and permissions (access rights).
     * To obtain such license please contact our IT department or your Speedy key account manager.
     * Type 100 - returns a list of all sites.
     * Type 300 - returns a list of all streets (requires a license).
     * Type 400 - returns a list of all quarters (requires a license).
     * Type 500 - returns a list of all common objects (requires a license).
     * Type 700 - returns a list of all block names (requires a license).
     * @since 1.0
     * @param integer $nomenType Signed 32-bit The type of address nomenclature
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return string CSV formatted
     */
    public function getAddressNomenclature($nomenType) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getAddressNomenclature($this->getResultLogin(true)->getSessionId(), $nomenType);
    }

    /**
     * Returns a list of all sites.
     * Note: This method is relatively slow (because of the size of its response). You shouldn't call it more than several times a day.
     * The methods is designed to provide data which should be locally stored/cached by client apps.
     * The address-related nomenclature data is updated only several times a year.
     * @since 1.0
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultSite instances
     */
    public function listAllSites() {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listAllSites($this->getResultLogin(true)->getSessionId());
    }

    /**
     * Returns a site by ID
     * @since 1.0
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultSite
     */
    public function getSiteById($siteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getSiteById($this->getResultLogin(true)->getSessionId(), $siteId);
    }

    /**
     * Returns sites having either full or partial address nomenclature (streets, quarters etc.).
     * @since 1.0
     * @param AddrNomen $addrNomen Only values FULL and PARTIAL are allowed
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultSite
     */
    public function getSitesByAddrNomenType($addrNomen) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getSitesByAddrNomenType($this->getResultLogin(true)->getSessionId(), $addrNomen);
    }

    /**
     * Returns a list of the most common types of streets.
     * @since 1.0
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array string List of the most common types of streets
     */
    public function listStreetTypes() {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listStreetTypes($this->getResultLogin(true)->getSessionId());
    }

    /**
     * Returns a list of the most common types of quarters (districts).
     * @since 1.0
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array string List of the most common types of quarters (districts).
     */
    public function listQuarterTypes() {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listQuarterTypes($this->getResultLogin(true)->getSessionId());
    }

    /**
     * Returns a list of streets matching the search criteria
     * The list is limited to 10 records.
     * @since 1.0
     * @param string $name Street name (or part of it)
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array ResultStreet List of streets
     */
    public function listStreets($name, $siteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listStreets($this->getResultLogin(true)->getSessionId(), $name, $siteId);
    }

    /**
     * Returns a list of quarters matching the search criteria
     * The list is limited to 10 records.
     * @since 1.0
     * @param string $name Quarter name (or part of it)
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array ResultQuarter List of quarters
     */
    public function listQuarters($name, $siteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listQuarters($this->getResultLogin(true)->getSessionId(), $name, $siteId);
    }

    /**
     * Returns a list of common objects matching the search criteria.
     * The list is limited to 10 records.
     * @since 1.0
     * @param string $name Common object name (or part of it)
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array ResultCommonObject List of common objects
     */
    public function listCommonObjects($name, $siteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listCommonObjects($this->getResultLogin(true)->getSessionId(), $name, $siteId);
    }

    /**
     * Returns a list of blocks matching the search criteria.
     * The list is limited to 10 records.
     * @since 1.0
     * @param string $name Block name (or part of it)
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array string List of blocks
     */
    public function listBlocks($name, $siteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listBlocks($this->getResultLogin(true)->getSessionId(), $name, $siteId);
    }

    /**
     * Returns a list of Speedy offices matching the search criteria
     * The list is limited to 10 records.
     * @since 1.0
     * @param string $name Office name (or part of it);
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array ResultOffice List of offices
     */
    public function listOffices($name, $siteId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->listOffices($this->getResultLogin(true)->getSessionId(), $name, $siteId);
    }

    /**
     * Returns data for client by ID.
     * Allowed values for clientId are only the ones of members of the user's contract and the predefined partners
     * in the WebClients application.
     * @since 1.0
     * @param integer $clientId Signed 64-bit integer – Client/Partner ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultClientData
     */
    public function getClientById($clientId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getClientById($this->getResultLogin(true)->getSessionId(), $clientId);
    }

    /**
     * Returns the dates when the shipment can be ordered for pick-up.
     * The "time" component represents the deadline for creating an order
     * (or the deadline for delivering the shipment to a Speedy office when senderOfficeId is set).
     * (This method could be used for the "takingDate" property of ParamPicking or ParamCalculation.)
     * Note: Either senderSiteId or senderOfficeId should be set, or neither of them. Both parameters having "not null" values is not allowed.
     * @since 1.0
     * @param integer $serviceTypeId
     * @param integer $senderSiteId Signed 64-bit – Sender's site ID
     * @param integer $senderOfficeId Signed 64-bit – If the sender intends to deliver the shipment to a Speedy office, the office ID could be set as a filter
     * @param date $minDate - When the "time" component is set then this date is to be included in the result list only if the time is not after the working time of Speedy;
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of dates
     */
    public function getAllowedDaysForTaking($serviceTypeId, $senderSiteId, $senderOfficeId, $minDate) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getAllowedDaysForTaking(
                $this->getResultLogin(true)->getSessionId(), $serviceTypeId, $senderSiteId, $senderOfficeId, $minDate
        );
    }

    /**
     * Returns a list of addresses matching the search criteria.
     * @since 1.0
     * @param ParamAddressSearch $address Search criteria (filter)
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultAddressSearch
     */
    public function addressSearch($address) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->addressSearch($this->getResultLogin(true)->getSessionId(), $address);
    }

    /**
     * Calculate method
     * @since 1.0
     * @param ParamCalculation $calculation
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultCalculation
     */
    public function calculate($calculation) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->calculate($this->getResultLogin(true)->getSessionId(), $calculation);
    }

    /**
     * This method could be used for preliminary check-up of shipment's price for a range of courier services.
     * @since 1.0
     * @param ParamCalculation $calculation Data needed to perform the calculation;
     * @param array $serviceTypeIds List<signed 64-bit integer> – A list of courier service IDs for which price needs to be calculated
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultCalculationMS
     */
    public function calculateMultipleServices($calculation, $serviceTypeIds) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->calculateMultipleServices($this->getResultLogin(true)->getSessionId(), $calculation, $serviceTypeIds);
    }

    /**
     * This is an alternative method for shipment price calculation where the parameter is of type ParamPicking.
     * Clients are encouraged to use the method that best fits their needs.
     * @since 1.0
     * @param ParamPicking $picking
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultCalculation
     */
    public function calculatePicking($picking) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->calculatePicking($this->getResultLogin(true)->getSessionId(), $picking);
    }

    /**
     * The method used to create BOL.
     * @since 1.0
     * @param ParamPicking $picking
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultBOL
     */
    public function createBillOfLading($picking) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->createBillOfLading($this->getResultLogin(true)->getSessionId(), $picking);
    }

    /**
     * Used for creating PDF documents to be printed (BOLs, labels etc.)
     * Examples:
     * •Bill of Lading;
     * •Bill of Lading with "cash on delivery";
     * •Custom travel label (type 20);
     * •Custom travel label (type 20) with "cash on delivery".
     * @since 1.0
     * @param ParamPDF $params
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array bytes
     */
    public function createPDF($params) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->createPDF($this->getResultLogin(true)->getSessionId(), $params);
    }

    /**
     * Creates PDF document for BOL.
     * The method is deprecated, "createPDF" should be used instead.
     * @deprecated The method is deprecated, "createPDF" should be used instead.
     * @since 1.0
     * @param integer $billOfLading Signed 64-bit
     * @param boolean $includeAutoPrintJS Specifies if embedded JavaScript code for direct printing to be generated
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array of bytes
     */
    public function createBillOfLadingPDF($billOfLading, $includeAutoPrintJS) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->createBillOfLadingPDF($this->getResultLogin(true)->getSessionId(), $billOfLading, $includeAutoPrintJS);
    }

    /**
     * Creates PDF document of "type I". The method is deprecated, "createPDF" should be used instead.
     * @deprecated The method is deprecated, "createPDF" should be used instead.
     * @since 1.0
     * @param integer $parcelId Signed 64-bit Parcel ID
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array of bytes
     */
    public function createCustomTravelLabelPDFType1($parcelId) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->createCustomTravelLabelPDFType1($this->getResultLogin(true)->getSessionId(), $parcelId);
    }

    /**
     * Used to cancel BOL.
     * Only allowed when the shipment is neither ordered nor picked up by Speedy.
     * @since 1.0
     * @param integer $billOfLading Signed 64-bit
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     */
    public function invalidatePicking($billOfLading) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->invalidatePicking($this->getResultLogin(true)->getSessionId(), $billOfLading);
    }

    /**
     * This method is used to update BOL.
     * Only allowed if BOL was created with pendingShipmentDescription = true.
     * @since 1.0
     * @param ParamPicking $picking Data for the shipment (BOL)
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultBOL
     */
    public function updateBillOfLading($picking) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->updateBillOfLading($this->getResultLogin(true)->getSessionId(), $picking);
    }

    /**
     * This method is used to add parcel to an existing BOL
     * (only allowed if BOL was created with pendingParcelsDescription = true).
     * @since 1.0
     * @param ParamParcel $parcel Parcel data
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return integer Signed 64-bit parcel's ID
     */
    public function addParcel($parcel) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->addParcel($this->getResultLogin(true)->getSessionId(), $parcel);
    }

    /**
     * Makes BOL "fully created".
     * Only applies to BOLs created with pendingParcelsDescription = true.
     * @since 1.0
     * @param integer $billOfLading Signed 64-bit
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return ResultBOL
     */
    public function finalizeBillOfLadingCreation($billOfLading) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->finalizeBillOfLadingCreation($this->getResultLogin(true)->getSessionId(), $billOfLading);
    }

    /**
     * Creates an order for shipments pick-up (i.e. a visit by courier of Speedy).
     * The retuned list contains objects corresponding to each BOL (one object per BOL).
     * When the validation errors list of at least one of the objects is not empty, that means the order has not been created.
     * @since 1.0
     * @param ParamOrder $order Order details
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultOrderPickingInfo
     */
    public function createOrder($order) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->createOrder($this->getResultLogin(true)->getSessionId(), $order);
    }

    /**
     * Returns a list with all parcels of a shipment.
     * @since 1.0
     * @param integer $billOfLading Signed 64-bit
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultParcelInfo
     */
    public function getPickingParcels($billOfLading) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getPickingParcels($this->getResultLogin(true)->getSessionId(), $billOfLading);
    }

    /**
     * This method can be used to track the state/history of a shipment.
     * @deprecated Use trackPickingEx instead
     * @since 1.0
     * @param integer $billOfLading Signed 64-bit
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultTrackPicking
     */
    public function trackPicking($billOfLading) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->trackPicking($this->getResultLogin(true)->getSessionId(), $billOfLading);
    }

    /**
     * This method can be used to track the state/history of a shipment.
     * @since 1.2
     * @param integer $billOfLading Signed 64-bit
     * @param ParamLanguage $language BG or EN. If set to null the server defaults to BG
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultTrackPickingEx
     */
    public function trackPickingEx($billOfLading, $language) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->trackPickingEx($this->getResultLogin(true)->getSessionId(), $billOfLading, $language);
    }
    
    /**
     * This method can be used to track the state/history of a shipment.
     * @since 1.4
     * @param integer $parcelId Signed 64-bit
     * @param ParamLanguage $language BG or EN. If set to null the server defaults to BG
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultTrackPickingEx
     */
    public function trackParcel($parcelId, $language) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->trackParcel($this->getResultLogin(true)->getSessionId(), $parcelId, $language);
    }

    /**
     * Search BOLs by reference codes (ref1 and/or ref2).
     * @since 1.0
     * @param ParamSearchByRefNum $params
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of BOLs found (signed 64-bit integers)
     */
    public function searchPickingsByRefNumber($params) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->searchPickingsByRefNumber($this->getResultLogin(true)->getSessionId(), $params);
    }
    
    /**
     * Get microregion id for provided GPS coordinates
     * @since 1.5
     * @param signed 64-bit treal $coordX
     * @param signed 64-bit treal $coordY
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return Mocregion id (signed 64-bit integer)
     */
    public function getMicroregionId($coordX, $coordY) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->getMicroregionId($this->getResultLogin(true)->getSessionId(), $coordX, $coordY);
    }
    
   /**
     * Returns data for clients by specified client ID or other search criteria.
     * If client ID is specified the behaviour of this method is the same as getClientById.
     * Otherwise, the search returns results that satisfy search criteria
     * @since 1.6
     * @param ParamClientSearch $clientQuery
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultClientData
     */
    public function searchClients($clientQuery) {
        $this->checkStateBeforeCall();
        return $this->_epsInterfaceImpl->searchClients($this->getResultLogin(true)->getSessionId(), $clientQuery);
    }
    
    
	/**
     * Returns list with available special delivery requirements for logged user
     * @throws ClientException Thrown in case EPS interface implementation is not set
     * @throws ServerException Thrown in case communication with server has failed
     * @return array List of ResultSpecialDeliveryRequirement
     * @since 2.1.0
     */
    public function listSpecialDeliveryRequirements() {
    	$this->checkStateBeforeCall();
    	return $this->_epsInterfaceImpl->listSpecialDeliveryRequirements($this->getResultLogin(true)->getSessionId());
    }
    
    /**
     * Validates address and returns validation result
     *   - validationMode = 0 (default) - Extended validation w/o GIS info (address uniqueness is not verified);
     *   - validationMode = 1 (NOT IMPLEMENTED YET - reserved for future implementation) Extended validation with GIS info (address uniqueness is verified);
     *   - validationMode = 2 - basic validation (the same as address validation in createBillOfLading)
     * @param ParamAddress $address
     * @param integer $validationMode signed 32 bit
     * @throws ServerException Thrown in case communication with server has failed
     * @throws PickingValidationException Thrown in case address validation has failed
     * @return boolean Validation result flag
     * @since 2.2.0
     */
    public function validateAddress($address, $validationMode) {
    	$this->checkStateBeforeCall();
    	return $this->_epsInterfaceImpl->validateAddress($this->getResultLogin(true)->getSessionId(), $address, $validationMode);
    }
    
    /**
     * Returns all client objects ( including logged user's ) having the same contract as logged client's contract.
     * @throws ServerException Thrown in case communication with server has failed
     * @return List of ResultClientData
     * @since 2.2.0
    */
    public function listContractClients() {
    	$this->checkStateBeforeCall();
    	return $this->_epsInterfaceImpl->listContractClients($this->getResultLogin(true)->getSessionId());
    }
    
    /**
     * Returns a list of Speedy offices matching the search criteria
     * The list is limited to 10 records.
     * @since 2.2.0
     * @param string $name Office name (or part of it);
     * @param integer $siteId Signed 64-bit Site ID
     * @throws ServerException Thrown in case communication with server has failed
     * @return array ResultOfficeEx List of offices
     */
    public function listOfficesEx($name, $siteId) {
    	$this->checkStateBeforeCall();
    	return $this->_epsInterfaceImpl->listOfficesEx($this->getResultLogin(true)->getSessionId(), $name, $siteId);
    }
}
?>