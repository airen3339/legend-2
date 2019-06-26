<?php $contentId = $this->params['contentId'];
$level           = Yii::$app->session->get('userLevel');//1-地区  6-教务 99-总裁 7-纯老师 5-老师兼学管
?>
<div class="nav-left">
    <p class="logo mb-0">
        <img src="/cn/images/course/nav/logo.png">
        <span>雷哥AI排课系统</span>
    </p>
    <!--地区、总裁、教务、老师兼学管账号都显示（今日 vip 班课 课表统计 我的课表）  学管显示（今日 vip 班课 我的课表） 纯老师只显示（我的课表）-->
    <ul class="p-0">
        <?php if ($level != 98) { ?>
        <!--教务 地区 总裁 老师兼学管-->
        <li class="<?php if ($contentId == 1) echo 'on'; ?> today">
            <img class="left-icon mmtf"
                 src="<?php if ($contentId == 1) echo '/cn/images/course/nav/daily_1.png'; else echo '/cn/images/course/nav/daily.png'; ?>">

            <a href="/cn/course/index">
                <span>今日课表</span>
            </a>
            <?php if ($contentId == 1) { ?>
                <img class="arrows mmtf" src="/cn/images/course/nav/sjx.png">
            <?php } ?>
        </li>
        <?php  if($level != 7){?>
            <li class=" vip">
                <img class="left-icon mmtf"
                     src="/cn/images/course/nav/student.png">
                <a>
                    <span>VIP课表</span>
                </a>
                <img src="/cn/images/course/nav/down.png">

            </li>
            <ul class="vip-second <?php if ($contentId == 6 || $contentId == 2) echo 'on'; ?>" >
                <?php if($level == 5 || $level ==6){?>
                    <li class="myclass <?php if ($contentId == 6) echo 'on'; ?>"> <!--给一个判断，是当前页是添加on类-->
                        <a href="/cn/my-course/my-course" class="">我的排课</a>

                        <?php if ($contentId == 6) { ?>
                            <img class="arrows mmtf" src="/cn/images/course/nav/sjx.png">
                        <?php } ?>

                    </li>
                <?php }?>
                <li class="myclass <?php if ($contentId == 2) echo 'on'; ?>"> <!--给一个判断，是当前页是添加on类-->
                    <a href="/cn/vip-classes/index" class="">VIP课表</a>
                    <?php if ($contentId == 2) { ?>
                        <img class="arrows mmtf" src="/cn/images/course/nav/sjx.png">
                    <?php } ?>
                </li>
            </ul>
        <li class="<?php if ($contentId == 3) echo 'on'; ?> class">
            <img class="left-icon mmtf"
                 src="<?php if ($contentId == 3) echo '/cn/images/course/nav/class_1.png'; else echo '/cn/images/course/nav/class.png'; ?>">
            <a href="/cn/classes/index">
                <span>班级课表</span>
            </a>
            <?php if ($contentId == 3) { ?>
                <img class="arrows mmtf" src="/cn/images/course/nav/sjx.png">
            <?php } ?>
        </li>
        <?php } ?>
    <?php } ?>
    <?php if ($level != 5 && $level != 7) { ?>
        <!--教务6 地区1 总裁99-->
        <li class="<?php if ($contentId == 4) echo 'on'; ?> statistical">
            <img class="left-icon mmtf"
                 src="<?php if ($contentId == 4) echo '/cn/images/course/nav/tong_1.png'; else echo '/cn/images/course/nav/tong.png'; ?>">
            <a href="/cn/classes-count/index">
                <span>课表统计</span>
            </a>
            <?php if ($contentId == 4) { ?>
                <img class="arrows mmtf" src="/cn/images/course/nav/sjx.png">
            <?php } ?>
        </li>
    <?php } ?>
    <?php if ($level != 1 && $level != 98) { ?>
        <!--总裁 教务 纯老师 老师兼学管       -->
        <li class="<?php if ($contentId == 5) echo 'on'; ?> statistical">
            <img class="left-icon mmtf"
                 src="<?php if ($contentId == 5) echo '/cn/images/course/kb_left.png'; else echo '/cn/images/course/kb_left_a.png'; ?>">
            <a href="/cn/teacher-count/index">
                <span>我的课表</span>

            </a>
            <?php if ($contentId == 5) { ?>
                <img class="arrows mmtf" src="/cn/images/course/nav/sjx.png">
            <?php } ?>
        </li>
    <?php } ?>
    </ul>
</div>
