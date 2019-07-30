<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>TradeProtocol</code>
 */
class TradeProtocol extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 timetick = 1;</code>
     */
    private $timetick = 0;
    /**
     * Generated from protobuf field <code>int32 ingotTrade = 2;</code>
     */
    private $ingotTrade = 0;
    /**
     * Generated from protobuf field <code>repeated .PBTradeLimit limits = 3;</code>
     */
    private $limits;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>uint32 timetick = 1;</code>
     * @return int
     */
    public function getTimetick()
    {
        return $this->timetick;
    }

    /**
     * Generated from protobuf field <code>uint32 timetick = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setTimetick($var)
    {
        GPBUtil::checkUint32($var);
        $this->timetick = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 ingotTrade = 2;</code>
     * @return int
     */
    public function getIngotTrade()
    {
        return $this->ingotTrade;
    }

    /**
     * Generated from protobuf field <code>int32 ingotTrade = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setIngotTrade($var)
    {
        GPBUtil::checkInt32($var);
        $this->ingotTrade = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .PBTradeLimit limits = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getLimits()
    {
        return $this->limits;
    }

    /**
     * Generated from protobuf field <code>repeated .PBTradeLimit limits = 3;</code>
     * @param \PBTradeLimit[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setLimits($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \PBTradeLimit::class);
        $this->limits = $arr;

        return $this;
    }

}
