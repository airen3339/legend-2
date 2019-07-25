<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <meta http-equiv="refresh" content="5">
</head>
<body>
<?php
ignore_user_abort();
set_time_limit(0);
//header("Content-Type: text/html;charset=utf-8"); 
$now = time();
//定义数据常量
define('DB_HOST', '118.190.133.164');
define('DB_ROOT', 'root');
define('DB_PWD', 'zghd');

define('GAME_HOST', '118.190.133.164');
define('PORT', '11112');

define('SRC', 'http://a.apiplus.net/T42AEBCEA4F86E2A4K/cqssc-1.json');

//链接数据库
$con = mysql_connect(DB_HOST, DB_ROOT, DB_PWD);
mysql_query("set names 'utf8'");
mysql_select_db("logindata_185");

$json = json_decode(file_get_contents(urldecode(SRC)));
$datas = $json->data[0];
/*
 * object(stdClass)#2 (4) {
  ["expect"]=>
  string(11) "20171024027"
  ["opencode"]=>
  string(9) "8,1,4,6,2"
  ["opentime"]=>
  string(19) "2017-10-24 10:30:50"
  ["opentimestamp"]=>
  int(1508812250)
}
 */
$expect  = $datas->expect;
$opencode = $datas->opencode;
$opentime = $datas->opentime;
$opentimestamp = $datas->opentimestamp;

$sql = "select id from ssc_datas where  expect = $expect";
$result = mysql_query($sql, $con);
$row = mysql_fetch_assoc($result);
if(!$row){
    $n_sql = "insert into ssc_datas (expect,opencode,opentime,opentimestamp,time) values ($expect,'$opencode','$opentime',$opentimestamp,$now)";
    $ret = mysql_query($n_sql,$con);
    if($ret){
        $info = ssc_datas(GAME_HOST,PORT);
    }
    else{
        die("链接数据库失败");
    }
}else{
    die("当前时间戳$now");
}

function ssc_datas($host, $prot) {
    $opstr = '9915';
    $d = array(
        'cmd' => 1008,
        'data' => array(
            'server_id' => 0,
            'op' => $opstr,
        )
    );
    return GMSendCmd($d, $host, $prot);
}

function GMSendCmd($cmddata,$host='',$port='') {
    $cmdjson=json_encode($cmddata);
    $return=array('error'=>'1');
    $fsp = fsockopen($host,$port,$errno,$errstr,150);
    if(!$fsp){
        return $return;
    }else{
        fputs($fsp, $cmdjson, strlen($cmdjson));
        $result =fread($fsp,8192);
        fclose($fsp);
        $result = json_decode($result, TRUE);
        
        $return=is_null($result)?$return:$result;
        return $return;
    }
}
?>

</body>
</html>