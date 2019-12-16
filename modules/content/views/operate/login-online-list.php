<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/js/echarts.common.min.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">等级分布</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/login-online-list" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    查询日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()" type="text" size="10" id="day"  name="day" value="<?php echo isset($_GET['day'])?$_GET['day']:''?>"/>
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
    <form action="/content/operate/login-online-list" method="post">
        <table class="table table-hover">
            <thead>
            </thead>
            <tbody>
            <div id="container" style="width: 90%;height: 440px;"></div>
            <input type="hidden" value="<?php echo isset($series)?$series:'';?>" id="linedata"/>
            <input type="hidden" value="<?php echo isset($day)?$day:'';?>" id="lineDay"/>
            <input type="hidden" value="<?php echo isset($server)?$server:'';?>" id="lineServer"/>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
    var app = {};
    var ydata = $('#linedata').val();
    var day = $('#lineDay').val();
    var server = $('#lineServer').val();
    var arr = ydata.split(',');
    option = null;
    option = {
        title: {
            text: day+'/'+server+'服'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['登录在线分布']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            name:'小时',
            type: 'category',
            // boundaryGap: false,
            data: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],
        },
        yAxis: {
            name:'人数',
            type: 'value',
            // axisLabel:{formatter:'{value} 人数'}
        },
        series: [
            {
                name:'登录在线分布',
                type:'line',
                stack: '登录',
                data:arr
            }

        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
</script>