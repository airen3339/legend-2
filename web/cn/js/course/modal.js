

/*vip排课点击时间过后*/
function jwpk_chooseDate_input() {
    //点击时间选择后的事件
    var date = $('#jwpk_chooseDate_input').val();
    var courseType = $('#courseType').val();//1-vip 2-班课
    if (date) {
        if (courseType == 1) {
            $('#class_time').css('display', '');
            $('#time_result').val('');
            $('#class_classTime').css('display', 'none');
            $('#class_teacher').css('display', 'none');
            $('#teacher-result').attr('data-setId', '');
            $('#teacher-result').val('');
            $('#class_classroom').css('display', 'none');
        } else {
            $('#class_time').css('display', '');
            $('#class_teacher').css('display', 'none');
            $('#class_classroom').css('display', 'none');
            $('#class_subject').css('display', 'none');
            $('#classroom-result').html('');
            $('#teacher-result').val('');
            $('#subject-result').val('');
            $('#time-result').val('');
            $('#class_hour').html('');
        }
    }

    // $('.c-datepicker-picker').each(function () {
    //     var s = $(this).attr('data-ob');
    //     if (s != 1) {
    //         console.log('s', s);
    //
    //         $(this).find('.c-datepicker-date-picker__next-btn').click(function () {
    //             setTimeout(function () {
    //                 console.log('next');
    //                 $(this).find('.available').click(function () {
    //                     console.log('22');
    //                     setTimeout(function () {
    //                         var date = $('#chooseDate_input').val();
    //                         if (date) {
    //                             if (courseType == 1) {
    //                                 $('#class_time').css('display', '');
    //                                 $('#time_result').val('');
    //                                 $('#class_classTime').css('display', 'none');
    //                                 $('#class_teacher').css('display', 'none');
    //                                 $('#teacher-result').attr('data-setId', '');
    //                                 $('#teacher-result').val('');
    //                                 $('#class_classroom').css('display', 'none');
    //                             } else {
    //                                 $('#class_time').css('display', '');
    //                                 $('#class_teacher').css('display', 'none');
    //                                 $('#class_classroom').css('display', 'none');
    //                                 $('#class_subject').css('display', 'none');
    //                                 $('#classroom-result').html('');
    //                                 $('#teacher-result').val('');
    //                                 $('#subject-result').val('');
    //                                 $('#time-result').val('');
    //                                 $('#class_hour').html('');
    //                             }
    //                         }
    //                     }, 500);
    //                 });
    //                 $(this).children('.c-datepicker-picker__footer').children('button').click(function () {
    //                     setTimeout(function () {
    //                         var date = $('#chooseDate_input').val();
    //                         if (date) {
    //                             if (date) {
    //                                 if (courseType == 1) {
    //                                     $('#class_time').css('display', '');
    //                                     $('#time_result').val('');
    //                                     $('#class_classTime').css('display', 'none');
    //                                     $('#class_teacher').css('display', 'none');
    //                                     $('#teacher-result').attr('data-setId', '');
    //                                     $('#teacher-result').val('');
    //                                     $('#class_classroom').css('display', 'none');
    //                                 } else {
    //                                     $('#class_time').css('display', '');
    //                                     $('#class_teacher').css('display', 'none');
    //                                     $('#class_classroom').css('display', 'none');
    //                                     $('#class_subject').css('display', 'none');
    //                                     $('#classroom-result').html('');
    //                                     $('#teacher-result').val('');
    //                                     $('#subject-result').val('');
    //                                     $('#time-result').val('');
    //                                     $('#class_hour').html('');
    //                                 }
    //                             }
    //                         }
    //                     }, 500)
    //                 })
    //             }, 500);
    //         });
    //
    //         $(this).find('.available').click(function () {
    //             setTimeout(function () {
    //                 var date = $('#chooseDate_input').val();
    //                 if (date) {
    //                     if (courseType == 1) {
    //                         $('#class_time').css('display', '');
    //                         $('#time_result').val('');
    //                         $('#class_classTime').css('display', 'none');
    //                         $('#class_teacher').css('display', 'none');
    //                         $('#teacher-result').attr('data-setId', '');
    //                         $('#teacher-result').val('');
    //                         $('#class_classroom').css('display', 'none');
    //                     } else {
    //                         $('#class_time').css('display', '');
    //                         $('#class_teacher').css('display', 'none');
    //                         $('#class_classroom').css('display', 'none');
    //                         $('#class_subject').css('display', 'none');
    //                         $('#classroom-result').html('');
    //                         $('#teacher-result').val('');
    //                         $('#subject-result').val('');
    //                         $('#time-result').val('');
    //                         $('#class_hour').html('');
    //                     }
    //                 }
    //             }, 500);
    //         });
    //         $(this).children('.c-datepicker-picker__footer').children('button').click(function () {
    //             setTimeout(function () {
    //                 var date = $('#chooseDate_input').val();
    //                 if (date) {
    //                     if (date) {
    //                         if (courseType == 1) {
    //                             $('#class_time').css('display', '');
    //                             $('#time_result').val('');
    //                             $('#class_classTime').css('display', 'none');
    //                             $('#class_teacher').css('display', 'none');
    //                             $('#teacher-result').attr('data-setId', '');
    //                             $('#teacher-result').val('');
    //                             $('#class_classroom').css('display', 'none');
    //                         } else {
    //                             $('#class_time').css('display', '');
    //                             $('#class_teacher').css('display', 'none');
    //                             $('#class_classroom').css('display', 'none');
    //                             $('#class_subject').css('display', 'none');
    //                             $('#classroom-result').html('');
    //                             $('#teacher-result').val('');
    //                             $('#subject-result').val('');
    //                             $('#time-result').val('');
    //                             $('#class_hour').html('');
    //                         }
    //                     }
    //                 }
    //             }, 500)
    //         });
    //         $(this).attr('data-ob', 1);
    //     }
    // })
}


//班课排课点击时间
function xgpk_chooseDate_input() {
    classCourse();

    // $('.c-datepicker-picker').show();
    // $('.c-datepicker-picker ').each(function () {
    //     // var display = $(this).css('display');
    //     // console.log('ds',display);
    //     console.log('11');
    //     var s = $(this).attr('data-ob');
    //     if (s != 1) {
    //         $(this).find('.available').click(function () {
    //             setTimeout(function () {
    //                 classCourse();
    //             }, 500)
    //         })
    //         $(this).find('.confirm').click(function () {
    //             console.log('点击确定');
    //             setTimeout(function () {
    //                 classCourse();
    //             }, 500)
    //         })
    //         $(this).attr('data-ob', 1);
    //     }
    // })
    // console.log('that2',that);
    // setTimeout(function () {
    //     that.find('.available').click(function () {
    //         setTimeout(function () {
    //             classCourse();
    //         }, 1000)
    //     })
    //     that.find('.confirm').click(function () {
    //         console.log('点击确定');
    //         setTimeout(function () {
    //             classCourse();
    //
    //         })
    //     })
    // },1000)
}

var url = window.location.href;
var index = url.indexOf('#');
var result = url.substr(index + 1, url.length);

if (result == 'class-modal') {
    setTimeout(function () {
        xgpk_chooseDate_input()
    }, 1000)
}
if (result == 'jw-vip-paike') {
    setTimeout(function () {
        jwpk_chooseDate_input();
    }, 1000)

}
/*教务班课显示排课弹窗*/
$('.topaike').click(function () {
    clearVipAdd();
    clearClassEdit();
    clearClassAdd();
    clearhad();
    $('#courseType').val(2);
    $('.class-modal').show();
    $('.class-modal').css({'display': 'flex'});
    xgpk_chooseDate_input();

});

/*教务班课显示班型课表弹窗*/
$('.class-excel th div').click(function () {
    var content = $(this).attr('data-content');
    console.log(content);
    if(content ==1){
        var setId = $(this).attr('data-setId');
        var text = $(this).html();
        var date = $(this).attr('data-date');
        var teacher = $(this).attr('data-teacher');
        text = $.trim(text);
        if (text) {
            $('#courseType').val(2);
            classCourseLook(setId, 0, date, teacher);
            $('.class-modal').show();
            $('.class-modal').css({'display': 'flex'});
        }
    }else{
        var levelUser = $('#levelUser').val();
        if(levelUser ==6){
            $('#courseType').val(2);
            $('.class-modal').show();
            $('.class-modal').css({'display': 'flex'});
        }
    }
});


$('.add-class-modal .cancel').click(function () {
    $(this).parents('.add-class-modal').hide();
    clearClassAdd();
    clearClassEdit();
    clearVipAdd();
    clearhad();
});

/*教务班课添加班课*/
$('.add-class button').click(function () {
    $(this).parent('.add-class').before(str_class);
});


/*教务vip添加班型*/
// $('.vip-class-kebiao .add-class-type button').click(function () {
//     $(this).parent('.add-class-type').before(str_jwvip_class_type);
// });


/*教务审核框查看课表添加班型*/
$('.shenhe-class-kebiao .add-class-type button').click(function () {
    $(this).parent('.add-class-type').before(str_jwvip_shenhe_type);
});

/*教务审核框查看课表*/
$('.see-shenhe-kebiao').click(function () {
    $('.shenhe-class-kebiao').show();
    $('.shenhe-class-kebiao').css({'display': 'flex'});
});

courseLook();

function courseLook() {
    /*教务vip课表查看弹窗*/
    $('.vip-see-kebiao').click(function () {
        var setId = $(this).attr('data-setId');
        var seeId = $(this).attr('data-seeId');
        var vipCourse = $(this).attr('data-vipCourse');
        if (!seeId || seeId != 1) {
            seeId = 0;
        }
        var mycourse = '';
        if (!vipCourse || vipCourse != 1) {
            mycourse = 1;
        } else {
            mycourse = 0;
        }
        vipCourseLook(setId, seeId, mycourse);
        $('#statusSee').val(1);
        $('#courseType').val(1);
        $('.jw-vip-paike').show();
        $('.jw-vip-paike').css({'display': 'flex'});
        jwpk_chooseDate_input();


    });
    /*教务班课课表查看弹窗*/
    $('.class-see-kebiao').click(function () {
        var setId = $(this).attr('data-setId');
        var seeId = $(this).attr('data-seeId');
        if (!seeId || seeId != 1) {
            seeId = 0;
        }
        classCourseLook(setId, seeId);
        $('#courseType').val(2);
        $('.class-modal').show();
        $('.class-modal').css({'display': 'flex'});
    });
    /*教务审核 查看课表*/
    $('.jw-check-course').click(function () {
        var setId = $(this).attr('data-setId');
        var seeId = $(this).attr('data-seeId');
        if (!seeId || seeId != 1) {
            seeId = 0;
        }
        vipCourseLook(setId, seeId);
        $('#statusSee').val(1);
        $('#checkStatus').val(1);
        $('#courseType').val(1);
        $('.jw-vip-paike').show();
        $('.jw-vip-paike').css({'display': 'flex'});
    });
    /*学管查看课表*/
    $('.xgsee-kebiao').click(function () {
        var setId = $(this).attr('data-setId');
        var seeId = $(this).attr('data-seeId');
        var vipCourse = $(this).attr('data-vipCourse');
        if (!seeId || seeId != 1) {
            seeId = 0;
        }
        var mycourse = '';
        if (!vipCourse || vipCourse != 1) {
            mycourse = 1;
        } else {
            mycourse = 0;
        }
        vipCourseLook(setId, seeId, mycourse);
        $('#statusSee').val(1);
        $('#courseType').val(1);
        $('.xg-paike').show();
        $('.xg-paike').css({'display': 'flex'});
    });
}

//下拉框选择改变事件
function btnChange(values, _this) {
//如果选中的是学生，显示第二个下拉框
    if (values == "待审核") {
        $(_this).css({'color': '#888'});
    } else if (values == "通过") {
        $(_this).css({'color': '#03AB36'});
    } else {
        $(_this).css({'color': '#DC6D6D'});

    }

}


$('.xgto-paike').click(function () {
    clearVipAdd();
    clearClassEdit();
    clearClassAdd();
    clearhad();
    $('#courseType').val(1);
    $('.xg-paike').show();
    $('.xg-paike').css({'display': 'flex'});
});
/*学管查看课表增加班型*/
$('.xg-class-kebiao .add-class-type button').click(function () {
    $(this).parent('.add-class-type').before(str_xgkebiao_type);

});
// /*学管查看课表导出*/
// $('.xg-class-kebiao .export').click(function () {
//     $('.export-alert').show();
// });

/*学管排课增加班型*/
$('.xg-paike .add-class-type button').click(function () {
    $(this).parent('.add-class-type').before(str_xgpaike_type);
});


$('.jwto-paike').click(function () {
    $('#courseType').val(1);
    $('.jw-vip-paike').show();
    $('.jw-vip-paike').css({'display': 'flex'});
});
$('.excelImport').click(function () {
    $('#excelInput').css('display', '');
});