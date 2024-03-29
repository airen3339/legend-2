<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>MazeData</code>
 */
class MazeData extends \Google\Protobuf\Internal\Message
{
    /**
     *起点
     *
     * Generated from protobuf field <code>int32 headIndex = 1;</code>
     */
    private $headIndex = 0;
    /**
     *终点
     *
     * Generated from protobuf field <code>int32 endIndex = 2;</code>
     */
    private $endIndex = 0;
    /**
     *正确路线
     *
     * Generated from protobuf field <code>repeated int32 rightPath = 3;</code>
     */
    private $rightPath;
    /**
     *所有迷仙阵房间具体信息
     *
     * Generated from protobuf field <code>repeated .MazeNodeProtocol mazeNodes = 4;</code>
     */
    private $mazeNodes;
    /**
     *重置时间
     *
     * Generated from protobuf field <code>int32 resetTime = 5;</code>
     */
    private $resetTime = 0;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     *起点
     *
     * Generated from protobuf field <code>int32 headIndex = 1;</code>
     * @return int
     */
    public function getHeadIndex()
    {
        return $this->headIndex;
    }

    /**
     *起点
     *
     * Generated from protobuf field <code>int32 headIndex = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setHeadIndex($var)
    {
        GPBUtil::checkInt32($var);
        $this->headIndex = $var;

        return $this;
    }

    /**
     *终点
     *
     * Generated from protobuf field <code>int32 endIndex = 2;</code>
     * @return int
     */
    public function getEndIndex()
    {
        return $this->endIndex;
    }

    /**
     *终点
     *
     * Generated from protobuf field <code>int32 endIndex = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setEndIndex($var)
    {
        GPBUtil::checkInt32($var);
        $this->endIndex = $var;

        return $this;
    }

    /**
     *正确路线
     *
     * Generated from protobuf field <code>repeated int32 rightPath = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getRightPath()
    {
        return $this->rightPath;
    }

    /**
     *正确路线
     *
     * Generated from protobuf field <code>repeated int32 rightPath = 3;</code>
     * @param int[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setRightPath($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT32);
        $this->rightPath = $arr;

        return $this;
    }

    /**
     *所有迷仙阵房间具体信息
     *
     * Generated from protobuf field <code>repeated .MazeNodeProtocol mazeNodes = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getMazeNodes()
    {
        return $this->mazeNodes;
    }

    /**
     *所有迷仙阵房间具体信息
     *
     * Generated from protobuf field <code>repeated .MazeNodeProtocol mazeNodes = 4;</code>
     * @param \MazeNodeProtocol[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setMazeNodes($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \MazeNodeProtocol::class);
        $this->mazeNodes = $arr;

        return $this;
    }

    /**
     *重置时间
     *
     * Generated from protobuf field <code>int32 resetTime = 5;</code>
     * @return int
     */
    public function getResetTime()
    {
        return $this->resetTime;
    }

    /**
     *重置时间
     *
     * Generated from protobuf field <code>int32 resetTime = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setResetTime($var)
    {
        GPBUtil::checkInt32($var);
        $this->resetTime = $var;

        return $this;
    }

}

