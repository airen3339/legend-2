<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>MazeNodeState</code>
 */
class MazeNodeState extends \Google\Protobuf\Internal\Message
{
    /**
     *房间索引
     *
     * Generated from protobuf field <code>int32 index = 1;</code>
     */
    private $index = 0;
    /**
     *事件状态 0未激活 1激活 2完成
     *
     * Generated from protobuf field <code>int32 eventState = 2;</code>
     */
    private $eventState = 0;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     *房间索引
     *
     * Generated from protobuf field <code>int32 index = 1;</code>
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     *房间索引
     *
     * Generated from protobuf field <code>int32 index = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setIndex($var)
    {
        GPBUtil::checkInt32($var);
        $this->index = $var;

        return $this;
    }

    /**
     *事件状态 0未激活 1激活 2完成
     *
     * Generated from protobuf field <code>int32 eventState = 2;</code>
     * @return int
     */
    public function getEventState()
    {
        return $this->eventState;
    }

    /**
     *事件状态 0未激活 1激活 2完成
     *
     * Generated from protobuf field <code>int32 eventState = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setEventState($var)
    {
        GPBUtil::checkInt32($var);
        $this->eventState = $var;

        return $this;
    }

}

