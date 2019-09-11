var teacher_result;
$('#teacher-result').click(function (e) {
    $(this).parents('.choose-input').siblings('.chooseTeacher ').show();
    if ($('.chooseDateModal').css("display", "block")) {

        $('.chooseDateModal').css("display", "none");
    }
    if ($('.choosSubject').css("display", "block")) {

        $('.choosSubject').css("display", "none");
    }


    // $('.chooseTeacher').css({'display': 'block'});
});
$('#bk_teacher-result').click(function (e) {
    $(this).parents('.choose-input').siblings('.chooseTeacher ').show();
    if ($('.chooseDateModal').css("display", "block")) {

        $('.chooseDateModal').css("display", "none");
    }
    if ($('.choosSubject').css("display", "block")) {

        $('.choosSubject').css("display", "none");
    }


    // $('.chooseTeacher').css({'display': 'block'});
});
$('#teacher-result-edit').click(function (e) {
    $(this).parents('.choose-input').siblings('.chooseTeacher ').show();
    if ($('.chooseDateModal').css("display", "block")) {

        $('.chooseDateModal').css("display", "none");
    }
    if ($('.choosSubject').css("display", "block")) {

        $('.choosSubject').css("display", "none");
    }


    // $('.chooseTeacher').css({'display': 'block'});
});

$('.teacher-result').click(function (e) {
    $(this).parents('.choose-input').siblings('.chooseTeacher ').show().css({'left': '-200px', 'top': '45px'});
    $(this).parents('.choose-input').siblings('.chooseTeacher ').children('ul').children('li').css({'width': '20%'});
    if ($('.chooseDateModal').css("display", "block")) {

        $('.chooseDateModal').css("display", "none");
    }
    if ($('.choosSubject').css("display", "block")) {

        $('.choosSubject').css("display", "none");
    }

});


$('#chooseTeacher li').click(function () {
    /* $(this).children('span').addClass('purple-font');
     $(this).children('.surplus').addClass('purple-font');
     $(this).siblings('li').removeClass('purple-font');
     $(this).siblings('li').children('.surplus').removeClass('purple-font');
     $(this).siblings('li').children('span').removeClass('purple-font');
     teacher_result = $(this).children('.name').html();
     console.log('teacher_result',teacher_result);*!/*/
});

$('.chooseTeacher .submit').click(function () {
    $(this).parents('.chooseTeacher').hide();

});
$('.dropdown-choose .chooseTeacher .submit').click(function () {
    var teacher = document.getElementById('teacher-result1');
    teacher.value = teacher_result;
    $(this).parents('.chooseTeacher').hide();
})


/*$('#subject-result').click(function (e) {
    $(this).parents('.choose-input').siblings('.choosSubject').show();
    $(this).parents('.choose-input').siblings('.choosSubject').css({'left': '-300px'});

    // $(this).parents('choose-input').siblings('.choosSubject').css({'display': 'block'});
});*/
$('.choose-input').click(function () {
    $(this).siblings('.choosSubject').css({'left': '-300px', 'display': 'block'});
    // $('#choosSubject').css('display','block');
});
$(document).on('click', function (e) {
    /*vip排课里表格内部选择课程*/
    if (!$(e.target).closest(".choose-input,#table_choosSubject").length) {
        $("#table_choosSubject").hide();
    }
    /*vip排课里表格头部选择课程*/
    if (!$(e.target).closest(".subject-result4,#nav_choosSubject").length) {
        $("#nav_choosSubject").hide();
    }
    /*vip排课里表格头部选择时间*/
    if (!$(e.target).closest(".table_chooseDate,#table_chooseDateModal").length) {
        $("#table_chooseDateModal").hide();
    }

    /*vip排课里表格头部选择老师*/
    if (!$(e.target).closest(".teacher-result2,#nav_chooseTeacher").length) {
        $("#nav_chooseTeacher").hide();
    }
    /*vip排课里表格内部选择老师*/
    if (!$(e.target).closest("#teacherChose,#table_chooseTeacher").length) {
        $("#table_chooseTeacher").hide();
    }

    /*信息弹窗里选择班型*/
    if (!$(e.target).closest(".subject-result2,#msg-choosSubject").length) {
        $("#msg-choosSubject").hide();
    }

    /*信息弹窗里选择老师*/
    if (!$(e.target).closest(".msg-chooseTeacherBtn,#msg-chooseTeacher").length) {
        $("#msg-chooseTeacher").hide();
    }

    /*信息弹窗里选择时间*/
    if (!$(e.target).closest(".msg-chooseDate,#msg-chooseDateModal").length) {
        $("#msg-chooseDateModal").hide();
    }

    /*审核弹窗选择班型*/
    if (!$(e.target).closest(".audit-choosSubject,#audit-choosSubject").length) {
        $("#audit-choosSubject").hide();
    }
    /*审核弹窗选择老师*/
    if (!$(e.target).closest(".audit-chooseTeacher,#audit-chooseTeacher").length) {
        $("#audit-chooseTeacher").hide();
    }

    /*审核弹窗里选择时间*/
    if (!$(e.target).closest(".audit-chooseDate,#audit-chooseDateModal").length) {
        $("#audit-chooseDateModal").hide();
    }
})


var subject_result;
var course_result;
$('.choosSubject ul li a').click(function (e) {
    $(this).siblings('.options').show();
    $(this).parents('li').addClass('have-after');
    $(this).css({'border-bottom': '1px solid #fff'});
    $(this).parents('li').siblings('li').children('.options').css({'display': 'none'});
    $(this).parents('li').siblings('li').removeClass('have-after');
    $(this).parents('li').siblings('li').children('a').css({'border-bottom': '1px solid #aaa'});
    if ($('.chooseDateModal').css("display", "block")) {
        $('.chooseDateModal').css("display", "none");
    }
    if ($('.chooseTeacher').css("display", "block")) {
        $('.chooseTeacher').css("display", "none");
    }
});
$('.options .option').click(function (e) {
    $(this).addClass('purple-font');
    $(this).siblings('li').removeClass('purple-font');
    $(this).parents('.options').parents('li').siblings().children('.options').children('.option').removeClass('purple-font');
    $(this).parents('.options').parents('li').removeClass('have-after');
    // $(this).parents('.options').siblings('a').css({'border-bottom': '1px solid #ddd'});
    $(this).parents('.options').hide();

});
/*$('.choosSubject .submit').click(function () {
    var subject = document.getElementById('subject-result');
    subject.value = subject_result + '-' + course_result;
});*/

$('.choosSubject .submit').click(function () {
    $(this).parents('.button-group').parents('.choosSubject').hide();
})
$('.dropdown-choose .choosSubject .submit').click(function () {
    $(this).parent('.button-group').parent('.choosSubject').hide();

})


/*排课表格里选择课程*/
$('.have-modal .GMAT-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GMAT-result1');
    subject.value = course_result;
});
$('.have-modal .TOFEL-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('TOFEL-result1');
    subject.value = course_result;

});
$('.have-modal .YASI-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('YASI-result1');
    subject.value = course_result;

});
$('.have-modal .GRE-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GRE-result1');
    subject.value = course_result;
});

/*nav上选择班型*/
$('.scheduling-list nav .GMAT-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GMAT-result');
    subject.value = course_result;
});
$('.scheduling-list nav .TOFEL-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('TOFEL-result');
    subject.value = course_result;

});
$('.scheduling-list nav .YASI-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('YASI-result');
    subject.value = course_result;
});
$('.scheduling-list nav .GRE-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GRE-result');
    subject.value = course_result;
});


/*消息里nav选择班型*/
$('#message nav .GMAT-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GMAT-result2');
    subject.value = course_result;
});
$('#message nav .TOFEL-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('TOFEL-result2');
    subject.value = course_result;

});
$('#message nav .YASI-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('YASI-result2');
    subject.value = course_result;
});
$('#message nav .GRE-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GRE-result2');
    subject.value = course_result;
});

/*审核区选择班型*/
$('#audit nav .GMAT-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GMAT-result4');
    subject.value = course_result;
});
$('#audit nav .TOFEL-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('TOFEL-result4');
    subject.value = course_result;

});
$('#audit nav .YASI-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('YASI-result4');
    subject.value = course_result;
});
$('#audit nav .GRE-choose .options .option').click(function () {
    course_result = $(this).html();
    var subject = document.getElementById('GRE-result4');
    subject.value = course_result;
});

$('#time-result').click(function () {
    $('.choose-time').css({'display': 'block'});
});
$('.bk_time-result').click(function () {
    console.log('bk_time-result');
    $('.bk_choose-time').css({'display': 'block'});
});
$('#time-result-edit').click(function () {
    $('.choose-time-edit').css({'display': 'flex'});
});
$('.time-list li').click(function () {
    $(this).addClass('pitchOn');
    $(this).siblings('li').removeClass('pitchOn');
});


$('.start-time li').click(function () {
    var time = $(this).html();
    var start_input = document.getElementById('start-time');
    start_input.value = time;
});
$('.end-time li').click(function () {
    var time = $(this).html();
    var end_input = document.getElementById('end-time');
    end_input.value = time;
});
$('.bk_start-time li').click(function () {
    var time = $(this).html();
    var start_input = document.getElementById('bk_start-time');
    start_input.value = time;
});
$('.bk_end-time li').click(function () {
    var time = $(this).html();
    var end_input = document.getElementById('bk_end-time');
    end_input.value = time;
});
$('.choose-time .submit').click(function () {
    var start_input = document.getElementById('start-time');
    var end_input = document.getElementById('end-time');
    var start = start_input.value;
    var end = end_input.value;
    var start_end = start + '-' + end;
    var time_result = document.getElementById('time-result');
    time_result.value = start_end;
    $('.choose-time').css({'display': 'none'});
    $('.time-list li').removeClass('pitchOn');
});
$('.choose-time .cancle').click(function () {
    $('.time-list li').removeClass('pitchOn');

});

$('.bk_choose-time .submit').click(function () {
    var start_input = document.getElementById('bk_start-time');
    var end_input = document.getElementById('bk_end-time');
    var start = start_input.value;
    var end = end_input.value;
    var start_end = start + '-' + end;
    var time_result = document.getElementById('bk_time-result');
    time_result.value = start_end;
    $('.bk_choose-time').css({'display': 'none'});
})
$('.start-time-edit li').click(function () {
    var time = $(this).html();
    var start_input = document.getElementById('start-time-edit');
    start_input.value = time;
});
$('.end-time-edit li').click(function () {
    var time = $(this).html();
    var end_input = document.getElementById('end-time-edit');
    end_input.value = time;
});
$('.choose-time-edit .submit').click(function () {
    var start_input = document.getElementById('start-time-edit');
    var end_input = document.getElementById('end-time-edit');
    var start = start_input.value;
    var end = end_input.value;
    var start_end = start + '-' + end;
    var time_result = document.getElementById('time-result-edit');
    time_result.value = start_end;
    $('.choose-time-edit').css({'display': 'none'});
})
$('.cancle').click(function () {
  /*  clearVipAdd();
    clearClassEdit();
    clearClassAdd();*/
    $(this).parent('.button-group').parent('.modal').hide();
});


$('.dropdown-choose #subject-result').click(function () {
    $(this).addClass('pitchOn');
    $(this).siblings('img').attr('src', '../img/pur_down.png');
})
$('.choose-dates span').click(function (e) {
    $(this).siblings('.chooseDateModal').show();
    if ($('.choosSubject').css("display", "block")) {

        $('.choosSubject').css("display", "none");
    }
    if ($('.chooseTeacher').css("display", "block")) {

        $('.chooseTeacher').css("display", "none");
    }
});
$('.choose-dates .chooseDateModal .c-datepicker-data-input').click(function () {
    $('.c-datepicker-picker ').removeClass('have_hour');
})
$('.choose-dates .chooseDateModal .cancel').click(function () {
    $(this).parents('.chooseDateModal').hide();
})
$('.choose-dates .chooseDateModal .submit').click(function () {
    $(this).parents('.chooseDateModal').hide();
})

$('#subject-result1').click(function (e) {
    $(this).parents('.choose-input').siblings('.choosSubject').show();
    $(this).parents('.choose-input').siblings('.choosSubject').css({'left': '-370px'});
    if ($('.chooseDateModal').css("display", "block")) {

        $('.chooseDateModal').css("display", "none");
    }
    if ($('.chooseTeacher').css("display", "block")) {

        $('.chooseTeacher').css("display", "none");
    }

})
$('#subject-result4').click(function (e) {
    $(this).parents('.choose-input').siblings('.choosSubject').show();
    $(this).parents('.choose-input').siblings('.choosSubject').css({'left': '-370px'});
    if ($('.chooseDateModal').css("display", "block")) {
        $('.chooseDateModal').css("display", "none");
    }
    if ($('.chooseTeacher').css("display", "block")) {
        $('.chooseTeacher').css("display", "none");
    }

})
$('#subject-result2').click(function (e) {
    $(this).parents('.choose-input').siblings('.choosSubject').show();
    $(this).parents('.choose-input').siblings('.choosSubject').css({'left': '-370px'});
    if ($('.chooseDateModal').css("display", "block")) {
        $('.chooseDateModal').css("display", "none");
    }
    if ($('.chooseTeacher').css("display", "block")) {

        $('.chooseTeacher').css("display", "none");
    }

})
$('#subject-result3').click(function (e) {
    $(this).parents('.choose-input').siblings('.choosSubject').show();
    $(this).parents('.choose-input').siblings('.choosSubject').css({'left': '-370px'});

    if ($('.chooseDateModal').css("display", "block")) {

        $('.chooseDateModal').css("display", "none");
    }
    if ($('.chooseTeacher').css("display", "block")) {

        $('.chooseTeacher').css("display", "none");
    }

});


/*我的课表-*/

/*年份数据*/

// for (i=1;i<30;i++){
//     var year_num=2018+i;
//     $('.choose-year').append('<option>'+year_num+' 年</option>\n')
// }
var num;
/*我的课表选择月*/
$('.month-count thead tr th').click(function () {
    $(this).css({'background': '#5859b5', 'color': '#fff'});
    $(this).siblings().css({'background': '#fff', 'color': '#333'});
    num = $(".month-count thead tr th").index(this);
    $(this).parents('thead').siblings('tbody').children('tr').children('th').css({
        'background': '#fff',
        'color': '#333'
    })
    $(this).parents('thead').siblings('tbody').children('tr').children('th').eq(num).css({
        'background': '#5859b5',
        'color': '#fff'
    })
});
$('.month-count tbody tr th').click(function () {
    $(this).css({'background': '#5859b5', 'color': '#fff'});
    $(this).siblings().css({'background': '#fff', 'color': '#333'});
    num = $(".month-count tbody tr th").index(this);
    $(this).parents('tbody').siblings('thead').children('tr').children('th').css({
        'background': '#fff',
        'color': '#333'
    })
    $(this).parents('tbody').siblings('thead').children('tr').children('th').eq(num).css({
        'background': '#5859b5',
        'color': '#fff'
    })
})


$(function () {
    pageClick();
});

//分页点击
function pageClick() {
    $('.pageHtml li').click(function () {
        $(this).siblings().removeClass('on');
        $(this).addClass('on');
        var page = $(this).html();
        var obj = $(this).parents('.pageHtml').attr('id');
        sessionStorage.setItem('page', page);
        if (obj == 'todayCoursePage' || obj == 'vipCoursePage' || obj == 'classCourse' || obj == 'classCount') {
            searchCourse(obj);
        }
        if (obj == 'check_course') {
            getCheckCourse(4);
        }
        if (obj == 'msgCourse') {
            getMessage(3);
        }
        if (obj == 'myCourse_vip') {
            getMyCourse(1);
        }
        if (obj == 'myCourse_class') {
            getMyCourse(2);
        }
        if (obj == 'teacher_course') {
            var type = $('.courses a.active').attr('data-cid');
            getTeacherCourse(type);
        }
        if (obj == 'classHead') {
            classPageContent(page, 1);//1-班课排课 班型内容
        }
        if (obj == 'classFoot') {
            classPageContent(page, 2);//1-班课排课 排课内容
        }
    });
}

//excel表下载 type 1-今日课表  2-vip课表 3-班级课表  4-课表统计 5-我的排课 班课 6-我的排课 vip 7-班课 课表详情 8-vip 课表详情 9-老师课表 10-我的课表
function excelLoad(type) {
    var area = sessionStorage.getItem('area');
    var subject = sessionStorage.getItem('subject');
    var start = sessionStorage.getItem('startTime');
    var end = sessionStorage.getItem('endTime');
    var classType = sessionStorage.getItem('classType');
    var teacher = sessionStorage.getItem('teacher');
    var stuName = $('#stuName').val();
    if (type == 1) {//今日课表
        window.location.href = '/cn/api/course-excel?t=' + type + '&a=' + area + '&s=' + subject + '&c=' + classType + '&te=' + teacher;
    }
    if (type == 2) {//vip课表
        window.location.href = '/cn/api/course-excel?t=' + type + '&a=' + area + '&s=' + subject + '&te=' + teacher + '&b=' + start + '&e=' + end + '&st=' + stuName;
    }
    if (type == 3) {//班级课表
        var classArea = $('#classArea').val();
        var classTeacher = $('#classTeacher').val();
        var count_month = $('#count_month').val();
        window.location.href = '/cn/api/course-excel?t=' + type + '&a=' + classArea + '&te=' + classTeacher + '&yearMonth=' + count_month;
    }
    if (type == 4) {//课表统计
        var tn = $('#teacherName').val();
        window.location.href = '/cn/api/course-excel?t=' + type + '&a=' + area + '&b=' + start + '&e=' + end + '&tn=' + tn;
    }
    // if (type == 5 || type == 6) {//2 我的排课
    if (type == 6) {//2 我的排课  vip  改版2.0 去除班课的我的排课
        var ar1 = sessionStorage.getItem('myCourse-area');
        var subj1 = sessionStorage.getItem('myCourse-subject');
        var sta1 = sessionStorage.getItem('filter_begin');
        var en1 = sessionStorage.getItem('filter_end');
        var teach1 = sessionStorage.getItem('myCourse-teacher');
        var stat1 = sessionStorage.getItem('myCourse-status');
        var stuNa1 = $('#stuName').val();
        window.location.href = '/cn/api/course-excel?t=' + type + '&a=' + ar1 + '&s=' + subj1 + '&b=' + sta1 + '&e=' + en1 + '&te=' + teach1 + '&sta=' + stat1 + '&st=' + stuNa1;
    }
    if (type == 7) {//班课 课表详情
        var are = $('#bk_area').val();
        var classtype = $('#bk_classType').val();
        var start = $('#rang-start').val();
        var end = $('#rang-end').val();
        window.location.href = '/cn/api/course-excel?t=' + type + '&c=' + classtype + '&b=' + start + '&e=' + end + '&a=' + are;
    }
    if (type == 8) {//vip 课表详情
        var std = $('#student').attr('data-stuId');
        var classT = $('#classType').val();
        var areaId = $('#area').val();
        if (confirm('只能导出通过审核的排课信息！')) {
            window.location.href = '/cn/api/course-excel?t=' + type + '&std=' + std + '&c=' + classT + '&a=' + areaId;
        }
    }
    if (type == 9) {//老师课表
        var teacherId = $('#teacherId').val();
        var teacher_area = $('#teacher_area').val();
        var begin = $('#teacher_start').val();
        var tend = $('#teacher_end').val();
        var teacher_classType = $('#teacher_classType').val();
        window.location.href = '/cn/api/course-excel?t=' + type + '&te=' + teacherId + '&a=' + teacher_area + '&b=' + begin + '&e=' + tend + '&c=' + teacher_classType;
    }
    if (type == 10) {//我的课表
        var teacherId = $('#teacherId').val();
        var year = $('#year').val();
        var month = $('#month').val();
        var area = $('#teacherArea div a.active').attr('data-areaId');
        window.location.href = '/cn/api/course-excel?t=' + type + '&te=' + teacherId + '&year=' + year + '&month=' + month + '&area=' + area;

    }
}

//改版2.0 课表统计 搜索老师
function countSearch(area = 0) {
    var teacherName = $('#teacherName').val();
    var begin = $('#count_begin').val();
    var end = $('#count_end').val();
    window.location.href = '/cn/classes-count/index?area=' + area + '&begin=' + begin + '&end=' + end + '&teacher=' + teacherName;
}

//数据清除
function clearhad() {
    // $('#area').val('');
    $('#bk_area').val('');
    $('#classType').val('');
    $('#bk_classType').val('');
    $('#student').val('');
    $('#rang-start').val('');
    $('#rang-end').val('');
    $('#studentNum').val('');
    $('#bk_studentNum').val('');
    $('#bk_totalTime').val('');
    $('#bk_useTime').val('');
    $('#useTime').html('');
    $('#allTime').html('');
    $('#bk_useTime2').html('');
    $('.classCourse').remove();
    $('.stuClass').remove();
}