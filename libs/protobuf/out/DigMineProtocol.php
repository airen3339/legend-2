<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>DigMineProtocol</code>
 */
class DigMineProtocol extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 exchangeCount = 1;</code>
     */
    private $exchangeCount = 0;
    /**
     * Generated from protobuf field <code>uint32 exchangeTime = 2;</code>
     */
    private $exchangeTime = 0;
    /**
     * Generated from protobuf field <code>uint32 out = 3;</code>
     */
    private $out = 0;
    /**
     * Generated from protobuf field <code>bool new = 4;</code>
     */
    private $new = false;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>uint32 exchangeCount = 1;</code>
     * @return int
     */
    public function getExchangeCount()
    {
        return $this->exchangeCount;
    }

    /**
     * Generated from protobuf field <code>uint32 exchangeCount = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setExchangeCount($var)
    {
        GPBUtil::checkUint32($var);
        $this->exchangeCount = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 exchangeTime = 2;</code>
     * @return int
     */
    public function getExchangeTime()
    {
        return $this->exchangeTime;
    }

    /**
     * Generated from protobuf field <code>uint32 exchangeTime = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setExchangeTime($var)
    {
        GPBUtil::checkUint32($var);
        $this->exchangeTime = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 out = 3;</code>
     * @return int
     */
    public function getOut()
    {
        return $this->out;
    }

    /**
     * Generated from protobuf field <code>uint32 out = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setOut($var)
    {
        GPBUtil::checkUint32($var);
        $this->out = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bool new = 4;</code>
     * @return bool
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * Generated from protobuf field <code>bool new = 4;</code>
     * @param bool $var
     * @return $this
     */
    public function setNew($var)
    {
        GPBUtil::checkBool($var);
        $this->new = $var;

        return $this;
    }

}

