<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>PbBranch</code>
 */
class PbBranch extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 branchID = 1;</code>
     */
    private $branchID = 0;
    /**
     * Generated from protobuf field <code>uint32 state = 2;</code>
     */
    private $state = 0;
    /**
     * Generated from protobuf field <code>string taskTargetState = 3;</code>
     */
    private $taskTargetState = '';

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>uint32 branchID = 1;</code>
     * @return int
     */
    public function getBranchID()
    {
        return $this->branchID;
    }

    /**
     * Generated from protobuf field <code>uint32 branchID = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setBranchID($var)
    {
        GPBUtil::checkUint32($var);
        $this->branchID = $var;

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
     * Generated from protobuf field <code>string taskTargetState = 3;</code>
     * @return string
     */
    public function getTaskTargetState()
    {
        return $this->taskTargetState;
    }

    /**
     * Generated from protobuf field <code>string taskTargetState = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setTaskTargetState($var)
    {
        GPBUtil::checkString($var, True);
        $this->taskTargetState = $var;

        return $this;
    }

}
