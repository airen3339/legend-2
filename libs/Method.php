<?php
namespace app\libs;
use app\modules\cn\models\CourseSet;
use app\modules\cn\models\SchoolTest;
use app\modules\cn\models\SetCourse;
use app\modules\cn\models\User;
use app\modules\cn\models\UserWords;
use app\modules\test\models\Words;
use yii;
use yii\data\Pagination;
class Method
{
    /**
     * 分页函数
     * @param array $config 分页配置
     * @return array 分页
     * @Obelisk
     */
    public static function getPagedRows($config=[])
    {
        $pages=new Pagination(['totalCount' => $config['count']]);
        if(isset($config['pageSize']))
        {
            $pages->setPageSize($config['pageSize'],true);
        }
        return $pages;
    }

    /**
     * 获取某个时间的周次
     * @param $date
     * @return array
     * @Obelisk
     */
    public static function getMonthWeeks($date){
        $ret=array();
        $stimestamp=strtotime($date);
        $mdays=date('t',$stimestamp);
        $msdate=date('Y-m-d',$stimestamp);
        $medate=date('Y-m-'.$mdays,$stimestamp);
        $etimestamp = strtotime($medate);
        //獲取第一周
        if(date('w',$stimestamp) == 0){
            $zcsy = 0;
        }else{
            $zcsy = 7-date('w',$stimestamp);//第一周去掉第一天還有幾天
        }
        $zcs1=$msdate;
        $zce1=date('Y-m-d',strtotime("+$zcsy day",$stimestamp));
        $ret[1]= ['start' => $zcs1,'end' => $zce1];
        //獲取中間周次
        $jzc=0;
        //獲得當前月份是6周次還是5周次
        $jzc0="";
        $jzc6="";
        for($i=$stimestamp; $i<=$etimestamp; $i+=86400){
            if(date('w', $i) == 0){
                $jzc0++;
            }
            if(date('w', $i) == 1){
                $jzc6++;
            }
        }
        if($jzc0==5 && $jzc6==5)
        {
            $jzc=5;
        }else{
            $jzc=4;
        }
        date_default_timezone_set('PRC');
        $t = strtotime('+1 monday '.$msdate);
        $n = 1;
        for($n=1; $n<$jzc; $n++) {
            $b = strtotime("+$n week -1 week", $t);
            $dsdate=date("Y-m-d", strtotime("0 day", $b));
            $dedate=date("Y-m-d", strtotime("6 day", $b));
            $jzcz=$n+1;
            $ret[$jzcz]=['start' => $dsdate,'end' => $dedate];
        }
        //獲取最後一周
        $zcsy=date('w',$etimestamp)-1;//最後一周是周幾日~六 0~6
        $zcs1=date('Y-m-d',strtotime("-$zcsy day",$etimestamp));
        $zce1=$medate;
        $jzcz=$jzc+1;
        $ret[$jzcz]=['start' => $zcs1,'end' => $zce1];
        return $ret;
    }
    /**
     * 生成32位字符串
     * @return string
     * @Obelisk
     */
    public static function guid()
    {
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $uuid = substr($charid, 0, 8) . substr($charid, 8, 4) . substr($charid, 12, 4) . substr($charid, 16, 4) . substr($charid, 20, 12);
        return $uuid;
    }



    /**
     * @param string $html html内容
     * @param string $tags 保留标签
     * @return string
     */
    public static function getextbyhtml($html = '', $tags = '')
    {
        if (!empty($html)) {
            $res = preg_replace('/&nbsp;/', ' ', trim(strip_tags(htmlspecialchars_decode($html), $tags)));
            $res = trim(preg_replace('/<(p|P)>\W+<\/(p|P)>/', '', $res));
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * 词典翻译
     * @Obelisk
     */
    public static function getTranslate($words){
        $url = "http://fanyi.youdao.com/openapi.do?keyfrom=5asdfasdf6&key=925644231&type=data&only=dict&doctype=json&version=1.1&q=".$words;
        $list = file_get_contents($url);
        $js_de = json_decode($list,true);
        if($js_de['errorCode'] != 0){
            $data = 0;
        }else{
            $js_de['basic']['us'] = $js_de['basic']['us-phonetic'];
            $js_de['basic']['uk'] = $js_de['basic']['uk-phonetic'];
            $data = $js_de['basic'];
        }
        return $data;
    }


    /**
     * 二维数组去重
     * by  yanni
     */
    public static function more_array_unique($arr=array()){
        foreach($arr[0] as $k => $v){
            $arr_inner_key[]= $k;   //先把二维数组中的内层数组的键值记录在在一维数组中
        }
        foreach ($arr as $k => $v){
            $v =join(",",$v);
            $temp[$k] =$v;      //保留原来的键值 $temp[]即为不保留原来键值
        }
        $temp =array_unique($temp);    //去重：去掉重复的字符串
        foreach ($temp as $k => $v){
            $a = explode(",",$v);   //拆分后的重组 如：Array( [0] => james [1] => 30 )
            $arr_after[$k]= array_combine($arr_inner_key,$a);  //将原来的键与值重新合并
        }
        return $arr_after;
    }

    /**
     * 生成字符串
     * @param $i
     * @return string
     * @Obelisk
     */
    public static function randStr($i){
        $str = "abcdefghijklmnopqrstuvwxyz";
        $finalStr = "";
        for($j=0;$j<$i;$j++)
        {
            $finalStr .= substr($str,rand(0,25),1);
        }
        return $finalStr;
    }

    /**
     * post请求
     * @param $url
     * @param string $post_data
     * @param int $timeout
     * @return mixed
     * @Obelisk
     */
    public static  function post($url, $post_data = '', $timeout = 5){//curl

        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url);

        curl_setopt ($ch, CURLOPT_POST, 1);

        if($post_data != ''){

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        }

        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_HEADER, false);

        $file_contents = curl_exec($ch);

        curl_close($ch);

        return $file_contents;

    }


    //获取重复字符串
    public static function getrepetition($str1, $str2) {
        //将字符串转成数组
        preg_match_all("/./u",$str1,$arr1);
        preg_match_all("/./u",$str2,$arr2);
        //计算字符串的长度
        $arr1 = $arr1[0];
        $arr2 = $arr2[0];
        $len1 = count($arr1);
        $len2 = count($arr2);
        //初始化相同字符串的长度
        $len = 0;
        $arr = [];
        $no = "able,ible,al,an,ant,ent,ar,ary,ice,atie,ical,ine,ing,ish,ive,ory,il,ile,eel,like,ly,some,y,ful,ous,ent,en,ous,fic,ble,teen,ty,th,an,ese,ish,er,ish,est,most,less,tion,ition,sition,tive,ward,ization,ist,ism,ion,age,cy,ence,ance,ency,ancy,er,ics,ian,ication,ing,logy,ment,ness,or,ry,ship,th,ty,ure,ed,ate,en,ize,in,kilo,mis,fore,un,dis,re,ese,ess,ive,ial,al,ed,ish,ify,en,mid,im,ir,fore,inter,micro,ance,ab,abs,se,ship,hood,ette,acq,isit,esce";
        $noArr = explode(",",$no);
        //初始化相同字符串的起始位置
        $pos = -1;
        Yii::$app->session->remove('j');
        for ($i = 0; $i < $len1; $i++) {
            $b = Yii::$app->session->get('j');
            if(!$b){
                $b = 0;
            }
            for ($j = $b; $j < $len2; $j++) {
                //找到首个相同的字符
                if ($arr1[$i] == $arr2[$j]) {
                    //判断后面的字符是否相同
                    for ($p = 1;$i+$p<=$len1; $p++){
                        if(!isset($arr1[$i+$p]) || !isset($arr2[$j+$p])){
                            $lstr = mb_substr($str1, $i, $p,'utf-8');
                            if(strlen($lstr)>3 && !in_array($lstr,$noArr)){
                                $arr[] = $lstr;
                            }
                            $j = $j+$p-1;
                            $i = $i+$p-1;
                            Yii::$app->session->set('j',$j);
                            break;
                        }
                        if($arr1[$i+$p] != $arr2[$j+$p]){
                            if ($p-1 > 0) {
                                $lstr = mb_substr($str1, $i, $p,'utf-8');
                                if(strlen($lstr)>3 && !in_array($lstr,$noArr)){
                                    $arr[] = $lstr;
                                }
                                $j = $j+$p-1;
                                $i = $i+$p-1;
                                Yii::$app->session->set('j',$j);
                                break;
                            }else{
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $arr;
    }
    /**
     * 数字转英文
     * 1=》first
     * cy
     *
     */
    public static function numberToEnglish($site){
        $english = '';
        $arr = ['First','Second','Third','Fourth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Eleventh','Twelfth','Thirteenth','Fourth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth'];
        foreach($arr as $k => $v){
            if($site == ($k+1)){
                $english = $v;
            }
        }
        return $english;
    }

    /**

     *  数据导入

     * @param string $file excel文件

     * @param string $sheet

     * @return string   返回解析数据

     * @throws PHPExcel_Exception

     * @throws PHPExcel_Reader_Exception

     */

    public static function importExcel($file='', $sheet=0){
        $sheet--;
        $file = iconv("utf-8", "gb2312", $file);   //转码

        if(empty($file) OR !file_exists($file)) {

            die('file not exists!');

        }

        $objRead = new \PHPExcel_Reader_Excel2007();   //建立reader对象

        if(!$objRead->canRead($file)){

            $objRead = new \PHPExcel_Reader_Excel5();

            if(!$objRead->canRead($file)){

                die('No Excel!');

            }

        }



        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');



        $obj = $objRead->load($file);  //建立excel对象

        $currSheet = $obj->getSheet($sheet);   //获取指定的sheet表

        $columnH = $currSheet->getHighestColumn();   //取得最大的列号

        $columnCnt = array_search($columnH, $cellName);

        $rowCnt = $currSheet->getHighestRow();   //获取总行数



        $data = array();

        for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容

            for($_column=0; $_column<=$columnCnt; $_column++){

                $cellId = $cellName[$_column].$_row;

                $cellValue = $currSheet->getCell($cellId)->getValue();

                //$cellValue = $currSheet->getCell($cellId)->getCalculatedValue();  #获取公式计算的值

                if($cellValue instanceof \PHPExcel_RichText){   //富文本转换字符串

                    $cellValue = $cellValue->__toString();

                }



                $data[$_row][$cellName[$_column]] = $cellValue;

            }

        }



        return $data;

    }






    /**
     * 获取公网IP和地址
     * cy
     */
    public static function getip(){
//        $ch = curl_init('http://tool.huixiang360.com/zhanzhang/ipaddress.php');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $a  = curl_exec($ch);
//        preg_match('/\[(.*)\]/', $a, $ip);
//
//        $cip =$ip[1];
        $code = 1;//获取成功
        $cip = Yii::$app->request->getUserIP();
        if($cip == ''){
            $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";//新浪借口获取访问者地区
            $ip=json_decode(file_get_contents($url),true);
            $data = $ip;
        }else{
            try{
                $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$cip;//淘宝借口需要填写ip
                $ip=json_decode(file_get_contents($url));
                if((string)$ip->code=='1'){
                    return false;
                }
                $data = (array)$ip->data;
            }catch(\Exception  $e){
                return ['code'=>2];
            }
        }
        $data['code'] = $code;
        return $data;
    }


    /**
     * 工作日判断
     * cy
     * http://api.goseek.cn/Tools/holiday?date=20190101
     * 返回数据：正常工作日对应结果为 0, 法定节假日对应结果为 1, 节假日调休补班对应的结果为 2
     * //正常工作日对应结果为 0, 法定节假日对应结果为 1, 节假日调休补班对应的结果为 2，休息日对应结果为 3  0和2 work  1和3 reset
     */
    public static function dayIsWork($day){
//        $time = strtotime($day);
//        $week = date('w',$time);
//        if($week ==0 || $week == 6){//星期天 星期六
//            return 2;
//        }else{
//            return 1;
//        }
        $data = file_get_contents("http://api.goseek.cn/Tools/holiday?date={$day}");
        $data = json_decode($data,true);
        if($data['code']==10000){
            return $data['data'];
        }else{//失败重新获取
            $return = self::dayIsWork($day);
            return $return;
        }
    }
    /**
     * 数组键回复数字排序
     * cy
     */
    public static function getDefaultArray($arr){
        $array = [];
        foreach($arr as $k => $t){
            $array[]=$t;
        }
        return $array;
    }
}