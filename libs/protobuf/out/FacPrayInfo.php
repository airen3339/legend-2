<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>FacPrayInfo</code>
 */
class FacPrayInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 praytype = 1;</code>
     */
    private $praytype = 0;
    /**
     * Generated from protobuf field <code>uint32 praycount = 2;</code>
     */
    private $praycount = 0;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>uint32 praytype = 1;</code>
     * @return int
     */
    public function getPraytype()
    {
        return $this->praytype;
    }

    /**
     * Generated from protobuf field <code>uint32 praytype = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setPraytype($var)
    {
        GPBUtil::checkUint32($var);
        $this->praytype = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 praycount = 2;</code>
     * @return int
     */
    public function getPraycount()
    {
        return $this->praycount;
    }

    /**
     * Generated from protobuf field <code>uint32 praycount = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setPraycount($var)
    {
        GPBUtil::checkUint32($var);
        $this->praycount = $var;

        return $this;
    }

}

