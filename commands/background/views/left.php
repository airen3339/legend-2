<?php
    $contentId = Yii::$app->session->get('contentId');
    $actionId = Yii::$app->session->get('actionId');
    ?>
<ul class=" nav-parent">
    <?php foreach($leftArr as $k => $v){?>
    <li class="nav-first" onclick="showNavChild(this)">
        <a href="#">
            <?php echo $v['name'];?>
            <span class="nav-img "  >
                <img src="/image/nav.png" class="<?php if($contentId != $v['id']) echo 'img-rotate'?>" />
            </span>
        </a>
        <ul  class="nav nav-child nav-stacked <?php if($contentId != $v['id']) echo 'nav-hid';?>">
            <?php foreach($v['child'] as $t => $e){?>
                <li <?php if($actionId == $e['id']) echo "style='background-color:aliceblue;'";?>>
                    <a href="/content/<?php echo $v['rule']?>/<?php echo $e['rule'];?>">
                        <?php echo $e['name']?>
                    </a>
                </li>
            <?php }?>
        </ul>
    </li>
    <?php }?>
</ul>
<script>
    function showNavChild(_this){
        if($(_this).children('ul.nav-child').hasClass('nav-hid')){//字内容隐藏状态
            $(_this).children("ul.nav-child").removeClass('nav-hid');
            $(_this).siblings().find('ul.nav-child').addClass('nav-hid');
            $(_this).siblings().find('a span.nav-img img').css('transform','rotate(180deg)');
            $(_this).children("a").find('span.nav-img img').css('transform','rotate(0deg)');
        }else{//显示状态
            $(_this).children("ul.nav-child").removeClass('nav-hid');
            $(_this).children("ul.nav-child").addClass('nav-hid');
            $(_this).find('.nav-img img').css('transform','rotate(180deg)');
        }
    }
</script>