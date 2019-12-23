<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">实时在线人数</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/online-number" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    时间：
                </td>
                <td>
                    <input class="input-small Wdate" style="width: 145px;" autocomplete="off" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    区服：
                </td>
                <td>
                    <select name="server">
                        <option value="0">请选择</option>
                        <?php
                        foreach($servers as $k => $v){ ?>
                            <option value='<?php echo $v['id']?>' <?php if(isset($_GET['server']) && $_GET['server'] == $v['id']) echo 'selected';?>><?php echo $v['name']?></option>";
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/online-number" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>区服</th>
                <th>在线人数</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data as $kss => $v) {
                ?>
                <tr class="text-item tdBorder">
                    <td ><span ><?php echo $v['name']?></span></td>
                    <td ><span ><?php echo $v['count']?></span></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>