<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">货币消耗</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/money-use" method="get" class="form-horizontal">
        <table class="table">
            <tr>
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
                    货币消耗：
                </td>
                <td>
                    <select name="moneyUse">
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
    <form action="/content/player/money-use" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>操作方式</th>
                <th>元宝/铜钱消耗总和</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr  class="text-item">
                    <td ><span style="width: 80px; "><?php echo $v['id']?></span></td>
                    <td ><span style="width: 80px; "><?php echo $v['name']?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>
