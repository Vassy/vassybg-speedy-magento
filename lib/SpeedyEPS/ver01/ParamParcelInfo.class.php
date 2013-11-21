<?php
/**
 * Instances of this class are used as parameters in web service calls for picking calculation and registration
 */
class ParamParcelInfo {

    /**
     * Parcel's serial number (2, 3, ...)
     * MANDATORY: YES
     * @var integer Signed 32-bit
     */
    private $_seqNo;

    /**
     * Parcel ID
     * MANDATORY: YES
     * @var integer Signed 64-bit
     */
    private $_parcelId;

    /**
     * Packing ID
     * MANDATORY: NO
     * @var integer Signed 64-bit
     */
    private $_packId;

    /**
     * Set parcel's serial number (2, 3, ...)
     * @param integer $seqNo Signed 32-bit
     */
    public function setSeqNo($seqNo) {
        $this->_seqNo = $seqNo;
    }

    /**
     * Get parcel's serial number
     * @return integer Signed 32-bit
     */
    public function getSeqNo() {
        return $this->_seqNo;
    }

    /**
     * Set parcel ID
     * @param integer $parcelId Signed 64-bit
     */
    public function setParcelId($parcelId) {
        $this->_parcelId = $parcelId;
    }

    /**
     * Get parcel ID
     * @return integer Signed 64-bit
     */
    public function getParcelId() {
        return $this->_parcelId;
    }

    /**
     * Set packing ID
     * @param integer $packId Signed 64-bit
     */
    public function setPackId($packId) {
        $this->_packId = $packId;
    }

    /**
     * Get packing ID
     * @return integer Signed 64-bit
     */
    public function getPackId() {
        return $this->_packId;
    }

    /**
     * Return standard class from this class
     * @return stdClass
     */
    public function toStdClass() {
        $stdClass = new stdClass();
        $stdClass->seqNo    = $this->_seqNo;
        $stdClass->parcelId = $this->_parcelId;
        $stdClass->packId   = $this->_packId;
        return $stdClass;
    }
}
?>