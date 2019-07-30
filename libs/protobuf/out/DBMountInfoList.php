<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dbbuff.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>DBMountInfoList</code>
 */
class DBMountInfoList extends \Google\Protobuf\Internal\Message
{
    /**
     *当前使用的灵兽的背包槽,0:无使用
     *
     * Generated from protobuf field <code>uint32 dwCurrentMountBagSlot = 1;</code>
     */
    private $dwCurrentMountBagSlot = 0;
    /**
     *可幻化列表
     *
     * Generated from protobuf field <code>repeated uint32 vecSkinId = 2;</code>
     */
    private $vecSkinId;
    /**
     *上一次祭祀灵兽的时间
     *
     * Generated from protobuf field <code>uint32 dwLastSacrificetTime = 3;</code>
     */
    private $dwLastSacrificetTime = 0;
    /**
     *是否已完成灵兽的前置任务(1:完成)
     *
     * Generated from protobuf field <code>uint32 dwIsCompletePerTask = 4;</code>
     */
    private $dwIsCompletePerTask = 0;

    public function __construct() {
        \GPBMetadata\Dbbuff::initOnce();
        parent::__construct();
    }

    /**
     *当前使用的灵兽的背包槽,0:无使用
     *
     * Generated from protobuf field <code>uint32 dwCurrentMountBagSlot = 1;</code>
     * @return int
     */
    public function getDwCurrentMountBagSlot()
    {
        return $this->dwCurrentMountBagSlot;
    }

    /**
     *当前使用的灵兽的背包槽,0:无使用
     *
     * Generated from protobuf field <code>uint32 dwCurrentMountBagSlot = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setDwCurrentMountBagSlot($var)
    {
        GPBUtil::checkUint32($var);
        $this->dwCurrentMountBagSlot = $var;

        return $this;
    }

    /**
     *可幻化列表
     *
     * Generated from protobuf field <code>repeated uint32 vecSkinId = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getVecSkinId()
    {
        return $this->vecSkinId;
    }

    /**
     *可幻化列表
     *
     * Generated from protobuf field <code>repeated uint32 vecSkinId = 2;</code>
     * @param int[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setVecSkinId($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::UINT32);
        $this->vecSkinId = $arr;

        return $this;
    }

    /**
     *上一次祭祀灵兽的时间
     *
     * Generated from protobuf field <code>uint32 dwLastSacrificetTime = 3;</code>
     * @return int
     */
    public function getDwLastSacrificetTime()
    {
        return $this->dwLastSacrificetTime;
    }

    /**
     *上一次祭祀灵兽的时间
     *
     * Generated from protobuf field <code>uint32 dwLastSacrificetTime = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setDwLastSacrificetTime($var)
    {
        GPBUtil::checkUint32($var);
        $this->dwLastSacrificetTime = $var;

        return $this;
    }

    /**
     *是否已完成灵兽的前置任务(1:完成)
     *
     * Generated from protobuf field <code>uint32 dwIsCompletePerTask = 4;</code>
     * @return int
     */
    public function getDwIsCompletePerTask()
    {
        return $this->dwIsCompletePerTask;
    }

    /**
     *是否已完成灵兽的前置任务(1:完成)
     *
     * Generated from protobuf field <code>uint32 dwIsCompletePerTask = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setDwIsCompletePerTask($var)
    {
        GPBUtil::checkUint32($var);
        $this->dwIsCompletePerTask = $var;

        return $this;
    }

}
