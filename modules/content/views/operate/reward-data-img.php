<script type="text/javascript" src="/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/js/echarts.common.min.js"></script>
<div class="span10" id="datacontent">
    <ul class="breadcrumb">
        <li><a href="/content/operate/index">运营数据</a> <span class="divider">/</span></li>
        <li class="active">奖池数据（天/图）</li>
    </ul>
    <ul class="nav">
    </ul>
    <form action="/content/operate/reward-data-img" method="get" class="form-horizontal">
        <table class="table">
            <tr>
                <td>
                    日期：
                </td>
                <td>
                    <input class="input-small Wdate" autocomplete="off" onclick="WdatePicker()" type="text" size="10" id="beginTime"  name="beginTime" value="<?php echo isset($_GET['beginTime'])?$_GET['beginTime']:''?>"/>
                </td>
                <td>
                    <button class="btn btn-primary" type="submit">查询</button>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
    <form action="/content/operate/reward-data-img" method="post">
        <table class="table table-hover">
            <thead>
            </thead>
            <tbody>
            <div id="container" style="width: 90%;height: 440px;"></div>
            <input type="hidden" value="<?php echo isset($series)?$series:'';?>" id="linedata"/>
            <input type="hidden" value="<?php echo isset($date)?$date:'';?>" id="lineDay"/>
            <input type="hidden" value="<?php echo isset($data)?$data:'';?>" id="lineVal"/>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
    var dom = document.getElementById("container");
    var myChart = echarts.init(dom);
    var app = {};
    var ydata = $('#lineVal').val();
    var day = $('#lineDay').val();
    var arr = ydata.split(',');
    var xdata = $('#linedata').val();
    var xArr = xdata.split(',');
    console.log(ydata,xdata);
    // console.log(arr,);
    option = null;
    option = {
        title: {
            text: day
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['奖池数据']
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
            name:'时间',
            type: 'category',
            boundaryGap: false,
            // data: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],
            data: xArr,
            axisLabel: {
                interval:0,
                rotate:55
            },
        },
        yAxis: {
            name:'库存',
            type: 'value',
            // axisLabel:{formatter:'{value} 人数'}
        },
        series: [
            {
                name:'奖池数据库存',
                type:'line',
                stack: '新增',
                data:arr
            }

        ],
        // dataZoom:{
        //     realtime:true, //拖动滚动条时是否动态的更新图表数据
        //     height:25,//滚动条高度
        //     start:40,//滚动条开始位置（共100等份）
        //     end:65//结束位置（共100等份）
        // }
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }
</script>