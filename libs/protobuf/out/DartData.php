<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>DartData</code>
 */
class DartData extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 count = 1;</code>
     */
    private $count = 0;
    /**
     * Generated from protobuf field <code>uint32 state = 2;</code>
     */
    private $state = 0;
    /**
     * Generated from protobuf field <code>uint32 date = 3;</code>
     */
    private $date = 0;
    /**
     * Generated from protobuf field <code>uint32 offline = 4;</code>
     */
    private $offline = 0;
    /**
     * Generated from protobuf field <code>uint32 rewardExp = 5;</code>
     */
    private $rewardExp = 0;
    /**
     * Generated from protobuf field <code>uint32 rewardType = 6;</code>
     */
    private $rewardType = 0;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>uint32 count = 1;</code>
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Generated from protobuf field <code>uint32 count = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setCount($var)
    {
        GPBUtil::checkUint32($var);
        $this->count = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 state = 2;</code>
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Generated from protobuf field <code>uint32 state = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setState($var)
    {
        GPBUtil::checkUint32($var);
        $this->state = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 date = 3;</code>
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Generated from protobuf field <code>uint32 date = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setDate($var)
    {
        GPBUtil::checkUint32($var);
        $this->date = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 offline = 4;</code>
     * @return int
     */
    public function getOffline()
    {
        return $this->offline;
    }

    /**
     * Generated from protobuf field <code>uint32 offline = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setOffline($var)
    {
        GPBUtil::checkUint32($var);
        $this->offline = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 rewardExp = 5;</code>
     * @return int
     */
    public function getRewardExp()
    {
        return $this->rewardExp;
    }

    /**
     * Generated from protobuf field <code>uint32 rewardExp = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setRewardExp($var)
    {
        GPBUtil::checkUint32($var);
        $this->rewardExp = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 rewardType = 6;</code>
     * @return int
     */
    public function getRewardType()
    {
        return $this->rewardType;
    }

    /**
     * Generated from protobuf field <code>uint32 rewardType = 6;</code>
     * @param int $var
     * @return $this
     */
    public function setRewardType($var)
    {
        GPBUtil::checkUint32($var);
        $this->rewardType = $var;

        return $this;
    }

}

