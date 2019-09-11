var course_result;
var Subject;

$('#choosSubject ul li a').click(function (e) {
    $(this).siblings('.options').css({'display': 'block'});
    $(this).siblings('.options').removeClass('remove');
    $(this).parents('li').addClass('have-after');
    $(this).css({'border-bottom': '1px solid #fff'});
    $(this).parents('li').siblings('li').children('.options').css({'display': 'none'});
    $(this).parents('li').siblings('li').children('a').css({'border-bottom': '1px solid #ddd'});
    $(this).parents('li').siblings('li').removeClass('have-after');
    courser_def = $(this).children('input').attr('placeholder');
    $(this).children('img').attr('src', '/cn/images/course/pur_down.png');
    Subject = $(this).children('input').attr('placeholder');
    console.log('Subject', Subject);
});


$('.options .option').click(function (e) {
    console.log('option');
    $(this).addClass('purple-font ');
    course_result = $(this).html();
    console.log('course_result', course_result);
    $(this).siblings('li').removeClass('purple-font');
    $(this).parents('.options').parents('li').siblings().children('.options').children('.option').removeClass('purple-font');
    $(this).parents('.options').parents('li').removeClass('have-after');

    if (course_result == '全部') {
        $(this).parents('.options').siblings('a').children('input').addClass('purple-font').attr("value", Subject);

    } else {
        $(this).parents('.options').siblings('a').children('input').addClass('purple-font').attr("value", course_result);

    }

    $(this).parents('.options').parents('li').siblings().children('a').children('input').removeClass('purple-font');
    $(this).parents('.options').siblings('a').css({'border-bottom': '1px solid #ddd'});
    $(this).parents('.options').siblings('a').children('img').attr('src', '/cn/images/course/filter-down.png');
    $(this).parents('.options').parents('li').siblings().each(function () {
        var courser_def = $(this).children('a').children('input').attr('placeholder');
        $(this).children('a').children('input').attr('value', courser_def);
    });
    $(this).parents('.options').hide();
});
$(document).click(function (e) {
    if (!$(e.target).closest(".subject,#options").length) {
        $(".options").hide();
        $('.subject').css({'border-bottom': '1px solid #ddd'});
        $('.subject').children('img').attr('src', '/cn/images/course/filter-down.png');
    }
    /*dropdown-toggle下拉框*/
    if (!$(e.target).closest(".dropdown-toggle,.dropdown-menu").length) {
        $(".dropdown-menu").hide();
    }
});
$('.filter ul li.filter-item').click(function () {
    $(this).addClass('purple-font font-weight');
    $(this).siblings('li').removeClass('purple-font font-weight');
});

$('#filter_subject .filter-item').click(function () {
    $(this).removeClass('font-weight');
});

/*信息时间开始*/
function msg_start(ele) {
    var start = $(ele).val();
    var type = $(ele).attr('data-type');
    sessionStorage.setItem('filter_begin', start);
    console.log('msg_start');
    if (type == 'msg-start') {//消息查看
        getMessage(3);
    }
    if (type == 'jw_filter_start') {//教务 我的排课 班课
        getMyCourse(2);
    }
    if (type == 'jwvip_filter_start') {//教务 我的排课  vip
        getMyCourse(1);
    }
    if (type == 'xgvip_filter_start') {//学管 我的排课  vip
        getMyCourse(1);
    }
    if (type == 'audit-start') {//教务 我的审核  vip
        getCheckCourse(4);
    }
    if (type == 'teacher-start') {//课表统计 老师课表
        var classType = $('#teacher_classType').val();
        getTeacherCourse(classType);
    }


    // $('.c-datepicker-picker').each(function () {
    //     var s = $(this).attr('data-ob');
    //     console.log('msg_start');
    //     if (s != 1) {
    //         $(this).find('.available').click(function () {
    //             setTimeout(function () {
    //                 var start = $(ele).children('input').val();
    //                 var type = $(ele).children('input').attr('data-type');
    //                 sessionStorage.setItem('filter_begin', start);
    //                 if (type == 'msg-start') {//消息查看
    //                     getMessage(3);
    //                 }
    //                 if (type == 'jw_filter_start') {//教务 我的排课 班课
    //                     getMyCourse(2);
    //                 }
    //                 if (type == 'jwvip_filter_start') {//教务 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'xgvip_filter_start') {//学管 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'audit-start') {//教务 我的审核  vip
    //                     getCheckCourse(4);
    //                 }
    //                 if (type == 'teacher-start') {//课表统计 老师课表
    //                     var classType = $('#teacher_classType').val();
    //                     getTeacherCourse(classType);
    //                 }
    //             }, 500);
    //         });
    //         $(this).children('.c-datepicker-picker__footer').children('button').click(function () {
    //             setTimeout(function () {
    //                 var start = $(ele).children('input').val();
    //                 sessionStorage.setItem('filter_begin', start);
    //                 var type = $(ele).children('input').attr('data-type');
    //                 if (type == 'msg-start') {//消息查看
    //                     getMessage(3);
    //                 }
    //                 if (type == 'jw_filter_start') {//教务 我的排课 班课
    //                     getMyCourse(2);
    //                 }
    //                 if (type == 'jwvip_filter_start') {//教务 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'xgvip_filter_start') {//学管 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'audit-start') {//教务 我的审核  vip
    //                     getCheckCourse(4);
    //                 }
    //                 if (type == 'teacher-start') {//课表统计 老师课表
    //                     var classType = $('#teacher_classType').val();
    //                     getTeacherCourse(classType);
    //                 }
    //             }, 500);
    //         });
    //         $(this).attr('data-ob', 1);
    //     }
    // });

}

/*信息时间结束*/
function msg_end(ele) {

    var end = $(ele).val();
    var type = $(ele).attr('data-type');
    sessionStorage.setItem('filter_end', end);
    if (type == 'msg-end') {//消息查看
        getMessage(3);
    }
    if (type == 'jw_filter_end') {//教务 我的排课 班课
        getMyCourse(2);
    }
    if (type == 'jwvip_filter_end') {//教务 我的排课  vip
        getMyCourse(1);
    }
    if (type == 'xgvip_filter_end') {//学管 我的排课  vip
        getMyCourse(1);
    }
    if (type == 'audit-end') {//教务 我的审核  vip
        getCheckCourse(4);
    }
    if (type == 'teacher-end') {//课表统计 老师课表
        var classType = $('#teacher_classType').val();
        getTeacherCourse(classType);
    }

    // $('.c-datepicker-picker').each(function () {
    //     var s = $(this).attr('data-ob');
    //     if (s != 1) {
    //         $(this).find('.available').click(function () {
    //             setTimeout(function () {
    //                 var end = $(ele).children('input').val();
    //                 var type = $(ele).children('input').attr('data-type');
    //                 sessionStorage.setItem('filter_end', end);
    //                 if (type == 'msg-end') {//消息查看
    //                     getMessage(3);
    //                 }
    //                 if (type == 'jw_filter_end') {//教务 我的排课 班课
    //                     getMyCourse(2);
    //                 }
    //                 if (type == 'jwvip_filter_end') {//教务 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'xgvip_filter_end') {//学管 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'audit-end') {//教务 我的审核  vip
    //                     getCheckCourse(4);
    //                 }
    //                 if (type == 'teacher-end') {//课表统计 老师课表
    //                     var classType = $('#teacher_classType').val();
    //                     getTeacherCourse(classType);
    //                 }
    //             }, 500);
    //         });
    //         $(this).find('.c-datepicker-picker .c-datepicker-picker__footer button ').click(function () {
    //             setTimeout(function () {
    //                 var end = $(ele).children('input').val();
    //                 sessionStorage.setItem('filter_begin', end);
    //                 var type = $(ele).children('input').attr('data-type');
    //                 if (type == 'msg-end') {//消息查看
    //                     getMessage(3);
    //                 }
    //                 if (type == 'jw_filter_end') {//教务 我的排课 班课
    //                     getMyCourse(2);
    //                 }
    //                 if (type == 'jwvip_filter_end') {//教务 我的排课 vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'xgvip_filter_end') {//学管 我的排课  vip
    //                     getMyCourse(1);
    //                 }
    //                 if (type == 'audit-end') {//教务 我的审核  vip
    //                     getCheckCourse(4);
    //                 }
    //                 if (type == 'teacher-end') {//课表统计 老师课表
    //                     var classType = $('#teacher_classType').val();
    //                     getTeacherCourse(classType);
    //                 }
    //             }, 500);
    //         });
    //         $(this).attr('data-ob', 1);
    //
    //     }
    // });
}