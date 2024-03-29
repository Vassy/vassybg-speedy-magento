<?php

require_once 'Size.class.php';
require_once 'ParamClientData.class.php';
require_once 'FixedDiscountCardId.class.php';
require_once 'ParamParcelInfo.class.php';

/**
 * Instances of this class are passed as a parameter of Speedy web service calls for calclualation and registration
 * @since 1.0
 */
class ParamPicking {

    /**
     * BOL number
     * MANDATORY: Only for updateBillOfLading. Null otherwise
     * @var integer Signed 64-bit
     */
    private $_billOfLading;

    /**
     * The date for shipment pick-up (the "time" component is ignored). Default value is "today".
     * MANDATORY: NO
     * @var date
     */
    private $_takingDate;

    /**
     * Courier service type ID
     * MANDATORY: YES
     * @var integer Signed 64-bit
     */
    private $_serviceTypeId;

    /**
     * ID of an office "to be called"
     * MANDATORY: Only for "to be called" pickings
     * @var integer Signed 64-bit
     */
    private $_officeToBeCalledId;

    /**
     * Fixed time for delivery ("HHmm" format, i.e., the number "1315" means "13:15", "830" means "8:30" etc.)
     * MANDATORY: Depending on the courier service, this property could be required, allowed or banned
     * @var integer Signed 16-bit
     */
    private $_fixedTimeDelivery;

    /**
     * In some rare cases users might prefer the delivery to be deferred by a day or two.
     * This parameter allows users to specify by how many (working) days they would like to postpone the shipment delivery.
     * Max 2 days
     * MANDATORY: No
     * @var integer Signed 32-bit
     */
    private $_deferredDeliveryWorkDays;

    /**
     * Specifies if the shipment has a "request for return documents"
     * MANDATORY: YES
     * @var boolean
     */
    private $_backDocumentsRequest;

    /**
     * Specifies if the shipment has a "request for return receipt"
     * MANDATORY: YES
     * @var boolean
     */
    private $_backReceiptRequest;

    /**
     * Specifies if the sender intends to deliver the shipment to a Speedy office by him/herself instead of ordering a visit by courier
     * MANDATORY: YES
     * @var boolean
     */
    private $_willBringToOffice;
    
    /**
     * Specifies the specific Speedy office, where the sender intends to deliver the shipment by him/herself. 
     * If willBringToOfficeId is provided, willBringToOffice flag is considered "true", regardless the value provided. 
     * If willBringToOfficeId is not provied (0 or null) and willBringToOffice flag is "true", 
     * willBringToOfficeId is automatically set with default value configured for caller user profile. 
     * The default willBringToOfficeId value could be managed using profile configuration page in client's Speedy web site. 
     * MANDATORY: NO
     * @since 1.3
     * @var integer Signed 64-bit
     */
    private $_willBringToOfficeId;

    /**
     * Shipment insurance value (if the shipment is insured).
     * The value is limited depending on user's permissions and Speedy's current policy
     * MANDATORY: NO
     * @var double Signed 64-bit
     */
    private $_amountInsuranceBase;

    /**
     * Cash-on-Delivery (COD) amount.
     * The value is limited depending on user's permissions and Speedy's current policy
     * MANDATORY: NO
     * @var double Signed 64-bit
     */
    private $_amountCodBase;

    /**
     * Specifies if the COD value is to be paid to a third party. Allowed only if the shipment has payerType = 2 (third party).
     * MANDATORY: NO
     * @var boolean
     */
    private $_payCodToThirdParty;

    /**
     * Return money-transfer request amount
     * The value is limited depending on user's permissions and Speedy's current policy
     * MANDATORY: NO
     * @var double Signed 64-bit
     */
    private $_retMoneyTransferReqAmount;

    /**
     * Parcels count. Maximum value is 999.
     * MANDATORY: YES
     * @var integer Signed 32-bit
     */
    private $_parcelsCount;

    /**
     * Size of shipment
     * MANDATORY: NO
     * @var Size
     */
    private $_size;

    /**
     * Declared weight (the greater of "volume" and "real" weight values).
     * Maximum value is 100.00
     * MANDATORY: YES
     * @var double Signed 64-bit
     */
    private $_weightDeclared;

    /**
     * Contents. Max text size - 50 symbols
     * MANDATORY: YES
     * @var string
     */
    private $_contents;

    /**
     * Packing
     * MANDATORY: YES
     * @var string
     */
    private $_packing;

    /**
     * Packing ID (number)
     * MANDATORY: NO
     * @var integer Signed 64-bit
     */
    private $_packId;

    /**
     * Specifies whether the shipment consists of documents
     * MANDATORY: YES
     * @var boolean
     */
    private $_documents;

    /**
     * Specifies whether the shipment is fragile - necessary when the price of insurance is being calculated
     * MANDATORY: YES
     * @var boolean
     */
    private $_fragile;

    /**
     * Specifies whether the shipment is palletized
     * MANDATORY: YES
     * @var boolean
     */
    private $_palletized;

    /**
     * Data for the sender
     * MANDATORY: YES
     * @var ParamClientData
     */
    private $_sender;

    /**
     * Data for the receiver
     * MANDATORY: YES
     * @var ParamClientData
     */
    private $_receiver;

    /**
     * Payer type (0=sender, 1=receiver or 2=third party)
     * MANDATORY: YES
     * @var integer Signed 32-bit
     */
    private $_payerType;

    /**
     * Payer ID.
     * MANDATORY: Must be set <=> payer is "third party".
     * @var integer Signed 64-bit
     */
    private $_payerRefId;

    /**
     * Insurance payer type (0=sender, 1=reciever or 2=third party)
     * MANDATORY: Must be set <=> shipment is insured (i.e. amountInsuranceBase > 0).
     * @var integer Signed 32-bit
     */
    private $_payerTypeInsurance;

    /**
     * Insurance payer ID
     * MANDATORY: Must be set <=> shipment has insurance (i.e. amountInsuranceBase > 0) and it is payed by a "third party".
     * @var integer Signed 64-bit
     */
    private $_payerRefInsuranceId;

    /**
     * Client's note
     * MANDATORY: NO
     * @var string
     */
    private $_noteClient;

    /**
     * Card/Coupon/Voucher number for fixed discount
     * MANDATORY: NO
     * @var FixedDiscountCardId
     */
    private $_discCalc;

    /**
     * ID of the client who is to receive the return receipt and/or the return documents.
     * If payer is "third party" then this client has to be payer's contract member.
     * Otherwise the client has to be sender's contract member.
     * MANDATORY: NO
     * @var integer Signed 64-bit
     */
    private $_retToClientId;

    /**
     * An optional reference code.
     * Maximum 30 symbols
     * MANDATORY: NO
     * @var string
     */
    private $_ref1;

    /**
     * An optional reference code.
     * Maximum 30 symbols
     * MANDATORY: NO
     * @var string
     */
    private $_ref2;

    /**
     * An optional value used to identify user's client software.
     * Please verify the allowed values with Speedy's IT Department.
     * MANDATORY: NO
     * @var integer Signed 64-bit
     */
    private $_clientSystemId;

    /**
     * Data for parcels with explicit/fixed IDs (from the second one onward)
     * The list has maximum lenght 998
     * MANDATORY: NO
     * @var array List of ParamParcelInfo
     */
    private $_parcels;

    /**
     * When parcelsCount > 1 and no explicit data has been set in the parcels property during the creation,
     * then parcels will be created automatically by default.
     * This parameter allows users to control this behaviour.
     * MANDATORY: NO
     * @var boolean
     */
    private $_skipAutomaticParcelsCreation;

    /**
     * Specifies if the service/system should allow parcels to be added to the shipment at a later stage.
     * MANDATORY: NO
     * @var boolean
     */
    private $_pendingParcelsDescription;

    /**
     * Specifies if the service/system should allow BOL's modification at a later stage.
     * MANDATORY: NO
     * @var boolean
     */
    private $_pendingShipmentDescription;

    /**
     * Set BOL number
     * @param integer $billOfLading Signed 64-bit
     */
    public function setBillOfLading($billOfLading) {
        $this->_billOfLading = $billOfLading;
    }

    /**
     * Get BOL number
     * @return integer Signed 64-bit
     */
    public function getBillOfLading() {
        return $this->_billOfLading;
    }

    /**
     * Set date for shipment pick-up (the "time" component is ignored).
     * @param date $takingDate
     */
    public function setTakingDate($takingDate) {
        $this->_takingDate = $takingDate;
    }

    /**
     * Get BOL number
     * @return date
     */
    public function getTakingDate() {
        return $this->_takingDate;
    }

    /**
     * Set courier service type ID
     * @param integer $serviceTypeId Signed 64-bit
     */
    public function setServiceTypeId($serviceTypeId) {
        $this->_serviceTypeId = $serviceTypeId;
    }

    /**
     * Get courier service type ID
     * @return integer Signed 64-bit
     */
    public function getServiceTypeId() {
        return $this->_serviceTypeId;
    }

    /**
     * Set ID of an office "to be called"
     * @param integer $officeToBeCalledId Signed 64-bit
     */
    public function setOfficeToBeCalledId($officeToBeCalledId) {
        $this->_officeToBeCalledId = $officeToBeCalledId;
    }

    /**
     * Get ID of an office "to be called"
     * @return integer Signed 64-bit
     */
    public function getOfficeToBeCalledId() {
        return $this->_officeToBeCalledId;
    }

    /**
     * Set fixed time for delivery ("HHmm" format, i.e., the number "1315" means "13:15", "830" means "8:30" etc.)
     * @param integer $fixedTimeDelivery Signed 16-bit
     */
    public function setFixedTimeDelivery($fixedTimeDelivery) {
        $this->_fixedTimeDelivery = $fixedTimeDelivery;
    }

    /**
     * Get fixed time for delivery
     * @return integer Signed 16-bit
     */
    public function getFixedTimeDelivery() {
        return $this->_fixedTimeDelivery;
    }

    /**
     * Set working days to postpone the shipment delivery. Allowe values are 1 or 2
     * @param integer $deferredDeliveryWorkDays Signed 32-bit
     */
    public function setDeferredDeliveryWorkDays($deferredDeliveryWorkDays) {
        $this->_deferredDeliveryWorkDays = $deferredDeliveryWorkDays;
    }

    /**
     * Get working days to postpone the shipment delivery
     * @return integer Signed 32-bit
     */
    public function getDeferredDeliveryWorkDays() {
        return $this->_deferredDeliveryWorkDays;
    }

    /**
     * Set "request for return documents" flag for shipment
     * @param boolean $backDocumentsRequest
     */
    public function setBackDocumentsRequest($backDocumentsRequest) {
        $this->_backDocumentsRequest = $backDocumentsRequest;
    }

    /**
     * Check "request for return documents" flag for shipment
     * @return boolean
     */
    public function isBackDocumentsRequest() {
        return $this->_backDocumentsRequest;
    }

    /**
     * Set "request for return receipt" flag for shipment
     * @param boolean $backReceiptRequest
     */
    public function setBackReceiptRequest($backReceiptRequest) {
        $this->_backReceiptRequest = $backReceiptRequest;
    }

    /**
     * Check "request for return receipt" flag for shipment
     * @return boolean
     */
    public function isBackReceiptRequest() {
        return $this->_backReceiptRequest;
    }

    /**
     * Set "bring to office" flag for shipment
     * @param boolean $willBringToOffice
     */
    public function setWillBringToOffice($willBringToOffice) {
        $this->_willBringToOffice = $willBringToOffice;
    }

    /**
     * Check "bring to office" flag for shipment
     * @return boolean
     */
    public function getWillBringToOffice() {
        return $this->_willBringToOffice;
    }
    
    /**
     * Set "bring to office" id
     * @since 1.3
     * @param Signed 64-bit $willBringToOfficeId
     */
    public function setWillBringToOfficeId($willBringToOfficeId) {
        $this->_willBringToOfficeId = $willBringToOfficeId;
    }
    
    /**
     * Get "bring to office" id
     * @since 1.3
     * @return Signed 64-bit
     */
    public function getWillBringToOfficeId() {
        return $this->_willBringToOfficeId;
    }

    /**
     * Set shipment insurance value (if the shipment is insured).
     * @param double $amountInsuranceBase Signed 64-bit
     */
    public function setAmountInsuranceBase($amountInsuranceBase) {
        $this->_amountInsuranceBase = $amountInsuranceBase;
    }

    /**
     * Get shipment insurance value
     * @return double Signed 64-bit
     */
    public function getAmountInsuranceBase() {
        return $this->_amountInsuranceBase;
    }

    /**
     * Set cash-on-Delivery (COD) amount.
     * @param double $amountCodBase Signed 64-bit
     */
    public function setAmountCodBase($amountCodBase) {
        $this->_amountCodBase = $amountCodBase;
    }

    /**
     * Get cash-on-Delivery (COD) amount.
     * @return double Signed 64-bit
     */
    public function getAmountCodBase() {
        return $this->_amountCodBase;
    }

    /**
     * Set flag for COD value to be paid to a third party. Allowed only if the shipment has payerType = 2 (third party).
     * @param boolean $payCodToThirdParty
     */
    public function setPayCodToThirdParty($payCodToThirdParty) {
        $this->_payCodToThirdParty = $payCodToThirdParty;
    }

    /**
     * Check flag for COD value to be paid to a third party
     * @return boolean
     */
    public function isPayCodToThirdParty() {
        return $this->_payCodToThirdParty;
    }

    /**
     * Set return money-transfer request amount
     * @param double $retMoneyTransferReqAmount Signed 64-bit
     */
    public function setRetMoneyTransferReqAmount($retMoneyTransferReqAmount) {
        $this->_retMoneyTransferReqAmount = $retMoneyTransferReqAmount;
    }

    /**
     * Get return money-transfer request amount
     * @return double Signed 64-bit
     */
    public function getRetMoneyTransferReqAmount() {
        return $this->_retMoneyTransferReqAmount;
    }

    /**
     * Set parcels count. Maximum value is 999
     * @param integer $parcelsCount Signed 32-bit
     */
    public function setParcelsCount($parcelsCount) {
        $this->_parcelsCount = $parcelsCount;
    }

    /**
     * Get parcels count
     * @return integer Signed 32-bit
     */
    public function getParcelsCount() {
        return $this->_parcelsCount;
    }

    /**
     * Set size of shipment
     * @param Size $size
     */
    public function setSize($size) {
        $this->_size = $size;
    }

    /**
     * Get size of shipment
     * @return Size
     */
    public function getSize() {
        return $this->_size;
    }

    /**
     * Set declared weight (the greater of "volume" and "real" weight values). Maximum value is 100.00
     * @param double $weightDeclared Signed 64-bit
     */
    public function setWeightDeclared($weightDeclared) {
        $this->_weightDeclared = $weightDeclared;
    }

    /**
     * Get declared weight
     * @return double Signed 64-bit
     */
    public function getWeightDeclared() {
        return $this->_weightDeclared;
    }

    /**
     * Set contents. Max text size - 50 symbols
     * @param string $contents
     */
    public function setContents($contents) {
        $this->_contents = $contents;
    }

    /**
     * Get contents.
     * @return string
     */
    public function getContents() {
        return $this->_contents;
    }

    /**
     * Set contents. Max text size - 50 symbols
     * @param string $packing
     */
    public function setPacking($packing) {
        $this->_packing = $packing;
    }

    /**
     * Get contents.
     * @return string
     */
    public function getPacking() {
        return $this->_packing;
    }

    /**
     * Set packing ID (number)
     * @param integer $packId Signed 64-bit
     */
    public function setPackId($packId) {
        $this->_packId = $packId;
    }

    /**
     * Get packing ID (number).
     * @return integer Signed 64-bit
     */
    public function getPackId() {
        return $this->_packId;
    }

    /**
     * Set flag whether shipment consists of documents
     * @param boolean $documents
     */
    public function setDocuments($documents) {
        $this->_documents = $documents;
    }

    /**
     * Check whether shipment consists if documents
     * @return boolean
     */
    public function isDocuments() {
        return $this->_documents;
    }

    /**
     * Set flag whether shipment is fragile - necessary when the price of insurance is being calculated
     * @param boolean $fragile
     */
    public function setFragile($fragile) {
        $this->_fragile = $fragile;
    }

    /**
     * Check whether shipment is fragile
     * @return boolean
     */
    public function isFragile() {
        return $this->_fragile;
    }

    /**
     * Set flag whether shipment is palletized
     * @param boolean $palletized
     */
    public function setPalletized($palletized) {
        $this->_palletized = $palletized;
    }

    /**
     * Check whether shipment is palletized
     * @return boolean
     */
    public function isPalletized() {
        return $this->_palletized;
    }

    /**
     * Set data for the sender
     * @param ParamClientData $sender
     */
    public function setSender($sender) {
        $this->_sender = $sender;
    }

    /**
     * Get data for the sender
     * @return ParamClientData
     */
    public function getSender() {
        return $this->_sender;
    }

    /**
     * Set data for the receiver
     * @param ParamClientData $receiver
     */
    public function setReceiver($receiver) {
        $this->_receiver = $receiver;
    }

    /**
     * Get data for the receiver
     * @return ParamClientData
     */
    public function getReceiver() {
        return $this->_receiver;
    }

    /**
     * Set payer type (0=sender, 1=receiver or 2=third party)
     * @param integer $payerType Signed 32-bit
     */
    public function setPayerType($payerType) {
        $this->_payerType = $payerType;
    }

    /**
     * Get payer type (0=sender, 1=receiver or 2=third party)
     * @return integer Signed 32-bit
     */
    public function getPayerType() {
        return $this->_payerType;
    }

    /**
     * Set payer ID
     * @param integer $payerRefId Signed 64-bit
     */
    public function setPayerRefId($payerRefId) {
        $this->_payerRefId = $payerRefId;
    }

    /**
     * Get payer ID
     * @return integer Signed 64-bit
     */
    public function getPayerRefId() {
        return $this->_payerRefId;
    }

    /**
     * Set insurance payer type (0=sender, 1=reciever or 2=third party)
     * @param integer $payerTypeInsurance Signed 32-bit
     */
    public function setPayerTypeInsurance($payerTypeInsurance) {
        $this->_payerTypeInsurance = $payerTypeInsurance;
    }

    /**
     * Get insurance payer type
     * @return integer Signed 32-bit
     */
    public function getPayerTypeInsurance() {
        return $this->_payerTypeInsurance;
    }

    /**
     * Set insurance payer ID
     * @param integer $payerRefInsuranceId Signed 64-bit
     */
    public function setPayerRefInsuranceId($payerRefInsuranceId) {
        $this->_payerRefInsuranceId = $payerRefInsuranceId;
    }

    /**
     * Get insurance payer ID
     * @return integer Signed 64-bit
     */
    public function getPayerRefInsuranceId() {
        return $this->_payerRefInsuranceId;
    }

    /**
     * Set client's note
     * @param string $noteClient
     */
    public function setNoteClient($noteClient) {
        $this->_noteClient = $noteClient;
    }

    /**
     * Get client's note
     * @return string
     */
    public function getNoteClient() {
        return $this->_noteClient;
    }

    /**
     * Set card/coupon/voucher number for fixed discount
     * @param FixedDiscountCardId $discCalc
     */
    public function setDiscCalc($discCalc) {
        $this->_discCalc = $discCalc;
    }

    /**
     * Get card/coupon/voucher number for fixed discount
     * @return FixedDiscountCardId
     */
    public function getDiscCalc() {
        return $this->_discCalc;
    }

    /**
     * Set ID of the client who is to receive the return receipt and/or the return documents.
     * If payer is "third party" then this client has to be payer's contract member.
     * Otherwise the client has to be sender's contract member.
     * @param integer $retToClientId Signed 64-bit
     */
    public function setRetToClientId($retToClientId) {
        $this->_retToClientId = $retToClientId;
    }

    /**
     * Get ID of the client who is to receive the return receipt and/or the return documents.
     * @return integer Signed 64-bit
     */
    public function getRetToClientId() {
        return $this->_retToClientId;
    }

    /**
     * Set optional reference code. Maximum 30 symbols.
     * @param string $ref1
     */
    public function setRef1($ref1) {
        $this->_ref1 = $ref1;
    }

    /**
     * Get optional reference code. Maximum 30 symbols.
     * @return string
     */
    public function getRef1() {
        return $this->_ref1;
    }

    /**
     * Set optional reference code 2. Maximum 30 symbols.
     * @param string $ref2
     */
    public function setRef2($ref2) {
        $this->_ref2 = $ref2;
    }

    /**
     * Get optional reference code 2. Maximum 30 symbols.
     * @return string
     */
    public function getRef2() {
        return $this->_ref2;
    }

    /**
     * Set optional value used to identify user's client software.
     * @param integer $clientSystemId Signed 64-bit
     */
    public function setClientSystemId($clientSystemId) {
        $this->_clientSystemId = $clientSystemId;
    }

    /**
     * Get optional value used to identify user's client software.
     * @return integer Signed 64-bit
     */
    public function getClientSystemId() {
        return $this->_clientSystemId;
    }

    /**
     * Set data for parcels with explicit/fixed IDs (from the second one onward)
     * @param array $parcels List of ParamParcelInfo
     */
    public function setParcels($parcels) {
        $this->_parcels = $parcels;
    }

    /**
     * Get data for parcels with explicit/fixed IDs (from the second one onward)
     * @return array List of ParamParcelInfo
     */
    public function getParcels() {
        return $this->_parcels;
    }

    /**
     * Set flag to set or not explicit data on processing parcels with sequence number > 1
     * @param boolean $skipAutomaticParcelsCreation
     */
    public function setSkipAutomaticParcelsCreation($skipAutomaticParcelsCreation) {
        $this->_skipAutomaticParcelsCreation = $skipAutomaticParcelsCreation;
    }

    /**
     * Check flag to set or not explicit data on processing parcels with sequence number > 1
     * @return boolean
     */
    public function isSkipAutomaticParcelsCreation() {
        return $this->_skipAutomaticParcelsCreation;
    }

    /**
     * Set flag service/system to allow parcels to be added to the shipment at a later stage
     * @param boolean $pendingParcelsDescription
     */
    public function setPendingParcelsDescription($pendingParcelsDescription) {
        $this->_pendingParcelsDescription = $pendingParcelsDescription;
    }

    /**
     * Check whether service/system to allow parcels to be added to the shipment at a later stage
     * @return boolean
     */
    public function isPendingParcelsDescription() {
        return $this->_pendingParcelsDescription;
    }

    /**
     * Set flag the service/system to allow BOL's modification at a later stage.
     * @param boolean $pendingShipmentDescription
     */
    public function setPendingShipmentDescription($pendingShipmentDescription) {
        $this->_pendingShipmentDescription = $pendingShipmentDescription;
    }

    /**
     * Check whether the service/system should allow BOL's modification at a later stage.
     * @return boolean
     */
    public function isPendingShipmentDescription() {
        return $this->_pendingShipmentDescription;
    }

    /**
     * Return standard class from this class
     * @return stdClass
     */
    public function toStdClass() {
        $stdClass = new stdClass();
        $stdClass->billOfLading                 = $this->_billOfLading;
        $stdClass->takingDate                   = $this->_takingDate;
        $stdClass->serviceTypeId                = $this->_serviceTypeId;
        $stdClass->officeToBeCalledId           = $this->_officeToBeCalledId;
        $stdClass->fixedTimeDelivery            = $this->_fixedTimeDelivery;
        $stdClass->deferredDeliveryWorkDays     = $this->_deferredDeliveryWorkDays;
        $stdClass->backDocumentsRequest         = $this->_backDocumentsRequest;
        $stdClass->backReceiptRequest           = $this->_backReceiptRequest;
        $stdClass->willBringToOffice            = $this->_willBringToOffice;
        $stdClass->willBringToOfficeId          = $this->_willBringToOfficeId;
        $stdClass->amountInsuranceBase          = $this->_amountInsuranceBase;
        $stdClass->amountCodBase                = $this->_amountCodBase;
        $stdClass->payCodToThirdParty           = $this->_payCodToThirdParty;
        $stdClass->retMoneyTransferReqAmount    = $this->_retMoneyTransferReqAmount;
        $stdClass->parcelsCount                 = $this->_parcelsCount;
        if (isset($this->_size)) {
            $stdClass->size = $this->_size->toStdClass();
        }
        $stdClass->weightDeclared               = $this->_weightDeclared;
        $stdClass->contents                     = $this->_contents;
        $stdClass->packing                      = $this->_packing;
        $stdClass->packId                       = $this->_packId;
        $stdClass->documents                    = $this->_documents;
        $stdClass->fragile                      = $this->_fragile;
        $stdClass->palletized                   = $this->_palletized;
        if (isset($this->_sender)) {
            $stdClass->sender = $this->_sender->toStdClass();
        }
        if (isset($this->_receiver)) {
            $stdClass->receiver = $this->_receiver->toStdClass();
        }
        $stdClass->payerType                    = $this->_payerType;
        $stdClass->payerRefId                   = $this->_payerRefId;
        $stdClass->payerTypeInsurance           = $this->_payerTypeInsurance;
        $stdClass->payerRefInsuranceId          = $this->_payerRefInsuranceId;
        $stdClass->noteClient                   = $this->_noteClient;
        if (isset($this->_discCalc)) {
            $stdClass->discCalc = $this->_discCalc->toStdClass();
        }
        $stdClass->retToClientId                = $this->_retToClientId;
        $stdClass->ref1                         = $this->_ref1;
        $stdClass->ref2                         = $this->_ref2;
        $stdClass->clientSystemId               = $this->_clientSystemId;
        $arrStdClassParamParcelInfo = array();
        if (isset($this->_parcels)) {
            if (is_array($this->_parcels)) {
                for($i = 0; $i < count($this->_parcels); $i++) {
                    $arrStdClassParamParcelInfo[$i] = $this->_parcels[$i]->toStdClass();
                }
            } else {
                $arrStdClassParamParcelInfo[0] = $this->_parcels->toStdClass();
            }
        }
        $stdClass->parcels                      = $arrStdClassParamParcelInfo;
        $stdClass->skipAutomaticParcelsCreation = $this->_skipAutomaticParcelsCreation;
        $stdClass->pendingParcelsDescription    = $this->_pendingParcelsDescription;
        $stdClass->pendingShipmentDescription   = $this->_pendingShipmentDescription;
        return $stdClass;
    }
}
?>