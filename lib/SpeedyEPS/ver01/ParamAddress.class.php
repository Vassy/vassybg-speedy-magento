<?php
/**
 * Instances of this class are used as parameters on web service method calls for picking calculation and registration
 *
 * When address is required (i.e. when clientId is null), at least one of the following rules must be met:
 * •not empty street (ID or Type&Name) and (streetNo or blockNo);
 * •not empty quarter (ID or Type&Name) and (streetNo or blockNo);
 * •not empty common object;
 * •not empty addressNote.
 */
class ParamAddress {

    /**
     * Site ID
     * MANDATORY: YES
     * @var integer Signed 64-bit
     */
    private $_siteId;

    /**
     * Street name. Max size is 50 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_streetName;

    /**
     * Street type. Max size is 15 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_streetType;

    /**
     * Street ID
     * MANDATORY: NO
     * @var integer Signed 64-bit
     */
    private $_streetId;

    /**
     * Quarter name. Max size is 50 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_quarterName;

    /**
     * Quarter type. Max size is 15 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_quarterType;

    /**
     * Quarter ID
     * MANDATORY: NO
     * @var long Signed 64-bit
     */
    private $_quarterId;

    /**
     * Street No. Max size is 10 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_streetNo;

    /**
     * Block No. Max size is 32 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_blockNo;

    /**
     * Entrance No. Max size is 10 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_entranceNo;

    /**
     * Floor No. Max size is 10 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_floorNo;

    /**
     * Appartment No. Max size is 10 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_apartmentNo;

    /**
     * Address note. Max size is 200 symbols.
     * MANDATORY: NO
     * @var string
     */
    private $_addressNote;

    /**
     * Common object ID
     * MANDATORY: NO
     * @var integer Signed 64-bit
     */
    private $_commonObjectId;

    /**
     * GIS coordinates - X
     * MANDATORY: NO
     * @var double Signed 64-bit
     */
    private $_coordX;

    /**
     * GIS coordinates - Y
     * MANDATORY: NO
     * @var double Signed 64-bit
     */
    private $_coordY;

    /**
     * Set site ID
     * @param integer $siteId Signed 64-bit
     */
    public function setSiteId($siteId) {
        $this->_siteId = $siteId;
    }

    /**
     * Get site ID
     * @return integer Signed 64-bit
     */
    public function getSiteId() {
        return $this->_siteId;
    }

    /**
     * Set street name. Max size is 50 symbols.
     * @param string $streetName
     */
    public function setStreetName($streetName) {
        $this->_streetName = $streetName;
    }

    /**
     * Get street name
     * @return string
     */
    public function getStreetName() {
        return $this->_streetName;
    }

    /**
     * Set street type. Max size is 15 symbols.
     * @param string $streetType
     */
    public function setStreetType($streetType) {
        $this->_streetType = $streetType;
    }

    /**
     * Get street type
     * @return string
     */
    public function getStreetType() {
        return $this->_streetType;
    }

    /**
     * Set street ID
     * @param integer $streetId Signed 64-bit
     */
    public function setStreetId($streetId) {
        $this->_streetId = $streetId;
    }

    /**
     * Get street ID
     * @return integer Signed 64-bit
     */
    public function getStreetId() {
        return $this->_streetId;
    }

    /**
     * Set quarter name. Max size is 50 symbols.
     * @param string $quarterName
     */
    public function setQuarterName($quarterName) {
        $this->_quarterName = $quarterName;
    }

    /**
     * Get quarter name
     * @return string
     */
    public function getQuarterName() {
        return $this->_quarterName;
    }

    /**
     * Set quarter type. Max size is 15 symbols.
     * @param string $quarterType
     */
    public function setQuarterType($quarterType) {
        $this->_quarterType = $quarterType;
    }

    /**
     * Get quarter type
     * @return string
     */
    public function getQuarterType() {
        return $this->_quarterType;
    }

    /**
     * Set quarter ID.
     * @param integer $quarterId Signed 64-bit
     */
    public function setQuarterId($quarterId) {
        $this->_quarterId = $quarterId;
    }

    /**
     * Get quarter ID
     * @return integer Signed 64-bit
     */
    public function getQuarterId() {
        return $this->_quarterId;
    }

    /**
     * Set street No. Max size is 10 symbols.
     * @param string $streetNo
     */
    public function setStreetNo($streetNo) {
        $this->_streetNo = $streetNo;
    }

    /**
     * Get street No
     * @return string
     */
    public function getStreetNo() {
        return $this->_streetNo;
    }

    /**
     * Set block No. Max size is 32 symbols.
     * @param string $blockNo
     */
    public function setBlockNo($blockNo) {
        $this->_blockNo = $blockNo;
    }

    /**
     * Get block No
     * @return string
     */
    public function getBlockNo() {
        return $this->_blockNo;
    }

    /**
     * Set entrance No. Max size is 10 symbols.
     * @param string $entranceNo
     */
    public function setEntranceNo($entranceNo) {
        $this->_entranceNo = $entranceNo;
    }

    /**
     * Get entrance No
     * @return string
     */
    public function getEntranceNo() {
        return $this->_entranceNo;
    }

    /**
     * Set floor No. Max size is 10 symbols.
     * @param string $floorNo
     */
    public function setFloorNo($floorNo) {
        $this->_floorNo = $floorNo;
    }

    /**
     * Get floor No
     * @return string
     */
    public function getFloorNo() {
        return $this->_floorNo;
    }

    /**
     * Set appartment No. Max size is 10 symbols.
     * @param string $apartmentNo
     */
    public function setApartmentNo($apartmentNo) {
        $this->_apartmentNo = $apartmentNo;
    }

    /**
     * Get appartment No
     * @return string
     */
    public function getApartmentNo() {
        return $this->_apartmentNo;
    }

    /**
     * Set address note. Max size is 200 symbols.
     * @param string $addressNote
     */
    public function setAddressNote($addressNote) {
        $this->_addressNote = $addressNote;
    }

    /**
     * Get address note
     * @return string
     */
    public function getAddressNote() {
        return $this->_addressNote;
    }

    /**
     * Set common object ID.
     * @param integer $commonObjectId Signed 64-bit
     */
    public function setCommonObjectId($commonObjectId) {
        $this->_commonObjectId = $commonObjectId;
    }

    /**
     * Get common object ID
     * @return integer Signed 64-bit
     */
    public function getCommonObjectId() {
        return $this->_commonObjectId;
    }

    /**
     * Set GIS coordinate - X.
     * @param double $coordX Signed 64-bit
     */
    public function setCoordX($coordX) {
        $this->_coordX = $coordX;
    }

    /**
     * Get GIS coordinate - X
     * @return double Signed 64-bit
     */
    public function getCoordX() {
        return $this->_coordX;
    }

    /**
     * Set GIS coordinate - Y.
     * @param double $coordY Signed 64-bit
     */
    public function setCoordY($coordY) {
        $this->_coordY = $coordY;
    }

    /**
     * Get GIS coordinate - Y
     * @return double Signed 64-bit
     */
    public function getCoordY() {
        return $this->_coordY;
    }

    /**
     * Return standard class from this class
     * @return stdClass
     */
    public function toStdClass() {
        $stdClass = new stdClass();
        $stdClass->siteId         = $this->_siteId;
        $stdClass->streetName     = $this->_streetName;
        $stdClass->streetType     = $this->_streetType;
        $stdClass->streetId       = $this->_streetId;
        $stdClass->quarterName    = $this->_quarterName;
        $stdClass->quarterType    = $this->_quarterType;
        $stdClass->quarterId      = $this->_quarterId;
        $stdClass->streetNo       = $this->_streetNo;
        $stdClass->blockNo        = $this->_blockNo;
        $stdClass->entranceNo     = $this->_entranceNo;
        $stdClass->floorNo        = $this->_floorNo;
        $stdClass->apartmentNo    = $this->_apartmentNo;
        $stdClass->addressNote    = $this->_addressNote;
        $stdClass->commonObjectId = $this->_commonObjectId;
        $stdClass->coordX         = $this->_coordX;
        $stdClass->coordY         = $this->_coordY;
        return $stdClass;
    }
}
?>