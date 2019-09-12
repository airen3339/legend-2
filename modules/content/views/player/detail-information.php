<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">玩家相关</a> <span class="divider">/</span></li>
        <li class="active">详细信息</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/detail-information" method="get" class="form-horizontal">
        <table class="table">
            <tr>

                <td>
                    uid：
                </td>
                <td>
                    <input style="height: 20px" type="text" size="10" id="uid"  name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:''?>"/>
                </td>
                <td>
                    区服：
                </td>
                <td>
                    <select name="service">
                        <option value="0">请选择</option>
                        <option value="1" <?php if(isset($_GET['createPower']) && $_GET['createPower'] == 1) echo 'selected';?>>有</option>
                        <option value="2" <?php if(isset($_GET['createPower']) && $_GET['createPower'] == 2) echo 'selected';?>>无</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">提交</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/player/detail-information" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>账号</th>
                <th>区服</th>
                <th>昵称</th>
                <th>战斗力</th>
                <th>等级</th>
                <th>经验</th>
                <th >元宝</th>
                <th >绑定元宝</th>
                <th >金币</th>
                <th >血量</th>
                <th >魔法能量</th>
                <th >充值金额</th>
            </tr>
            </thead>
            <tbody>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo isset($data['UserID'])?$data['UserID']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['WorldName'])?$data['WorldName']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Name'])?$data['Name']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Battle'])?$data['Battle']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Level'])?$data['Level']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Exp'])?$data['Exp']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Ingot'])?$data['Ingot']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Cash'])?$data['Cash']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['Money'])?$data['Money']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['CurHP'])?$data['CurHP']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['CurMP'])?$data['CurMP']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['rechargeMoney'])?$data['rechargeMoney']:''?></span></td>
                </tr>
            </tbody>
        </table>
    </form>

</div>