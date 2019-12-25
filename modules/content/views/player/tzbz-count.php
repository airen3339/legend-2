<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/player/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">天中宝藏数据</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/player/tzbz-count" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    角色名：
                </td>
                <td>
                    <input  style="height: 20px;" type="text"  id="name"  name="name" value="<?php echo isset($_GET['name'])?$_GET['name']:''?>"/>
                </td>
                <td>
                    开始日期：
                </td>
                <td>
                    <input class="input-small Wdate"  style="width: 145px;"  autocomplete="off" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    结束日期：
                </td>
                <td>
                    <input class="input-small Wdate"  style="width: 145px;"  autocomplete="off" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  size="10" type="text" id="endTime" name="endTime"  value="<?php echo isset($_GET['endTime'])?$_GET['endTime']:''?>"/>
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
    <form action="/content/player/tzbz-count" method="post">
        <table class="table table-hover">
            <thead>
            <tr>
                <?php foreach($data as $k => $v){?>
                    <th><?php echo $v['name'];?></th>
                <?php }?>
            </tr>
            </thead>
            <tbody>
            <tr  class="text-item tdBorder">
                <?php
                if(isset($hadRole) && $hadRole){
                    foreach($data as $s => $p) {
                        ?>
                        <td><span><?php echo $p['count'] ?></span></td>
                        <?php
                    }
                }
                ?>
            </tr>
            </tbody>
        </table>
    </form>
</div>
