<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/gm/index">GM工具</a> <span class="divider">/</span></li>
        <li class="active">发奖操作记录</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/gm/reward-record" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    uid：
                </td>
                <td>
                    <input class="input-small "  type="text" size="10" id="uid"  name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:''?>"/>
                </td>
                <td>
                    区服：
                </td>
                <td>
                    <select name="service">
                        <option value="-99">请选择</option>
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
    <form action="/content/gm/reward-record" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>操作时间</th>
                <th>账号</th>
                <th>区服</th>
                <th>昵称\平台</th>
                <th >邮件标题</th>
                <th >奖励内容</th>
                <th >是否领取</th>
                <th >处理人</th>
            </tr>
            </thead>
            <tbody>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo isset($data['id'])?$data['id']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['name'])?$data['name']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['catalog'])?$data['catalog']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['catalog'])?$data['catalog']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['catalog'])?$data['catalog']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['catalog'])?$data['catalog']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['catalog'])?$data['catalog']:''?></span></td>
                    <td ><span style="width: 80px; "><?php echo isset($data['catalog'])?$data['catalog']:''?></span></td>
                </tr>
            </tbody>
        </table>
    </form>

</div>