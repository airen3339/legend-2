//修改密码
function updatePass() {
    var oldPass = $('#oldPassword').val();
    var password = $('#pass').val();
    var newPass = $('#newPassword').val();
    var confirmPass = $('#confirmPassword').val();
    if (oldPass != password) {
        alert('原密码错误');
        return false;
    }
    if (oldPass == newPass) {
        alert('新密码与原密码一样');
        return false;
    }
    if (newPass != confirmPass) {
        alert('确认密码与新密码不一致');
        return false;
    }
    $.ajax({
        url: '/cn/api/update-pass',
        dataType: 'json',
        type: 'post',
        data: {
            password: newPass,
        },
        success: function (e) {
            if (e.code == 1) {
                location.href = '/cn/index/index';
            } else {
                alert(e.message);
            }
        }
    });
}

//添加课程
function addClass() {
    $('#addClass').css('display', 'table-row');
}


$('.vip').click(function () {
    $(this).siblings('.vip-second').toggle();
});

//取消vip课程
function returnVipClass(type) {//type 1-新增路径 2-修改路径
    if (type == 1) {
        $('#addClass').css('display', 'none');
    } else {
        $('tr.userCourse').css('display', 'table-row');//显示编辑内容
        //合并班型内容th隐藏显示判断
        var thHidden = $('#thHidden').val();
        if (thHidden) {
            var th = '.hiddenType' + thHidden;
            $(th).css('display', 'none');
        }
        //合并版型的版型行数合并判断修改
        var parent = $('#rowId').val();
        if (parent) {
            var r = '#rows' + parent;
            var rows = $(r).attr('rowspan');
            rows = parseInt(rows) + 1;
            $(r).attr('rowspan', rows);
        }
        var areaTotal = $('#areaTotal').attr('rowspan');
        var areaRows = parseInt(areaTotal) + 1;
        $('#areaTotal').attr('rowspan', areaRows);
        $('#childAreaTotal').css('display', 'none');
        $('#addClass').remove();//移除编辑框
        $('#addClass1').removeAttr('id').attr('id', 'addClass');//修改默认新增框id
        $('#addButton').css('display', 'block');//显示添加课程按钮
    }
}

//地区改变  学生对应改变 1-vip  2-班课
function areaStudent(type = 1) {
    var area = 0;
    if (type == 1) {
        area = $('#area').val();
        var str = '#areaId' + area;
        $('.areaStudents').css('display', 'none');
        if (area > 0) {
            $(str).css('display', 'block');
        }
    } else {
        area = $('#bk_area').val();
    }
    $('#searchStudents').html(ss);
    var ss = '';
    $('#student').val(ss);
    $('#studentName').val(ss);
    $('#classType').val(ss);
    $('#bk_classType').val(ss);
    $('#allTime').val(ss);
    $('#bk_allTime').val(ss);
    $('#bk_useTime').html(ss);
    $('#useTime').html(ss);
    $('.stuClass').remove();
    $('.classCourse').remove();
    $('#rang-start').val('');
    $('#rang-end').val('');
    clearVipAdd();
    clearClassEdit();
}

//学生选择 点击事件
function choiceStudent(_this) {
    var name = $(_this).html();
    var stuId = $(_this).attr('data-stuId');
    $('#studentName').attr('data-stuId', stuId);
    $('#studentName').val(name);
    $(_this).addClass('purple-font').siblings().removeClass('purple-font');

}

//学生断定 确定事件
function sureStudent() {
    var student = $('#studentName').val();
    var stuId = $('#studentName').attr('data-stuId');
    $('#student').val(student);
    $('#student').attr('data-stuId', stuId);
    // studentClass();
    $('#classType').val('');
    $('#allTime').val('');
    $('#useTime').val();
    clearVipAdd();
    $('.studentClass').remove();
    $('.stuClass').remove();
}

//学生选择  输入事件
function studentSearch() {
    var level = $('#level').val();
    var search = $('#studentName').val();
    var area = 0;
    if (level == 6) {
        area = $('#area').val();
        if (area < 1) {
            alert('请选择地区');
        }
    } else {
        area = $('#area').val();
    }
    $.ajax({
        url: '/cn/api/student-search',
        type: 'post',
        data: {
            level: level,
            area: area,
            student: search,
        },
        dataType: 'json',
        success: function (e) {
            var str = '';
            for (var i = 0; i < e.length; i++) {
                str += '<li onclick="choiceStudent(this)" data-stuId="' + e[i].clientId + '">' + e[i].name + '</li>';
            }

            $('.areaStudents').css('display', 'none');
            $('#searchStudents').css('display', 'block').html(str);
        }
    });
    $('#studentName').attr('data-stuId', 0);
}

//学生改变
function studentClass() {
    var studentId = $('#student').attr('data-stuId');
    var area = $('#area').val();
    $.ajax({
        url: '/cn/api/student-course',
        type: 'post',
        dataType: 'json',
        data: {
            id: studentId,
            area: area,
        },
        success: function (e) {
            var str = '';
            if (e.code == 1) {
                var data = e.data;
                for (var i = 0; i < data.length; i++) {
                    str += '<tr class="userCourse">';
                    if (data[i].total > 1) {
                        str += '<th id="rows' + data[i].id + '" rowspan="' + data[i].total + '">' + data[i].classType + '</th>';
                    } else {
                        str += '<th>' + data[i].classType + '</th>';
                    }
                    str += '<th>' + data[i].subject + '</th>';
                    str += '<th>' + data[i].totalTime + '</th>';
                    str += '<th>' + data[i].useTime + '</th>';
                    str += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0">';
                    if (data[i].total > 1) {
                        str += '<span class="purple-font" onclick="editVipClass(this,' + data[i].id + ',2)">编辑</span>';
                    } else {
                        str += '<span class="purple-font" onclick="editVipClass(this,' + data[i].id + ',1)">编辑</span>';
                    }
                    str += '<span class="purple-font" onclick="reduceVipClass(' + data[i].id + ')">删除</span></p>';
                    str += '</th></tr>';
                    if (data[i].total > 1) {
                        var child = data[i].child;
                        for (var j = 0; j < child.length; j++) {
                            str += '<tr class="userCourse">';
                            str += '<th style="display: none" class="hiddenType' + data[i].id + '">' + child[j].classType + '</th>';
                            str += '<th>' + child[j].subject + '</th>';
                            str += '<th>' + child[j].totalTime + '</th>';
                            str += '<th>' + child[j].useTime + '</th>';
                            str += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0">';
                            str += '<span class="purple-font" onclick="editVipClass(this,' + child[j].id + ',3,' + data[i].id + ')">编辑</span>';
                            str += '<span class="purple-font" onclick="reduceVipClass(' + child[j].id + ')">删除</span></p>';
                            str += '</th></tr>';
                        }
                    }
                }
                str += '<tr class="userCourse"><th colspan="2" class="font-bold purple-font zongji"><img src="/cn/images/course/tongji.png" class="mmtf">总计 </th>';
                str += '<th class="font-bold purple-font" id="totalTime">' + e.totalTime + '</th>';
                str += '<th class="font-bold purple-font" id="useTime">' + e.useTime + '</th>';
                str += '<th></th></tr>';
                $('.allCount').html('全部（' + e.useTime + '）');//排课课时数据修改
                //获取学生班型对应的老师和学生
                var teac = '<li class="float-left purple-font" data-teaId=0 onclick="classDo(this)"><a class="border-0">全部</a></li>';
                var subject = '<li class="float-left purple-font" data-setId=0 onclick="classDo(this)"><a class="border-0">全部</a></li>';
                for (var k = 0; k < e.teacher.length; k++) {
                    teac += '<li class="float-left" data-teaId="' + e.teacher[k].teacherId + '" onclick="classDo(this)"><a class="name">' + e.teacher[k].name + '</a></li>';
                }
                for (var d = 0; d < e.classType.length; d++) {
                    subject += '<li class="float-left" data-setId="' + e.classType[d].setId + '" onclick="classDo(this)"><a class="border-0 ">' + e.classType[d].classType + '</a></li>';
                }
                $('#teacherChoice').html(teac);
                $('#subjectChoice').html(subject);
                //排课信息
                var cour = '';
                for (var q = 0; q < e.details.length; q++) {
                    cour += '<tr class="detailCourse">';
                    cour += '<th rowspan="' + e.details[q].total + '">';
                    if (e.details[q].status == 0) {
                        cour += '<span class="ml-0 lable purple-font">预</span>';
                    }
                    if (e.details[q].status == -1) {
                        cour += '<span class="ml-0 lable purple-font">拒绝</span>';
                    }
                    cour += '' + e.details[q].dateStr + '</th>';
                    cour += '<th>' + e.details[q].time + '</th>';
                    cour += '<th>' + e.details[q].classTime + '</th>';
                    cour += '<th>' + e.details[q].setCourse + '</th>';
                    cour += '<th>' + e.details[q].teacher + '</th>';
                    cour += '<th>' + e.details[q].classroom + '</th>';
                    cour += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + e.details[q].id + ')">删除</span></p></th>';
                    cour += '</tr>';
                    if (e.details[q].total > 1) {
                        var ch = e.details[q].child;
                        for (var c = 0; c < ch.length; c++) {
                            cour += '<tr class="detailCourse">';
                            cour += '<th>' + ch[c].time + '</th>';
                            cour += '<th>' + ch[c].classTime + '</th>';
                            cour += '<th>' + ch[c].setCourse + '</th>';
                            cour += '<th>' + ch[c].teacher + '</th>';
                            cour += '<th>' + ch[c].classroom + '</th>';
                            cour += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + ch[c].id + ')">删除</span></p></th>';
                            cour += '</tr>';
                        }
                    }
                }
                $('#addLesson').css('display', 'none');
                $('.detailCourse').remove();
                $('#student_course').append(cour);
            } else {
                str += '<tr class="userCourse"><th colspan="2" class="font-bold purple-font zongji"><img src="/cn/images/course/tongji.png" class="mmtf">总计 </th>';
                str += '<th class="font-bold purple-font" id="totalTime">0</th>';
                str += '<th class="font-bold purple-font" id="useTime">0</th>';
                str += '<th></th></tr>';
            }
            $('.userCourse').remove();//移除老数据
            $('#addClass').css('display', 'none');
            $('#userCourse').append(str);
        }

    });

}

//事件选择清空
function deleteTime() {
    $('#navBeginTime').val('');
    $('#navEndTime').val('');
}

//点击class添加删除
function classDo(_this) {
    var res = $(_this).hasClass('purple-font');
    if (res) {
        $(_this).removeClass('purple-font');
    } else {
        $(_this).addClass('purple-font');
        $(_this).siblings('li').removeClass('purple-font');
    }
}

function chooseTeacher(_this, type = 1) {
    var teacher_result = $(_this).children('.name').html();
    if (type == 1) {
        var teacher = document.getElementById('bk_teacher-result');
    } else {//班课修改
        var teacher = document.getElementById('teacher-result-edit');
        $('#teacher-result-edit').attr('data-setId', ($(_this).attr('data-setId')));
    }
    teacher.value = teacher_result;
}

//老师选择 确定点击事件
function addTeacher(str, type = 1) {
    var code = 0;
    if (str == 'teacherChoice') {
        var name = $('#teacherChoice li.purple-font a').html();
        var tid = $('#teacherChoice li.purple-font').attr('data-teaId');
        $('#teacher-result2').val(name);
        $('#teacher-result2').attr('data-teaId', tid);
        code = 1;//课程条件导航内容筛选
    }
    if (str == 'subjectChoice') {
        var nam = $('#subjectChoice li.purple-font a').html();
        var setId = $('#subjectChoice li.purple-font').attr('data-setId');
        $('#subject-result4').val(nam);
        $('#subject-result4').attr('data-teaId', setId);
        code = 1;
    }
    if (str == 'chooseSubject') {
        var sub = $('#chooseSubject li.purple-font a').html();
        var subId = $('#chooseSubject li.purple-font').attr('data-setId');
        $('#subject-result').val(sub);
        $('#subject-result').attr('data-setId', subId);
        if (type == 1) {
            chooseDate('class_teacher');
        } else {
            chooseClassData('class_classroom');
        }
    }
    if (str == 'chooseTeacher') {
        var tea = $('#chooseTeacher li.purple-font a:first').html();
        var teaId = $('#chooseTeacher li.purple-font').attr('data-setId');
        $('#teacher-result').val(tea);
        $('#teacher-result').attr('data-setId', teaId);
        if (type == 1) {
            chooseDate('class_classroom');
        } else {
            chooseClassData('class_classroom');
        }
    }
    if (code == 1) {
        searchClass();
    }
}

//vip课程添加  学生 班型 科目 总课时
function saveVipClass(type) {//type 1-新增 2-修改
    var student = $('#student').attr('data-stuId');
    var classType = $('#classType').val();
    var subject = $('#subject').val();
    var totalTime = $('#totalTime1').val();
    var level = $('#level').val();
    var area = 0;
    if (level == 6) {
        area = $('#area').val();
        if (area < 1) {
            alert('请选择地区！');
            return false;
        }
    }
    if (!student || student < 1) {
        alert('请选择学生！');
        return false;
    }
    if (!classType) {
        alert('请选择班型！');
        return false;
    }
    if (!subject) {
        alert('请选择科目！');
        return false;
    }
    if (!totalTime) {
        alert('请填写总课时量！');
        return false;
    }
    var courseId = 0;
    if (type != 1) {//修改
        courseId = $('#editId').val();
    }
    $.ajax({
        url: '/cn/api/add-vip-class',
        type: 'post',
        dataType: 'json',
        data: {
            type: 1,//1-添加学生 班型 科目 总课时  2-添加日期 时间 课时 课程 老师 教师
            student: student,
            classType: classType,
            subject: subject,
            totalTime: totalTime,
            doType: type,//1-新增 2-修改
            courseId: courseId,
            area: area,
        },
        success: function (e) {
            if (e.code == 1) {
                var str = '';
                var data = e.data;
                for (var i = 0; i < data.length; i++) {
                    str += '<tr class="userCourse">';
                    if (data[i].total > 1) {
                        str += '<th id="rows' + data[i].id + '" rowspan="' + data[i].total + '">' + data[i].classType + '</th>';
                    } else {
                        str += '<th>' + data[i].classType + '</th>';
                    }
                    str += '<th>' + data[i].subject + '</th>';
                    str += '<th>' + data[i].totalTime + '</th>';
                    str += '<th>' + data[i].useTime + '</th>';
                    str += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0">';
                    if (data[i].total > 1) {
                        str += '<span class="purple-font" onclick="editVipClass(this,' + data[i].id + ',2)">编辑</span>';
                    } else {
                        str += '<span class="purple-font" onclick="editVipClass(this,' + data[i].id + ',1)">编辑</span>';
                    }
                    str += '<span class="purple-font" onclick="reduceVipClass(' + data[i].id + ')">删除</span></p>';
                    str += '</th></tr>';
                    if (data[i].total > 1) {
                        var child = data[i].child;
                        for (var j = 0; j < child.length; j++) {
                            str += '<tr class="userCourse">';
                            str += '<th style="display: none" class="hiddenType' + data[i].id + '">' + child[j].classType + '</th>';
                            str += '<th>' + child[j].subject + '</th>';
                            str += '<th>' + child[j].totalTime + '</th>';
                            str += '<th>' + child[j].useTime + '</th>';
                            str += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0">';
                            str += '<span class="purple-font" onclick="editVipClass(this,' + child[j].id + ',3,' + data[i].id + ')">编辑</span>';
                            str += '<span class="purple-font" onclick="reduceVipClass(' + child[j].id + ')">删除</span></p>';
                            str += '</th></tr>';
                        }
                    }
                }
                str += '<tr class="userCourse"><th colspan="2" class="font-bold purple-font zongji"><img src="/cn/images/course/tongji.png" class="mmtf">总计 </th>';
                str += '<th class="font-bold purple-font" id="totalTime">' + e.totalTime + '</th>';
                str += '<th class="font-bold purple-font" id="useTime">' + e.useTime + '</th>';
                str += '<th></th></tr>';
                $('.userCourse').remove();
                $('#addClass').css('display', 'none');
                $('#classroom-result').html('');
                $('#teacher-result').val('');
                $('#subject-result').val('');
                $('#time-result').val('');
                $('#class_hour').html('');
                var edit = $('#editId').val();
                if (edit) {
                    $('#addClass').remove();
                    $('#addClass1').removeAttr('id').attr('id', 'addClass');
                }
                $('#userCourse').append(str);
                $('#addButton').css('display', 'block');
                //获取学生班型对应的老师和学生
                var teac = '<li class="float-left purple-font" data-teaId=0 onclick="classDo(this)"><a class="border-0">全部</a></li>';
                var subject = '<li class="float-left purple-font" data-setId=0 onclick="classDo(this)"><a class="border-0">全部</a></li>';
                for (var k = 0; k < e.teacher.length; k++) {
                    teac += '<li class="float-left" data-teaId="' + e.teacher[k].teacherId + '" onclick="classDo(this)"><a class="name">' + e.teacher[k].name + '</a></li>';
                }
                for (var d = 0; d < e.classType.length; d++) {
                    subject += '<li class="float-left" data-setId="' + e.classType[d].setId + '" onclick="classDo(this)"><a class="border-0 ">' + e.classType[d].classType + '</a></li>';
                }
                $('#teacherChoice').html(teac);
                $('#subjectChoice').html(subject);
            } else {
                alert(e.message);
            }
        }
    });
}

//vip课程删除
function reduceVipClass(id) {
    if (confirm('确定删除该课程及下属上课安排？')) {
        $.ajax({
            url: '/cn/api/delete-class',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                type: 1,//1-vip  2-班级
            },
            success: function (e) {
                if (e.code == 1) {
                    studentClass();
                } else {
                    alert(e.message);
                }
            }
        });
    }
}

//vip课程编辑  编辑框内容初始化
function editVipClass(_this, id, type, parent = 0) {
    var edit = $('#editId').val();
    if (edit) {
        alert('已经有编辑内容，请先处理！');
        return false;
    }
    $.ajax({
        url: '/cn/api/edit-class-msg',
        type: 'post',
        dataType: 'json',
        data: {
            id: id,
        },
        success: function (e) {
            if (e.code == 1) {
                var str = '<tr id="addClass">';
                str += '<th ><select name="classType" id="classType">';
                str += '<option value="">请选择</option>';
                for (var i = 0; i < e.classType.length; i++) {
                    str += '<option value="' + e.classType[i].id + '"';
                    if (e.classType[i].id == e.course.classType) {
                        str += 'selected="selected"';
                    }
                    str += '>' + e.classType[i].name + '</option>';
                }
                str += '</select></th>';
                str += '<th><select id="subject">';
                str += '<option value="">请选择</option>';
                for (var j = 0; j < e.subject.length; j++) {
                    str += '<option value="' + e.subject[j].id + '"';
                    if (e.subject[j].id == e.course.subject) {
                        str += 'selected="selected"';
                    }
                    str += '>' + e.subject[j].name + '</option>';
                }
                str += '</select></th>';
                str += '<th><input type="text" id="totalTime1" value="' + e.course.totalTime + '"/></th>';
                str += '<th>' + e.useTime + '</th>';
                str += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0">';
                str += '<span class="purple-font" onclick="saveVipClass(2)">保存</span>';
                str += '<input type="hidden" id="editId" value="' + e.course.id + '" />';
                if (type == 2) {//合并班型的第一行
                    str += '<input type="hidden" id="thHidden" value="' + id + '" />';
                }
                if (type == 3) {
                    str += '<input type="hidden" id="rowId" value="' + parent + '" />';
                }
                str += '<span class="purple-font" onclick="returnVipClass(2)">取消</span></p></th';
                str += '</tr>';
                $('#addClass').removeAttr('id').attr('id', 'addClass1');//将默认新增框id修改
                // $('#addClass').html(str).css('display','table-row');
                $('#userCourse').prepend(str);//添加显示编辑框
                $(_this).parents('tr.userCourse').css('display', 'none');//隐藏编辑内容
                $('#addButton').css('display', 'none');//隐藏添加课程按钮
                if (type == 2) {//合并班型的第一行
                    var hid = '.hiddenType' + id;
                    $(hid).css('display', 'block');
                } else if (type == 3) {//不是合并班型的第一行
                    var r = '#rows' + parent;
                    var rows = $(r).attr('rowspan');
                    rows = parseInt(rows) - 1;
                    $(r).attr('rowspan', rows);
                }
            }
        }
    });
}

//班级课程添加  班型 科目 总课时 日期 时间 课时 老师 教室
function saveClass(type) {//type 1-新增 2-修改
    var classType = $('#classType').val();
    var studentNum = $('#studentNum').val();
    var totalTime = $('#totalTime1').val();
    var start = $('#rang-start').val();
    var end = $('#rang-end').val();
    var area = $('#area').val();
    if (!area) {
        alert('请选择地区！');
        return false;
    }
    if (!classType) {
        alert('请选择班型！');
        return false;
    }
    if (!start) {
        alert('请选择开始时间！');
        return false;
    }
    if (!end) {
        alert('请选择结束时间！');
        return false;
    }
    if (!totalTime) {
        alert('请填写总课时量！');
        return false;
    }
    var courseId = 0;
    if (type != 1) {//修改
        courseId = $('#editId').val();
    }
    $.ajax({
        url: '/cn/api/add-class',
        type: 'post',
        dataType: 'json',
        data: {
            type: 1,//1- 班型 科目 总课时  2-添加日期 时间 课时 课程 老师 教师
            classType: classType,
            studentNum: studentNum,
            totalTime: totalTime,
            doType: type,//1-新增 2-修改
            courseId: courseId,
            area: area,
            start: start,
            end: end,
        },
        success: function (e) {
            if (e.code == 1) {
                window.location.reload();
            } else {
                alert(e.message);
            }
        }
    });
}

//班级课程 删除 type 1-vip  2-班课
function reduceClass(_this, id, type = 1) {
    if (confirm('确定删除该课程及下属上课安排？')) {
        var totalTime = $('#totalTime').html();
        var useTime = $('#useTime').html();
        $.ajax({
            url: '/cn/api/delete-class',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
                type: 2,//1-vip  2-班级
            },
            success: function (e) {
                if (e.code == 1) {
                    if (type == 2) {
                        window.location.reload();
                    } else {
                        studentClass();
                    }
                } else {
                    alert(e.message);
                }
            }
        });
    }
}

//班级课程 编辑  type  1-单条班型内容  2-合并班型的第一行（第一条）  3-不是合并班型的第一行 4-合并班型的第一行（第二行开始）
function editClass(_this, id, type, parent = 0) {
    var edit = $('#editId').val();
    if (edit) {
        alert('已经有编辑内容，请先处理！');
        return false;
    }
    $.ajax({
        url: '/cn/api/edit-class-msg',
        type: 'post',
        dataType: 'json',
        data: {
            id: id,
        },
        success: function (e) {
            if (e.code == 1) {
                $.getScript('/cn/js/course/choose-date.js', function () {
                });
                var str = '<tr id="addClass">';
                str += '<th ><select name="area" id="area">';
                str += '<option value="">请选择</option>';
                for (var t = 0; t < e.area.length; t++) {
                    str += '<option value="' + e.area[t].id + '"';
                    if (e.area[t].id == e.course.area) {
                        str += 'selected="selected"';
                    }
                    str += '>' + e.area[t].name + '</option>';
                }
                str += '</select></th>';
                str += '<th ><select name="classType" id="classType">';
                str += '<option value="">请选择</option>';
                for (var i = 0; i < e.classType.length; i++) {
                    str += '<option value="' + e.classType[i].id + '"';
                    if (e.classType[i].id == e.course.classType) {
                        str += 'selected="selected"';
                    }
                    str += '>' + e.classType[i].name + '</option>';
                }
                str += '</select></th>';
                //科目
                // str += '<th><select id="subject">';
                // str +='<option value="">请选择</option>';
                // for(var j=0;j<e.subject.length;j++){
                //     str += '<option value="'+e.subject[j].id+'"';
                //     if(e.subject[j].id == e.course.subject){
                //         str += 'selected="selected"';
                //     }
                //     str += '>'+e.subject[j].name+'</option>';
                // }
                // str += '</select></th>';
                str += '<th> <div class="Time-range">';
                str += '<div class="c-datepicker-date-editor J-datepicker-day1  float-left border-0 " onclick="range_datepicker()">';
                str += '<input placeholder="" name="" class="c-datepicker-data-input "  value="' + e.course.beginTime + '" id="rang-start"></div>';
                str += '<span class="c-datepicker-range-separator float-left">-</span>';
                str += '<div class="float-left c-datepicker-date-editor J-datepicker-day1 border-0" onclick="range_datepicker()">';
                str += '<input placeholder="" name="" class="c-datepicker-data-input" value="' + e.course.endTime + '" id="rang-end"> </div> </div> </th>';
                str += '<th><input type="text" id="studentNum" value="' + e.course.studentNum + '"/></th>';
                str += '<th><input type="text" id="totalTime1" value="' + e.course.totalTime + '"/></th>';
                str += '<th>' + e.useTime + '</th>';
                str += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0">';
                str += '<span class="purple-font" onclick="saveClass(2)">保存</span>';
                str += '<input type="hidden" id="editId" value="' + e.course.id + '" />';
                if (type == 2 || type == 4) {//合并班型的第一行
                    str += '<input type="hidden" id="thHidden" value="' + id + '" />';
                }
                if (type == 3) {//不是合并班型的第一行
                    str += '<input type="hidden" id="rowId" value="' + parent + '" />';
                }
                str += '<span class="purple-font" onclick="returnVipClass(2)">取消</span></p></th';
                str += '</tr>';
                $('#addClass').removeAttr('id').attr('id', 'addClass1');//将默认新增框id修改
                $('#userCourse').prepend(str);//添加显示编辑框
                $(_this).parents('tr.userCourse').css('display', 'none');//隐藏编辑内容
                $('#addButton').css('display', 'none');//隐藏添加课程按钮
                if (type == 2) {//合并班型的第一行
                    var hid = '.hiddenType' + id;
                    $(hid).css('display', 'block');
                    $('#childAreaTotal').css('display', '');
                } else if (type == 3) {//不是合并班型的第一行
                    var r = '#rows' + parent;
                    var rows = $(r).attr('rowspan');
                    rows = parseInt(rows) - 1;
                    $(r).attr('rowspan', rows);
                } else if (type == 4) {//合并班型的第二行开始（第二条数据开始）
                    var hidd = '.hiddenType' + id;
                    $(hidd).css('display', 'block');
                }
                var areaTotal = $('#areaTotal').attr('rowspan');
                var areaRows = parseInt(areaTotal) - 1;
                $('#areaTotal').attr('rowspan', areaRows);
            }
        }
    });
}

/**
 * 获取排课信息
 * cy
 * @returns {boolean}
 */
function searchClass() {
    var level = $('#level').val();
    var area = $('#area').val();
    var student = $('#student').attr('data-stuId');
    var begin = $('#navBeginTime').val();
    var end = $('#navEndTime').val();
    var teacher = $('#teacher-result2').attr('data-teaId');//老师
    var classType = $('#subject-result4').attr('data-teaId');//班型
    // console.log(level,area,student,begin,end,teacher,classType);
    $.ajax({
        url: '/cn/api/search-vip-class',
        type: 'post',
        dataType: 'json',
        data: {
            level: level,
            area: area,
            student: student,
            begin: begin,
            end: end,
            teacher: teacher,
            classType: classType,
        },
        success: function (e) {
            //排课信息
            var cour = '';
            for (var q = 0; q < e.details.length; q++) {
                cour += '<tr class="detailCourse">';
                cour += '<th rowspan="' + e.details[q].total + '">';
                cour += '<th rowspan="' + e.details[q].total + '">';
                if (e.details[q].status == 0) {
                    cour += '<span class="ml-0 lable purple-font">预</span>';
                }
                if (e.details[q].status == -1) {
                    cour += '<span class="ml-0 lable purple-font">拒绝</span>';
                }
                cour += '' + e.details[q].dateStr + '</th>';
                cour += '<th>' + e.details[q].time + '</th>';
                cour += '<th>' + e.details[q].classTime + '</th>';
                cour += '<th>' + e.details[q].setCourse + '</th>';
                cour += '<th>' + e.details[q].teacher + '</th>';
                cour += '<th>' + e.details[q].classroom + '</th>';
                cour += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + e.details[q].id + ')">删除</span></p></th>';
                cour += '</tr>';
                if (e.details[q].total > 1) {
                    var ch = e.details[q].child;
                    for (var c = 0; c < ch.length; c++) {
                        cour += '<tr class="detailCourse">';
                        cour += '<th>' + ch[c].time + '</th>';
                        cour += '<th>' + ch[c].classTime + '</th>';
                        cour += '<th>' + ch[c].setCourse + '</th>';
                        cour += '<th>' + ch[c].teacher + '</th>';
                        cour += '<th>' + ch[c].classroom + '</th>';
                        cour += '<th class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + ch[c].id + ')">删除</span></p></th>';
                        cour += '</tr>';
                    }
                }
            }
            $('#addLesson').css('display', 'none');
            $('.detailCourse').remove();
            $('#student_course').append(cour);
            $('.allCount').html('全部（' + e.useTime + ')');
        }
    });

}

//添加排课 vip课程
function addLesson() {
    var area = $('#area').val();
    if (area < 1) {
        alert('请选择地区');
        return false;
    }
    var student = $('#student').attr('data-stuId');
    if (!student) {
        alert('请选择学生');
        return false;
    }
    var totalTime = $('#totalTime').html();
    var useTime = $('#useTime').html();
    totalTime = parseInt(totalTime);
    useTime = parseInt(useTime);
    if (totalTime > useTime) {//总课时数大于已排课时数
        $('#addLesson').css('display', 'table-row');
    } else {
        if (totalTime == 0) {
            alert('该学生还没有可排课程');
        } else {
            alert('课时已排完，请先添加新课程');
        }
    }
}

//数据选择 vip课程
function chooseDate(str) {
    var level = $('#level').val();
    var area = $('#area').val();
    var student = $('#student').attr('data-stuId');
    var begin = $('#start-time').val();
    var end = $('#end-time').val();
    var hour = getHour(begin, end);
    var date = $('#chooseDate_input').val();
    var setId = $('#subject-result').attr('data-setId');
    if (str == 'class_subject') {//获取课程
        $('#class_hour').html(hour);
        //课程数据获取嵌套
        $.ajax({
            url: '/cn/api/get-add-vip-class',
            type: 'post',
            dataType: 'json',
            data: {
                type: 1,//type 1-班型课程 2-老师 3-教室
                level: level,
                area: area,
                student: student,
                classTime: hour,
            },
            success: function (e) {
                if (e.code == 1) {
                    var subject = '';
                    for (var d = 0; d < e.subject.length; d++) {
                        subject += '<li class="float-left" data-setId="' + e.subject[d].setId + '" onclick="classDo(this)"><a class="border-0 ">' + e.subject[d].classType + '(' + e.subject[d].subject + ')</a></li>';
                    }
                    $('#chooseSubject').html(subject);
                }
            }
        });
        $('#class_teacher').css('display', 'none');
        $('#class_classroom').css('display', 'none');
        $('#classroom-result').html('');
        $('#teacher-result').val('');
        $('#subject-result').val('');
    }
    if (str == 'class_teacher') {//获取老师
        //老师数据获取嵌套
        $.ajax({
            url: '/cn/api/get-add-vip-class',
            type: 'post',
            dataType: 'json',
            data: {
                type: 2,//type 1-班型课程 2-老师 3-教室
                level: level,
                area: area,
                student: student,
                begin: begin,
                end: end,
                setId: setId,
                date: date,
            },
            success: function (e) {
                if (e.code == 1) {
                    var teacher = '';
                    for (var o = 0; o < e.teacher.length; o++) {
                        teacher += '<li class="float-left teacher-option ';
                        if (level == 6 && e.teacher[o].tired == 1) {
                            teacher += 'tired';
                        }
                        teacher += '" data-setId="' + e.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this)"><a class="name">' + e.teacher[o].name + ' </a><a class="surplus">（剩余' + e.teacher[o].hasHour + '）</a></li>';
                    }
                    $('#chooseTeacher').html(teacher);
                }
            }
        });
        $('#class_classroom').css('display', 'none');
        $('#classroom-result').html('');
    }
    if (str == 'class_classroom') {//获取教师
        $.ajax({
            url: '/cn/api/get-add-vip-class',
            type: 'post',
            dataType: 'json',
            data: {
                type: 3,//type 1-班型课程 2-老师 3-教室
                setId: setId,
                begin: begin,
                end: end,
                date: date,
            },
            success: function (e) {
                if (e.code == 1) {
                    var room = '';
                    for (var o = 0; o < e.classroom.length; o++) {
                        room += '<option value="' + e.classroom[o].id + '">' + e.classroom[o].classroom + '</option>';
                    }
                    $('#classroom-result').html(room);
                }
            }
        });
    }
    var obj = '#' + str;
    $(obj).css('display', '');
}

//添加排课 班级课程
function addClassLesson() {
    var totalTime = $('#totalTime').html();
    var useTime = $('#useTime').html();
    totalTime = parseInt(totalTime);
    useTime = parseInt(useTime);
    if (totalTime > useTime) {//总课时数大于已排课时数
        $.ajax({
            url: '/cn/api/get-add-class',
            type: 'post',
            dataType: 'json',
            data: {
                type: 1,//type 1-地区 2-班型课程 3-老师 4-教室
            },
            success: function (e) {
                if (e.code == 1) {
                    var area = '';
                    for (var i = 0; i < e.area.length; i++) {
                        area += '<option value="' + e.area[i].area + '">' + e.area[i].name + '</option>';
                    }
                    $('#area-result').html(area);
                }
            }
        });
        $('#addLesson').css('display', 'table-row');
    } else {
        if (totalTime == 0) {
            alert('还没有可排课程');
        } else {
            alert('课时已排完，请先添加新课程');
        }
    }
}

//数据选择 班级课程
function chooseClassData(str) {
    var classType = $('#detail_classType').val();
    var areaId = $('.areaId-content a.active').attr('data-areaId');
    var chooseTime = $('#chooseTime').val();//1-后台已设置时间  0-排课设置时间
    var start = '';
    var end = '';
    var dateMsg = $('#detail_date').val();
    if (classType > 0) {
        if (str == 'class_subject') {
            //课程数据获取嵌套
            $.ajax({
                url: '/cn/api/get-add-class-new',
                type: 'post',
                dataType: 'json',
                data: {
                    type: 1,//type 1-获取班课的科目及上课时间  2-老师 3-教室
                    classType: classType,
                    area: areaId,
                },
                success: function (w) {
                    var sub = '<select id="detail_subject"  onchange="chooseClassData(\'class_date\')"><option value="0">请选择</option>';
                    for (var i = 0; i < w.subjects.length; i++) {
                        sub += '<option value="' + w.subjects[i].id + '">' + w.subjects[i].name + '</option>';
                    }
                    sub += '</select>';
                    $('#class_subject').html(sub);
                    //班型日期给予
                    var date = '<select id="detail_date"  onchange="chooseClassData';
                    if (w.hadTime == 1) {
                        date += '(\'class_teacher\')">';
                    } else {
                        date += '(\'class_time\')">';
                    }
                    date += '<option value="0">请选择</option>';
                    for (var t = 0; t < w.dates.length; t++) {
                        date += '<option value="' + w.dates[t].id + '">' + w.dates[t].date + '</option>';
                    }
                    date += '</select>';
                    $('#class_date').html(date);
                    if (w.hadTime == 1) {//后台已设置上课时间  排课时无需选择
                        $('#class_time1').html(w.times);//时间内容给予
                        $('#class_hour').html(w.classTime);//课时给予
                        $('#chooseTime').val(1);//修改时间选择值 后续操作判断
                    } else {
                        $('#chooseTime').val(0);
                        $('#class_hour').html('');
                    }
                }
            });
            $('#class_time1').css('display', 'none');
            $('#class_time2').css('display', 'none');
            $('#class_date').css('display', 'none');
            $('#class_hour').css('display', 'none');
            $('#class_teacher').css('display', 'none');
            $('#class_classroom').css('display', 'none');
        }
        if (str == 'class_time') {//时间选择 后台未设置时间
            $('#class_time2').css('display', '');
        }
        if (str == 'class_teacher') {//获取老师
            var hour = 0;
            var subject = $('#detail_subject').val();
            if (chooseTime != 1) {//排课设置时间
                start = $('#start-time').val();
                end = $('#end-time').val();
                if (!start) {
                    alert('请选择开始时间！');
                    return false;
                }
                if (!end) {
                    alert('请选择结束时间！');
                    return false;
                }
                hour = getHour(start, end);
                $('#class_time2').css('display', '');//时间选择
                $('#class_time1').css('display', 'none');//时间显示
            } else {
                $('#class_time1').css('display', '');//时间显示
                $('#class_time2').css('display', 'none');//时间选择
            }
            //老师数据获取嵌套
            $.ajax({
                url: '/cn/api/get-add-class-new',
                type: 'post',
                dataType: 'json',
                data: {
                    type: 2,//type 1-获取班课的科目及上课时间  2-老师 3-教室
                    area: areaId,
                    classType: classType,
                    hadTime: chooseTime,
                    start: start,
                    end: end,
                    dateMsg: dateMsg,
                    hour: hour,
                    subject: subject,
                },
                success: function (e) {
                    var teacher = '';
                    for (var o = 0; o < e.teacher.length; o++) {
                        teacher += '<li class="float-left teacher-option ';
                        if (e.teacher[o].isTired == 1) {
                            teacher += ' tired';
                        }
                        teacher += '" data-setId="' + e.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this)"><a class="name">' + e.teacher[o].name + ' </a><a class="surplus">（剩余' + e.teacher[o].hasHour + '）</a></li>';
                    }
                    $('#chooseTeacher').html(teacher);
                    $('#class_hour').html(e.hours);
                    $('#class_hour').css('display', '');
                }
            });
        }
        if (str == 'class_classroom') {
            if (chooseTime != 1) {//排课设置时间
                start = $('#start-time').val();
                end = $('#end-time').val();
                if (!start) {
                    alert('请选择开始时间！');
                    return false;
                }
                if (!end) {
                    alert('请选择结束时间！');
                    return false;
                }
            }
            $.ajax({
                url: '/cn/api/get-add-class-new',
                type: 'post',
                dataType: 'json',
                data: {
                    type: 3,//type 1-获取班课的科目及上课时间  2-老师 3-教室
                    start: start,
                    end: end,
                    dateMsg: dateMsg,
                    hadTime: chooseTime,
                    classType: classType,
                },
                success: function (e) {
                    var room = '<select id="classroom-result"><option value="0">请选择</option>';
                    for (var p = 0; p < e.length; p++) {
                        room += '<option value="' + e[p].id + '">' + e[p].classroom + '</option>';
                    }
                    room += '</select>';
                    $('#class_classroom').html(room);
                }
            });
        }
    } else {
        return false;
    }

    var obj = '#' + str;
    $(obj).css('display', '');
}

//计算课时  时间段加上10除以60得到课时
function getHour(begin, end) {
    var arr_begin = begin.split(':');
    var arr_end = end.split(':');
    var begin_hour = parseInt(arr_begin[0]);
    var begin_minute = parseInt(arr_begin[1]);
    var end_hour = parseInt(arr_end[0]);
    var end_minute = parseInt(arr_end[1]);
    var time1 = 60 * begin_hour + begin_minute;
    var time2 = 60 * end_hour + end_minute;
    var time = time2 - time1;
    if (time < 0) {
        return 0;
    } else {
        var hour = Math.floor(10 * ((time + 10) / 60)) / 10;
        return hour;
    }
}

//取消排课 type 1-新增 2-修改
function returnVipClassDetail() {
    $('#addLesson').css('display', 'none');
}

//添加排课  vip课程
function saveVipClassDetail() {
    var setId = $('#subject-result').attr('data-setId');
    var date = $('#chooseDate_input').val();
    var begin_time = $('#start-time').val();
    var end_time = $('#end-time').val();
    var hour = $('#class_hour').html();
    var teacher = $('#teacher-result').attr('data-setId');
    var classroom = $('#classroom-result option:checked').val();
    var time = $('#time-result').val();
    var level = $('#level').val();
    var student = $('#student').attr('data-stuId');
    var area = 0;
    if (level == 6) {
        area = $('#area').val();
    }
    if (!date) {
        alert('请选择日期');
        return false;
    }
    if (!time) {
        alert('请选择时间');
        return false;
    }
    if (!setId) {
        alert('请选择课程');
        return false;
    }
    if (!teacher) {
        alert('请选择老师');
        return false;
    }
    if (!classroom) {
        alert('请选择上课教室');
        return false;
    }
    $.ajax({
        url: '/cn/api/add-vip-class-detail',
        type: 'post',
        dataType: 'json',
        data: {
            setId: setId,
            date: date,
            begin_time: begin_time,
            end_time: end_time,
            hour: hour,
            teacher: teacher,
            classroom: classroom,
            student: student,
            area: area,
            level: level,
        },
        success: function (w) {
            if (w.code == 1) {
                studentClass();
            }
        }
    });
}

//添加排课  班级课程
function saveClassDetail() {
    var area = $('.areaId-content a.active').attr('data-areaId');
    var classType = $('#detail_classType').val();
    var subject = $('#detail_subject').val();
    var dateMsg = $('#detail_date').val();
    var chooseTime = $('#chooseTime').val();//1-后台已设置时间  0-排课设置时间
    var start = '';
    var end = '';
    var hour = $('#class_hour').html();
    var teacher = $('#teacher-result').attr('data-setId');
    var classroom = $('#classroom-result').val();

    if (!area) {
        alert('请选择地区');
        return false;
    }
    if (classType < 1) {
        alert('请选择班型');
        return false;
    }
    if (subject < 1) {
        alert('请选择科目');
        return false;
    }
    if (!dateMsg || dateMsg == 0) {
        alert('请选择日期');
        return false;
    }
    if (chooseTime != 1) {
        start = $('#start-time').val();
        end = $('#end-time').val();
        if (!start) {
            alert('请选择开始时间！');
            return false;
        }
        if (!end) {
            alert('请选择结束时间！');
            return false;
        }
    }
    if (!teacher) {
        alert('请选择老师');
        return false;
    }
    if (classroom < 1) {
        alert('请选择上课教室');
        return false;
    }
    $.ajax({
        url: '/cn/api/add-class-detail',
        type: 'post',
        dataType: 'json',
        data: {
            area: area,
            classType: classType,
            subject: subject,
            dateMsg: dateMsg,
            hadTime: chooseTime,
            start: start,
            end: end,
            hour: hour,
            teacher: teacher,
            classroom: classroom,
        },
        success: function (w) {
            if (w.code == 1) {
                window.location.reload();
            } else {
                alert(w.message);
            }
        }
    });
}

//获取地区下的数据 分页
function getAreaContent(areaId, page = 1) {

}

//排课删除
function deleteDetail(id, type = 2) {
    if (confirm('确定删除该排课吗？')) {
        $.ajax({
            url: '/cn/api/delete-details',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
            },
            success: function (w) {
                if (w.code == 1) {
                    if (type == 2) {//班课
                        window.location.reload();
                    } else {
                        studentClass();
                    }
                } else {
                    alert(w.message);
                }
            }
        });
    }
}

//班级课程  排课条件获取
function changeClassLesson(id, page = 1) {
    $.ajax({
        url: '/cn/api/lesson-class',
        type: 'post',
        dataType: 'json',
        data: {
            area: id,
            page: page,
        },
        success: function (w) {
            var lesson = '';
            for (var i = 0; i < w.areaClass.length; i++) {
                lesson += '<tr class="lessonClass">';
                lesson += '<th rowspan="' + w.areaClass[i].total + '">' + w.areaClass[i].classType + '</th>';
                lesson += '<th rowspan="' + w.areaClass[i].details[0].total + '">' + w.areaClass[i].details[0].subject + '</th>';
                lesson += '<th rowspan="' + w.areaClass[i].details[0].count + '">' + w.areaClass[i].details[0].dateStr + '(' + w.areaClass[i].details[0].week + ')</th>';
                lesson += '<th>' + w.areaClass[i].details[0].time + '</th>';
                lesson += '<th>' + w.areaClass[i].details[0].classTime + '</th>';
                lesson += '<th>' + w.areaClass[i].details[0].teacher + '</th>';
                lesson += '<th>' + w.areaClass[i].details[0].classroom + '</th>';
                lesson += '<th><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + w.areaClass[i].details[0].id + ',2)">删除</span></th>';
                lesson += '</tr>';
                if (w.areaClass[i].total > 1) {
                    var child = w.areaClass[i].details;
                    for (var t = 0; t < child.length; t++) {
                        if (t != 0) {
                            lesson += '<tr class="lessonClass">';
                            lesson += '<th rowspan="' + child[t].total + '">' + child[t].subject + '</th>';
                            lesson += '<th rowspan="' + child[t].count + '">' + child[t].dateStr + '(' + child[t].week + ')</th>';
                            lesson += '<th>' + child[t].time + '</th>';
                            lesson += '<th>' + child[t].classTime + '</th>';
                            lesson += '<th>' + child[t].teacher + '</th>';
                            lesson += '<th>' + child[t].classroom + '</th>';
                            lesson += '<th><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + child[t].id + ',2)">删除</span></th>';
                        }
                        if (child[t].count > 1) {
                            for (var p = 0; p < child[t].other.length; p++) {
                                var other = child[t].other;
                                lesson += '<tr class="lessonClass">';
                                lesson += '<th>' + other[p].time + '</th>';
                                lesson += '<th>' + other[p].classTime + '</th>';
                                lesson += '<th>' + other[p].teacher + '</th>';
                                lesson += '<th>' + other[p].classroom + '</th>';
                                lesson += '<th><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + other[p].id + ',2)">删除</span></th>';
                            }
                        }
                        if (child[t].total > child[t].count) {
                            var cont = child[t].child;
                            for (var d = 0; d < cont.length; d++) {
                                lesson += '<tr class="lessonClass">';
                                lesson += '<th rowspan="' + cont[d].total + '">' + cont[d].dateStr + '(' + cont[d].week + ')</th>';
                                lesson += '<th>' + cont[d].time + '</th>';
                                lesson += '<th>' + cont[d].classTime + '</th>';
                                lesson += '<th>' + cont[d].teacher + '</th>';
                                lesson += '<th>' + cont[d].classroom + '</th>';
                                lesson += '<th><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + cont[d].id + ',2)">删除</span></th>';
                                if (cont[d].total > 1) {
                                    for (var h = 0; h < cont[d].child.length; h++) {
                                        var chi = cont[d].child;
                                        lesson += '<tr class="lessonClass">';
                                        lesson += '<th>' + chi[d].time + '</th>';
                                        lesson += '<th>' + chi[d].classTime + '</th>';
                                        lesson += '<th>' + chi[d].teacher + '</th>';
                                        lesson += '<th>' + chi[d].classroom + '</th>';
                                        lesson += '<th><p class="d-flex flex-wrap  justify-content-around mb-0"><span class="purple-font" onclick="deleteDetail(' + chi[d].id + ',2)">删除</span></th>';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            var classty = '';
            classty += '<option value="0" >请选择</option>';
            for (var p = 0; p < w.classType.length; p++) {
                classty += '<option value="' + w.classType[p].id + '">' + w.classType[p].name + '</option>';
            }
            $('#detail_classType').html(classty);
            $('.lessonClass').remove();
            $('#student_course').append(lesson);
            $('.pageHtml2').html(w.pageHtml2);
            pageClick();
        }
    });
}


//今日课表  导航条件搜索课程
function search(_this, type) {//type 1-地区 2-科目 3-时间 4-类型 4-老师
    if (type == 1) {
        var area = $(_this).attr('data-area');
        sessionStorage.setItem('area', area);
        //获取地区下的老师
        // getAreaTeacher(area);
    }
    if (type == 2) {
        var subject = $(_this).attr('data-subject');
        $('#filter_subject .purple-font').removeClass('purple-font');
        $(_this).addClass('purple-font');
        sessionStorage.setItem('subject', subject);
    }
    if (type == 4) {
        var classType = $(_this).attr('data-type');
        sessionStorage.setItem('classType', classType);
    }
    if (type == 5) {
        var teacher = $(_this).attr('data-teaId');
        sessionStorage.setItem('teacher', teacher);
    }
    if (type == 3) {
        filter_data_start();
        filter_data_end();
    }
    sessionStorage.setItem('page', 1);
    searchCourse();
}

//获取对应地区下的老师
function getAreaTeacher(area = 0) {
    $.ajax({
        url: '/cn/api/get-area-teacher',
        dataType: 'json',
        type: 'post',
        data: {
            area: area,
        },
        success: function (e) {
            var teach = '<li class="float-left all filter-item purple-font font-weight" data-teaId=0 onclick="search(this,5)">全部</li>';
            for (var i = 0; i < e.length; i++) {
                teach += '<li class="float-left filter-item" onclick="search(this,5)" data-teaId="' + e[i].id + '">' + e[i].name + '</li>';
            }
            $('#filter_teacher').html(teach);
        }
    });
}

$('#filter_start').click(function () {
    filter_data_start();
});
$('#filter_end').click(function () {
    filter_data_end();
});

//获取数据
function searchCourse(obj = '') {
    var str = '#' + obj;
    var area = sessionStorage.getItem('area');
    var subject = sessionStorage.getItem('subject');
    var start = sessionStorage.getItem('startTime');
    var end = sessionStorage.getItem('endTime');
    var classType = sessionStorage.getItem('classType');
    var teacher = sessionStorage.getItem('teacher');
    var contentId = $('#contentId').val();
    var page = sessionStorage.getItem('page');
    if (!page || page < 1) {
        page = 1;
    }
    var level = $('#levelUser').val();
    if (contentId == 1) {//今日课表
        $.ajax({
            url: '/cn/api/today-course',
            type: 'post',
            dataType: 'json',
            data: {
                area: area,
                subject: subject,
                classType: classType,
                teacher: teacher,
                page: page
            },
            success: function (e) {
                $('#vipCount').html('VIP课（' + e.vipCount + '）');
                $('#classCount').html('班课（' + e.classCount + '）');
                $('#teacherCount').html('老师数（' + e.teacherCount + '）');
                $('#timeCount').html('总课时（' + e.timeCount + '）');
                var course = '';
                for (var i = 0; i < e.course.length; i++) {
                    course += '<tr>';
                    course += '<th>' + e.course[i].object + '</th>';
                    course += '<th>' + e.course[i].time + '</th>';
                    course += '<th>' + e.course[i].classTime + '</th>';
                    course += '<th>' + e.course[i].setCourse + '</th>';
                    course += '<th>' + e.course[i].teacher + '</th>';
                    course += '<th>' + e.course[i].areaStr + '</th>';
                    course += '<th>' + e.course[i].classroom + '</th>';
                    course += '<th>' + e.course[i].username + '</th>';
                    course += '</tr>';
                }
                $('#todayCourse').html(course);
                if (obj) {
                    $(str).html(e.pageHtml);
                } else {
                    $('#todayCoursePage').html(e.pageHtml);
                }
                pageClick();
                courseLook();
            }
        });
    }
    if (contentId == 2) {//vip课表
        var stuName = $('#stuName').val();
        $.ajax({
            url: '/cn/api/vip-course',
            type: 'post',
            dataType: 'json',
            data: {
                area: area,
                subject: subject,
                teacher: teacher,
                start: start,
                end: end,
                stuName: stuName,
                page: page
            },
            success: function (e) {
                $('#studentCount').html('(' + e.studentCount + ')');
                $('#teacherCount').html('(' + e.teacherCount + ')');
                $('#totalCount').html('(' + e.totalCount + ')');
                var course = '';
                for (var i = 0; i < e.data.length; i++) {
                    course += '<tr>';
                    course += '<th>' + e.data[i].studentName + '</th>';
                    course += '<th>' + e.data[i].classType + '</th>';
                    course += '<th>' + e.data[i].totalTime + '</th>';
                    course += '<th>' + e.data[i].signing + '</th>';
                    course += '<th>' + e.data[i].teachers + '</th>';
                    course += '<th>' + e.data[i].areaStr + '</th>';
                    course += '<th>' + e.data[i].username + '</th>';
                    course += '<th class="purple-font"><a href="#" data-vipCourse="1" data-setId="' + e.data[i].setId + '" class="purple-font ';
                    if (level == 6) {
                        course += 'vip-see-kebiao';
                    } else {
                        course += 'xgsee-kebiao';
                    }
                    course += '">查看课表</a></th>';
                    course += '</tr>';
                }
                $('#vipCourse').html(course);
                if (obj) {
                    $(str).html(e.pageHtml);
                } else {
                    $('#vipCoursePage').html(e.pageHtml);
                }
                pageClick();
                courseLook();
            }
        });
    }

    if (contentId == 4) {//课表统计
        var studName = $('#stuName').val();
        $.ajax({
            url: '/cn/api/class-count',
            type: 'post',
            dataType: 'json',
            data: {
                area: area,
                subject: subject,
                classType: classType,
                start: start,
                end: end,
                student: studName,
                page: page
            },
            success: function (e) {
                $('#teacherNum').html('(' + e.teacherCount + ')');
                var teac = '';
                for (var i = 0; i < e.teacher.length; i++) {
                    teac += '<tr>';
                    teac += '<th>' + e.teacher[i].name + '</th>';
                    teac += '<th>' + e.teacher[i].areaStr + '</th>';
                    teac += '<th>' + e.teacher[i].classType + '</th>';
                    teac += '<th>' + e.teacher[i].totalTime + '</th>';
                    teac += '<th>' + e.teacher[i].work + '</th>';
                    teac += '<th>' + e.teacher[i].reset + '</th>';
                    teac += '<th class="purple-font"><a href="/cn/course-detail/teacher-course?teacherId=' + e.teacher[i].teacherId + '&area=' + e.teacher[i].area + '">查看课表</a> </th>';
                    teac += '</tr>';
                }
                $('#teacherCount').html(teac);
                $(str).html(e.pageHtml);
                pageClick();
                courseLook();
            }
        });
    }

    // console.log(area,subject,start,end,classType,teacher);return;
};

/*function keyEnd(event){
    console.log('keyEnd');
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
            console.log('enter');
        }
    };
}*/

function filter_data_start() {

    var start = $('#filter_start').val();
    sessionStorage.setItem('startTime', start);
    searchCourse();
}

function filter_data_end() {

    var end = $('#filter_end').val();
    sessionStorage.setItem('endTime', end);
    searchCourse();

}

//老师课时搜索  type 1-地区 2-科目 3-时间
function searchTeacher(_this, type) {
    if (type == 1) {
        var area = $(_this).attr('data-area');
        sessionStorage.setItem('day-area', area);
    }
    if (type == 2) {
        var subject = $(_this).attr('data-subject');
        $('#filter_subject .purple-font').removeClass('purple-font');
        $(_this).addClass('purple-font');
        sessionStorage.setItem('day-subject', subject);
    }
    teacherCount();
}

//老师课时统计  获取数据
function teacherCount() {
    var area = sessionStorage.getItem('day-area');
    var day = sessionStorage.getItem('day-time');
    var subject = sessionStorage.getItem('day-subject');
    var teacher = $('#teacherName').val();
    $.ajax({
        url: '/cn/api/teacher-count',
        type: 'post',
        dataType: 'json',
        data: {
            area: area,
            day: day,
            subject: subject,
            teacher: teacher,
        },
        success: function (w) {
            var teacher = '';
            for (var i = 0; i < w.teacher.length; i++) {
                teacher += '<li class="teacher-item">';
                teacher += '<p class="mb-0">' + w.teacher[i].name + '</p>';
                teacher += '<p class="font-8 mb-0">（剩余' + w.teacher[i].hasTime + '）</p>';
                teacher += '<li>';
            }
            $('#teacherCount').html(teacher);
            $('#teacherNum').html('(' + w.teacherCount + ')');
        }
    });
}

//消息查看
function lookMessage(type = 0) {
    $('#messageSee').val(type);
    // $.ajax({
    // url: '/cn/api/look-message',
    // type: 'post',
    // dataType: 'json',
    // data: {},
    // success: function (e) {
    //     if (e.code == 1) {
    //         var count = 0;
    //         $('#messageCount').html(count);
    // }
    // }
    // });
}

//消息查看 获取消息  type  1-未查看 2-已查看 3-筛选条件
function getMessage(type) {
    var start = sessionStorage.getItem('filter_begin');
    var end = sessionStorage.getItem('filter_end');
    var classType = sessionStorage.getItem('data-classType');
    var teacher = sessionStorage.getItem('data-teacher');
    var area = sessionStorage.getItem('data-area');
    var userId = sessionStorage.getItem('data-userId');
    var level = $('#messageLevel').val();
    var contentId = $('#contentId').val();
    var page = sessionStorage.getItem('page');
    if (!page) page = 1;
    if (type == 3) {//判断是未查看还是已查看内容
        if ($('#nav-toAudit-tab').hasClass('active')) {
            type = 1;
        } else {
            type = 2;
        }
    }
    $.ajax({
        url: '/cn/api/get-message',
        type: 'post',
        dataType: 'json',
        data: {
            type: type,
            start: start,
            end: end,
            classType: classType,
            teacher: teacher,
            area: area,
            userId: userId,
            page: page,
        },
        success: function (e) {
            var message = '';
            $('#willScan').html(e.willScan);
            $('#scaned').html(e.scaned);
            for (var i = 0; i < e.message.length; i++) {
                message += '<tr>';
                message += '<th>' + e.message[i].lookTime + '</th>';
                message += '<th>' + e.message[i].object + '</th>';
                message += '<th>' + e.message[i].classType + '</th>';
                message += '<th>' + e.message[i].teacher + '</th>';
                message += '<th>' + e.message[i].areaStr + '</th>';
                message += '<th>' + e.message[i].username + '</th>';
                message += '<th>' + e.message[i].remark + '</th>';
                message += '<th class="purple-font">';
                if (level == 5) {
                    message += '<a data-seeId="1" href="#" class="xgsee-kebiao" data-setId="' + e.message[i].setId + '">';
                } else {
                    message += '<a data-seeId="1" href="#" class="';
                    if (e.message[i].type == 1) {
                        message += 'vip-see-kebiao';
                    } else {
                        message += 'class-see-kebiao';
                    }
                    message += '" data-setId="' + e.message[i].setId + '">';
                }
                message += '查看课表</a></th>';
                message += '</tr>';
            }
            $('#messageCourse').html(message);
            $('#msgCourse').html(e.pageHtml);
            sessionStorage.setItem('page', 1);
            pageClick();
            courseLook();

        }
    });
}

//信息查看  筛选条件
function addMessageValue(_this, str) {
    var obe = 'data-' + str;
    var value = $(_this).attr(obe);
    var inp = '.message-' + str;
    sessionStorage.setItem(obe, value);
    getMessage(3);
}

//我的课程  点击事件
function myCourseSearch(_this, str) {
    var obe = 'data-' + str;
    var key = 'myCourse-' + str;
    var value = $(_this).attr(obe);
    sessionStorage.setItem(key, value);
    var typ = $('#myCourseType').val(); //type vip-vip（教务、学管-1）  class-班课（教务-2）
    var type = 1;
    if (typ == 'class') {
        type = 2;
    }
    getMyCourse(type);
}

//获去我的课程信息  type  1-vip  2-班课
function getMyCourse(type) {
    var area = sessionStorage.getItem('myCourse-area');
    var subject = sessionStorage.getItem('myCourse-subject');
    var start = sessionStorage.getItem('filter_begin');
    var end = sessionStorage.getItem('filter_end');
    var teacher = sessionStorage.getItem('myCourse-teacher');
    var status = sessionStorage.getItem('myCourse-status');
    var stuName = $('#stuName').val();
    console.log('getMyCourse');
    var page = sessionStorage.getItem('page');
    if (!page || page < 1) {
        page = 1
    }
    var level = $('#levelUser').val();
    // console.log(area,subject,start,end,teacher,status,stuName);return;
    $.ajax({
        url: '/cn/api/get-my-course',
        type: 'post',
        dataType: 'json',
        data: {
            type: type,
            area: area,
            subject: subject,
            start: start,
            end: end,
            teacher: teacher,
            status: status,
            stuName: stuName,
            page: page,
        },
        success: function (e) {
            if (type == 2) {
                $('#courseCount').html('(' + e.courseCount + ')');
                $('#timeCount').html('(' + e.timeCount + ')');
                $('#teacherCount').html('(' + e.teacherCount + ')');
                var course = '';
                var cou = e.course;
                for (var i = 0; i < e.course.length; i++) {
                    course += '<tr>';
                    course += '<th>' + cou[i].classType + '</th>';
                    course += '<th>';
                    for (var q = 0; q < cou[i].course.length; q++) {
                        course += '<p class="mb-0">' + cou[i].course[q].date + '<span class="fs-12 font-7">（' + cou[i].course[q].week + '）</span></p>';
                    }
                    course += '</th><th>';
                    for (var t = 0; t < cou[i].course.length; t++) {
                        course += '<p class="mb-0">' + cou[i].course[t].beginTime + '-' + cou[i].course[t].endTime + '</p>';
                    }
                    course += '</th>';
                    course += '<th>' + cou[i].totalTime + '</th>';
                    course += '<th>' + cou[i].subject + '</th>';
                    course += '<th>';
                    for (var p = 0; p < cou[i].course.length; p++) {
                        course += '<p class="mb-0">' + cou[i].course[p].teacher + '</p>';
                    }
                    course += '</th><th>' + cou[i].areaStr + '</th><th>';
                    for (var c = 0; c < cou[i].course.length; c++) {
                        course += '<p class="mb-0">' + cou[i].course[c].classroom + '</p>';
                    }
                    course += '</th><th>' + cou[i].username + '</th>';
                    course += '<th class="purple-font"> <a href="#" class="purple-font ';
                    course += 'vip-see-kebiao" data-setId="' + cou[i].setId + '">查看课表</a></th>';
                    course += '</tr>';
                }
                $('#classCourse').html(course);
                $('#myCourse_class').html(e.pageHtml);
                pageClick();
                courseLook();
            } else {
                $('#studentCount').html('(' + e.studentCount + ')');
                $('#teacherCount').html('(' + e.teacherCount + ')');
                var cour = '';
                for (var x = 0; x < e.data.length; x++) {
                    cour += '<tr>';
                    cour += '<th>' + e.data[x].studentName + '</th>';
                    cour += '<th>' + e.data[x].classType + '</th>';
                    cour += '<th>' + e.data[x].totalTime + '</th>';
                    cour += '<th>' + e.data[x].useTime + '</th>';
                    cour += '<th>' + e.data[x].signing + '</th>';
                    cour += '<th>' + e.data[x].teachers + '</th>';
                    if (e.level == 5) {
                        cour += '<th>' + e.data[x].remark + '</th>';
                    } else {
                        cour += '<th>' + e.data[x].areaStr + '</th>';
                    }
                    cour += '<th class="purple-font"> <a href="#" class="purple-font ';
                    if (level == 5) {
                        cour += 'xgsee-kebiao';
                    } else {
                        cour += 'vip-see-kebiao ';
                    }
                    cour += '" data-setId="' + e.data[x].setId + '">查看课表</a></th>';
                    cour += '</tr>';
                }
                $('#vipCourse').html(cour);
                $('#myCourse_vip').html(e.pageHtml);
                pageClick();
                courseLook();
            }
        }
    });
}

//时间清空
function clearTime(str) {
    sessionStorage.setItem('filter_begin', '');
    sessionStorage.setItem('filter_end', '');
    if (str == 'msg') {//消息查看
        $('#message_start').val('');
        $('#message_end').val('');
        getMessage(3);
    }
    if (str == 'check') {//审核功能
        $('#check_start').val('');
        $('#check_end').val('');
        getCheckCourse(4);
    }
}

//审核功能 筛选条件
function addCheckValue(_this, str) {
    var obe = 'data-' + str;
    var value = $(_this).attr(obe);
    var inp = 'check-' + str;
    sessionStorage.setItem(inp, value);
    getCheckCourse(4);
}

//审核功能 获取课程  type  1-待审核 2-通过 3-拒绝 4-筛选条件
function getCheckCourse(type) {
    var start = sessionStorage.getItem('filter_begin');
    var end = sessionStorage.getItem('filter_end');
    var classType = sessionStorage.getItem('check-classType');
    var teacher = sessionStorage.getItem('check-teacher');
    var area = sessionStorage.getItem('check-area');
    var userId = sessionStorage.getItem('check-userId');
    var contentId = $('#checkContentId').val();
    var page = sessionStorage.getItem('page');
    if (!page || page < 1) {
        page = 1;
    }
    if (type == 4) {//判断是未查看还是已查看内容
        if ($('.checking').hasClass('active')) {
            type = 1;
        } else if ($('.checkSuccess').hasClass('active')) {
            type = 2;
        } else {
            type = 3;
        }
    } else {
        start = '';
        end = '';
        classType = 0;
        teacher = 0;
        area = 0;
        userId = 0;
    }
    // console.log(start,end,classType,teacher,area,userId);return;
    $.ajax({
        url: '/cn/api/get-check-course',
        type: 'post',
        dataType: 'json',
        data: {
            type: type,
            start: start,
            end: end,
            classType: classType,
            teacher: teacher,
            area: area,
            userId: userId,
            page: page,
        },
        success: function (e) {
            var check = '';
            for (var i = 0; i < e.check.length; i++) {
                check += '<tr>';
                check += '<th>' + e.check[i].studentName + '</th>';
                check += '<th>' + e.check[i].classType + '</th>';
                check += '<th>' + e.check[i].totalTime + '</th>';
                check += '<th>' + e.check[i].useTime + '</th>';
                check += '<th>' + e.check[i].teachers + '</th>';
                check += '<th>' + e.check[i].remark + '</th>';
                check += '<th>' + e.check[i].areaStr + '</th>';
                check += '<th>' + e.check[i].username + '</th>';
                check += '<th class="purple-font"><a  href="#" class="purple-font jw-check-course" data-setId="' + e.check[i].setId + '">查看课表</a></th>';
                check += '</tr>';
            }
            $('#checkCourse').html(check);
            $('#check_course').html(e.pageHtml);
            pageClick();
            sessionStorage.setItem('page', 1);
            courseLook();
        }
    });
}

//老师课表筛选
function getTeacherCourse(type, year = 0, month = '01') {
    $('#teacher_classType').val(type);
    var begin = sessionStorage.getItem('filter_begin');
    var end = sessionStorage.getItem('filter_end');
    var teacherId = $('#teacherId').val();
    var area = $('#teacher_area').val();
    var page = sessionStorage.getItem('page');
    if (!page) {
        page = 1;
    }
    // console.log(type,begin,end);return;
    $.ajax({
        url: '/cn/api/get-teacher-details',
        type: 'post',
        dataType: 'json',
        data: {
            teacher: teacherId,
            area: area,
            begin: begin,
            end: end,
            classType: type,
            page: page,
            year: year,
            month: month,
        },
        success: function (w) {
            // $('.allCourse').html('全部（'+w.totals+'）');
            // var classType = '';
            // for(var i=0;i<w.classType.length;i++){
            //     classType += '<a class="nav-item nav-link classCourse ';
            //     if(type == w.classType[i].id){
            //         classType += ' active';
            //     }
            //     classType +=
            //         '" id="nav-toAudit-tab" data-toggle="tab" data-cid="'+w.classType[i].id+'" href="#nav-toAudit" role="tab" aria-controls="nav-home" aria-selected="true" onclick="getTeacherCourse('+w.classType[i].id+','+w.year+',\''+w.month+'\')">'+w.classType[i].classType+'（'+w.classType[i].times+'）</a>';
            // }
            // $('.classCourse').remove();
            // $('.courses').append(classType);
            var details = '';
            for (var t = 0; t < w.details.length; t++) {
                details += '<tr>';
                details += '<th rowspan="' + w.details[t].total + '"><p class="mb-0">' + w.details[t].dateStr + '</p><p class="mb-0">' + w.details[t].week + '</p></th>';
                details += '<th>' + w.details[t].beginTime + '-' + w.details[t].endTime + '</th>';
                details += '<th>' + w.details[t].classTime + '</th>';
                details += '<th>' + w.details[t].classType + '(' + w.details[t].subject + ')</th>';
                details += '<th>' + w.details[t].student + '</th>';
                details += '<th>' + w.details[t].areaStr + '</th>';
                details += '<th>' + w.details[t].classroom + '</th>';
                details += '<th class="font-7">' + w.details[t].username + '</th>';
                if (year > 0) {
                    if (w.details[t].classStatus == -1) {
                        details += '<th>已上课</th>';
                    }
                    if (w.details[t].classStatus == 2) {
                        details += '<th>未开始</th>';
                    }
                    if (w.details[t].classStatus == 1) {
                        details += '<th><a href="' + w.details[t].classroomUrl + '">进入教室</a></th>';
                    }
                }
                details += '</tr>';
                if (w.details[t].total > 1) {
                    for (var r = 0; r < w.details[t].child.length; r++) {
                        details += '<tr>';
                        details += '<th>' + w.details[t].child[r].classType + '-' + w.details[t].child[r].endTime + '</th>';
                        details += '<th>' + w.details[t].child[r].classTime + '</th>';
                        details += '<th>' + w.details[t].child[r].classType + '(' + w.details[t].child[r].subject + ')</th>';
                        details += '<th>' + w.details[t].child[r].student + '</th>';
                        details += '<th>' + w.details[t].child[r].areaStr + '</th>';
                        details += '<th>' + w.details[t].child[r].classroom + '</th>';
                        details += '<th class="font-7">' + w.details[t].child[r].username + '</th>';
                        if (year > 0) {
                            if (w.details[t].child[r].classStatus == -1) {
                                details += '<th>已上课</th>';
                            }
                            if (w.details[t].child[r].classStatus == 2) {
                                details += '<th>未开始</th>';
                            }
                            if (w.details[t].child[r].classStatus == 1) {
                                details += '<th><a href="' + w.details[t].classroomUrl + '">进入教室</a></th>';
                            }
                        }
                        details += '</tr>';
                    }
                }
            }
            $('#teacherCourse').html(details);
            $('#teacher_course').html(w.pageHtml);
            pageClick();
            courseLook();
            sessionStorage.setItem('page', 1);
        }
    });
}

//排课审核
function checkCourse(id) {
    var obj = '#checkCourse' + id;
    var value = $(obj).val();
    $.ajax({
        url: '/cn/api/check-course',
        type: 'post',
        dataType: 'json',
        data: {
            id: id,
            status: value,
        },
        success: function (w) {
            if (w.code == 1) {
                window.location.reload();
            } else {
                alert(w.message);
            }
        }
    });
}

//我的课表 年份变化 数据获取
function getYearContent(month) {
    if (month == '一月') {
        month = '01';
    }
    if (month == '二月') {
        month = '02';
    }
    if (month == '三月') {
        month = '03';
    }
    if (month == '四月') {
        month = '04';
    }
    if (month == '五月') {
        month = '05';
    }
    if (month == '六月') {
        month = '06';
    }
    if (month == '七月') {
        month = '07';
    }
    if (month == '八月') {
        month = '08';
    }
    if (month == '九月') {
        month = '09';
    }
    if (month == '十月') {
        month = '10';
    }
    if (month == '十一月') {
        month = '11';
    }
    if (month == '十二月') {
        month = '12';
    }
    if (!month) {
        month = '';
    }
    var year = $('#year').val();
    if (!year) {
        alert('请选择哪一年！');
        return false;
    }
    window.location.href = '/cn/teacher-count/index?year=' + year + '&month=' + month;
}

/**
 * 顶部提示操作
 * 老师课时查看功能
 */
function cancel() {
    var check = $('#notice:checked').val();
    if (check == 1) {//勾选了选择框
        sessionStorage.setItem('notice', 1);
        $('.notice').css('display', 'none');
    } else {
        $('.notice').css('display', 'none');
    }
}

//班课排课 分页数据获取 type  1-获取班型分页数据（上半部分） 2-排课内容（下半部分）
function classPageContent(page, type = 1) {
    if (type == 1) {
        $.ajax({
            url: '/cn/api/class-page-content',
            type: 'post',
            dataType: 'json',
            data: {
                page: page,
                type: type,
            },
            success: function (w) {

                $('#totalTime').html(w.totalTime);
                $('#useTime').html(w.useTime);
                var course = '';
                for (var i = 0; i < w.course.length; i++) {
                    course += '<tr class="userCourse">';
                    if (i == 0) {
                        course += '<th id="areaTotal"  rowspan="' + w.areaTotal + '">' + w.areaStr + '</th>';
                    }
                    course += '<th id="rows' + w.course[i].id + '" rowspan="' + w.course[i].total + '">' + w.course[i].classType + '</th>';
                    course += '<th>' + w.course[i].start + '-' + w.course[i].end + '</th>';
                    course += '<th>' + w.course[i].studentNum + '</th>';
                    course += '<th>' + w.course[i].totalTime + '</th>';
                    course += '<th>' + w.course[i].useTime + '</th>';
                    course += '<th class="handle"> <p class="d-flex flex-wrap  justify-content-around mb-0">';
                    course += '<span class="purple-font" onclick="editClass(this,' + w.course[i].id + ',';
                    if (w.course[i].total > 1) {
                        if (i > 0) {
                            course += '4)">编辑</span>';
                        } else {
                            course += '2)">编辑</span>';
                        }
                    } else {
                        course += '1)">编辑</span>';
                    }
                    course += '<span class="purple-font" onclick="reduceClass(this,' + w.course[i].id + ',2)">删除</span>';
                    course += '</p></th></tr>';
                    if (w.course[i].total > 1) {
                        var childs = w.course[i].child;
                        for (var t = 0; t < childs.length; t++) {
                            course += '<tr class="userCourse"><th style="display: none" class="hiddenType' + childs[t].id + '">' + childs[t].classType + '</th>';
                            course += '<th>' + childs[t].start + '-' + childs[t].end + '</th>';
                            course += '<th>' + childs[t].totalTime + '</th>';
                            course += '<th>' + childs[t].useTime + '</th>';
                            course += '<th  class="handle"><p class="d-flex flex-wrap  justify-content-around mb-0"> <span class="purple-font" onclick="editClass(this,' + childs[t].id + ',3,' + w.course[i].id + ')">编辑</span>';
                            course += '<span class="purple-font"onclick="reduceClass(this,' + childs[t].id + ')">删除</span> </p> </th> </tr>';
                        }
                    }
                }
                course += '<tr class="userCourse">';
                course += '<th colspan="4" class="font-bold purple-font zongji"><img src="/cn/images/course/tongji.png" class="mmtf">总计 </th>';
                course += '<th class="font-bold purple-font" id="totalTime">' + w.totalTime + '</th>';
                course += '<th class="font-bold purple-font" id="useTime">' + w.useTime + '</th>';
                course += '<th></th></tr>';
                $('.userCourse').remove();
                $('#addClass').css('display', 'none');
                $('#userCourse').append(course);
            }
        });
    } else {
        var area = $('.areaId-content a.active').attr('data-areaId');
        changeClassLesson(area, page);
    }
}

/**
 * 班型数据改变
 * cy
 * vip 排课
 */
function classTypeClass(myCourse = 0) {
    $('.stuClass').remove();
    var area = $('#area').val();
    var classType = $('#classType').val();
    var student = $('#student').attr('data-stuId');
    var levelUser = $('#levelUser').val();
    var statusSee = $('#statusSee').val();// 1-需要显示状态信息  0-不需要
    var checkStatus = $('#checkStatus').val();//1-审核模式
    if (area > 0 && classType > 0 && student) {
        if (statusSee == 1) {
            $('.status_see').css('display', '');
        } else {
            $('.status_see').css('display', 'none');
        }

        $.ajax({
            url: '/cn/api/vip-class',
            type: 'post',
            dataType: 'json',
            data: {
                area: area,
                student: student,
                classType: classType,
                myCourse: myCourse,
            },
            success: function (e) {
                $('#editVipCourse').css('display', 'none');
                if (e.code == 1) {
                    $('#allTime').val(e.data.allTime);
                    $('#useTime').html(e.data.useTime);
                    var cou = '';
                    for (var i = 0; i < e.data.course.length; i++) {
                        cou += '<tr class="stuClass">';
                        cou += '<th>' + e.data.course[i].subject + '</th>';
                        cou += '<th>' + e.data.course[i].dateStr + '（' + e.data.course[i].week + '）</th>';
                        cou += '<th>' + e.data.course[i].time + '</th>';
                        cou += '<th>' + e.data.course[i].classTime + '</th>';
                        cou += '<th>' + e.data.course[i].teacher + '</th>';
                        cou += '<th>' + e.data.course[i].classroom + '</th>';
                        if (levelUser == 5 && statusSee == 1) {//学管
                            cou += '<th ';
                            if (e.data.course[i].status == 1) {
                                cou += 'style="color:green;"';
                            }
                            if (e.data.course[i].status == -1) {
                                cou += 'style="color:red;"';
                            }
                            cou += '>';
                            if (e.data.course[i].status == 0) {
                                cou += '待审核';
                            }
                            if (e.data.course[i].status == 1) {
                                cou += '通过';
                            }
                            if (e.data.course[i].status == -1) {
                                cou += '拒绝';
                            }

                            cou += '</th>';
                        }
                        if (levelUser == 6 && statusSee == 1) {//学管
                            if (checkStatus == 1) {
                                cou += '<th>';
                                cou += '<select id="jwCheck' + e.data.course[i].id + '" onchange="jwCheck(' + e.data.course[i].id + ',' + e.data.course[i].status + ')">';
                                cou += '<option value="0" ';
                                if (e.data.course[i].status == 0) {
                                    cou += 'selected';
                                }
                                cou += '>待审核</option>';
                                cou += '<option value="1"  style="color:green;" ';
                                if (e.data.course[i].status == 1) {
                                    cou += 'selected';
                                }
                                cou += '>通过</option>';
                                cou += '<option value="-1" style="color:red;" ';
                                if (e.data.course[i].status == -1) {
                                    cou += 'selected';
                                }
                                cou += '>拒绝</option>';
                                cou += '</select>';
                                cou += '</th>';
                            } else {//消息查看进入 只能查看 不能操作
                                cou += '<th ';
                                if (e.data.course[i].status == 1) {
                                    cou += 'style="color:green;"';
                                }
                                if (e.data.course[i].status == -1) {
                                    cou += 'style="color:red;"';
                                }
                                cou += '>';
                                if (e.data.course[i].status == 0) {
                                    cou += '待审核';
                                }
                                if (e.data.course[i].status == 1) {
                                    cou += '通过';
                                }
                                if (e.data.course[i].status == -1) {
                                    cou += '拒绝';
                                }
                            }

                            cou += '</th>';
                        }
                        cou += '<th><p class="d-flex flex-wrap  justify-content-around mb-0">';
                        if (levelUser == 5 || levelUser == 6) {
                            cou += '<span class="purple-font alledit" id="editId' + e.data.course[i].id + '" onclick="classEdit(' + e.data.course[i].id + ')">编辑</span>';
                            cou += '<span class="purple-font allreturn" id="return' + e.data.course[i].id + '"  onclick="returnClass(' + e.data.course[i].id + ',1)" style="display: none;">取消</span>';
                            cou += '<span class="purple-font" onclick="classDelete(' + e.data.course[i].id + ',1)">删除</span>';
                        }
                        cou += '</p></th></tr>';
                    }
                    $('#vipCourses').append(cou);
                }
            }
        });
    }
}

//vip排课 内容获取
function sureVipClass(str) {
    var classType = $('#classType').val();
    var subject = $('#subject').val();
    var date = $('#chooseDate_input').val();
    var begin = $('#start-time').val();
    var end = $('#end-time').val();
    if (str == 'class_date') {
        clearVipAdd(1);
    }
    // if(str == '')
    if (str == 'class_teacher') {
        hour = getHour(begin, end);
        $('#class_classTime').html(hour);
        $('#class_classTime').css('display', '');
        //老师数据获取嵌套
        $.ajax({
            url: '/cn/api/vip-class-data',
            type: 'post',
            dataType: 'json',
            data: {
                type: 1,//type 1-老师 2-教室
                classType: classType,
                subject: subject,
                date: date,
                begin: begin,
                end: end,
            },
            success: function (e) {
                if (e.code == 1) {
                    var teacher = '';
                    for (var o = 0; o < e.teacher.length; o++) {
                        teacher += '<li class="float-left teacher-option ';
                        if (e.teacher[o].tired == 1) {
                            teacher += 'tired';
                        }
                        teacher += '" data-setId="' + e.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this)"><a class="name">' + e.teacher[o].name + ' </a><a class="surplus">（剩余' + e.teacher[o].hasHour + ' 本月' + e.teacher[o].monthHour + '）</a></li>';
                    }
                    $('#chooseTeacher').html(teacher);
                }
            }
        });
    }
    if (str == 'class_classroom') {
        var tea = $('#chooseTeacher li.purple-font a:first').html();
        var teaId = $('#chooseTeacher li.purple-font').attr('data-setId');
        $('#teacher-result').val(tea);
        $('#teacher-result').attr('data-setId', teaId);
        $.ajax({
            url: '/cn/api/vip-class-data',
            type: 'post',
            dataType: 'json',
            data: {
                type: 2,////type 1-老师 2-教室
                classType: classType,
                begin: begin,
                end: end,
                date: date,
            },
            success: function (e) {
                if (e.code == 1) {
                    var room = '';
                    for (var o = 0; o < e.classroom.length; o++) {
                        room += '<option value="' + e.classroom[o].id + '">' + e.classroom[o].classroom + '</option>';
                    }
                    $('#classroom-result').html(room);
                }
            }
        });
    }

    var object = '#' + str;
    $(object).css('display', '');
}

//vip排课 2.0  记录排课数据
function vipSetCourse() {
    var editId = $('#editId').val();
    var area = $('#area').val();
    var student = $('#student').attr('data-stuId');
    var classType = $('#classType').val();
    var allTime = $('#allTime').val();
    var subject = $('#subject').val();
    var date = $('#jwpk_chooseDate_input').val();
    var begin = $('#start-time').val();
    var end = $('#end-time').val();
    var classTime = $('#class_classTime').html();
    var teacher = $('#teacher-result').attr('data-setId');
    var classroom = $('#classroom-result').val();
    if (area < 1) {
        alert('请选择地区！');
        return false;
    }
    if (student < 1) {
        alert('请选择学生');
        return false;
    }
    if (classType < 1) {
        alert('请选择班型');
        return false;
    }
    if (allTime <= 0) {
        alert('请输入正确的课时数！');
        return false;
    }
    if (subject < 1) {
        alert('请选择科目');
        return false;
    }
    if (!date) {
        alert('请选择日期');
        return false;
    }
    if (!begin || !end || parseFloat(classTime) <= 0 || !classTime) {
        alert('请填选正确的上课时间');
        return false;
    }
    if (parseFloat(teacher) < 1 || !teacher) {
        alert('请选择老师');
        return false;
    }
    if (classroom < 1) {
        alert('请选择上课教室');
        return false;
    }
    var useTime = $('#useTime').html();
    useTime = useTime ? parseFloat(useTime) : 0;
    var hadTime = parseFloat(useTime) + parseFloat(classTime);
    allTime = parseFloat(allTime);
    if (hadTime > allTime) {
        alert('课时已超出总课时，请调整！');
        return false;
    }
    $.ajax({
        url: '/cn/api/set-vip-course',
        type: 'post',
        dataType: 'json',
        data: {
            type: 1,//1-vip 2-班课
            editId: editId,
            area: area,
            student: student,
            classType: classType,
            totalTime: allTime,
            subject: subject,
            date: date,
            begin: begin,
            end: end,
            classTime: classTime,
            teacher: teacher,
            classroom: classroom,
        },
        success: function (e) {
            alert(e.message);
            if (e.code == 1) {
                classTypeClass();
                clearVipAdd(1);
                $('#chooseDate_input').val('');
            }
        }
    });
}

//新增内容清空 vip
function clearVipAdd(type = 0) {
    if (type != 1) {
        $('#subject').val('');
        $('#chooseDate_input').val('');
        $('#class_date').css('display', 'none');
    } else {
        $('#class_date').css('display', '');
    }
    $('#time-result').val('');
    $('#bk_time-result').val('');
    $('#bk_start-time').val('');
    $('#start-time').val('');
    $('#bk_end-time').val('');
    $('#end-time').val('');
    $('#class_classTime').html('');
    $('#bk_class_classTime').html('');
    $('#bk_teacher-result').attr('data-setId', '');
    $('#teacher-result').attr('data-setId', '');
    $('#bk_teacher-result').val('');
    $('#teacher-result').val('');
    $('#class_time').css('display', 'none');
    $('#bk_class_time').css('display', 'none');
    $('#class_classTime').css('display', 'none');
    $('#bk_class_classTime').css('display', 'none');
    $('#class_teacher').css('display', 'none');
    $('#bk_class_teacher').css('display', 'none');
    $('#class_classroom').css('display', 'none');
    $('#bk_class_classroom').css('display', 'none');
}

//新增内容显示
function vipAddDisplay() {
    $('#class_subject').css('display', '');
    $('#class_date').css('display', '');
    $('#class_time').css('display', '');
    $('#class_classTime').css('display', '');
    $('#class_teacher').css('display', '');
    $('#class_classroom').css('display', '');
}

//vip课时改变  改版 2.0
function changeTime(_this) {
    var total = $(_this).val();
    var useTime = $('#useTime').html()
    useTime = useTime ? parseInt(useTime) : 0;
    if (total < useTime) {
        alert('总课时必须大于已排课时');
        classTypeClass();
    }
}

//vip排课  课程删除 改版2.0
function classDelete(id, type = 1) {
    if (confirm('确定删除该条信息吗？')) {
        $.ajax({
            url: '/cn/api/class-delete',
            type: 'post',
            dataType: 'json',
            data: {
                id: id,
            },
            success: function (e) {
                alert(e.message);
                if (e.code == 1) {
                    if (type == 1) {//vip
                        classTypeClass();
                    } else {//班课
                        classCourse();
                    }
                }
            }
        });
    }
}

//vip排课 编辑返回 改版 2.0
function returnClass(id, type = 1) {
    if (type == 1) {
        clearVipAdd(1);
    } else {
        clearClassAdd();
        $('#addClassCourse').css('display', '');
        $('#editClassCourse').css('display', 'none');
    }
    $('#chooseDate_input').val('');
    var editStr = '#editId' + id;
    var returnStr = '#return' + id;
    $('.alledit').css('display', '');
    $('.allreturn').css('display', 'none');
    $(editStr).css('display', '');
    $(returnStr).css('display', 'none');
    $('#editId').val(0).attr('data-hour', 0);
}

//vip排课 课程编辑 改版 2.0
function classEdit(id) {
    $.ajax({
        url: '/cn/api/class-edit',
        type: 'post',
        dataType: 'json',
        data: {
            type: 1,//type 1-vip 2-班课
            id: id,
        },
        success: function (e) {
            if (e.code == 1) {
                $('#editId').val(id);
                vipAddDisplay();
                var sub = '';
                var data = e.data;
                for (var i = 0; i < data.subjects.length; i++) {
                    sub += '<option value="' + data.subjects[i].id + '"  ';
                    if (data.course.subject == data.subjects[i].id) {
                        sub += 'selected';
                    }
                    sub += '>' + data.subjects[i].name + '</option>';
                }
                $('#subject').html(sub);
                $('#chooseDate_input').val(data.course.date);
                $('#jwpk_chooseDate_input').val(data.course.date);
                $('#time-result').val(data.course.time);
                $('#start-time').val(data.course.begin);
                $('#end-time').val(data.course.end);
                $('#class_classTime').html(data.course.classTime);
                $('#teacher-result').val(data.course.teacherName);
                $('#teacher-result').attr('data-setId', data.course.teacher);
                var teacher = '';
                for (var o = 0; o < data.teacher.length; o++) {
                    teacher += '<li class="float-left teacher-option ';
                    if (data.teacher[o].tired == 1) {
                        teacher += 'class="tired"';
                    }
                    if (data.teacher[o].teacherId == data.course.teacher) {
                        teacher += ' selected';
                    }
                    teacher += '" data-setId="' + data.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this)"><a class="name">' + data.teacher[o].name + ' </a><a class="surplus">（剩余' + data.teacher[o].hasHour + ' 本月' + data.teacher[o].monthHour + '）</a></li>';
                }
                $('#chooseTeacher').html(teacher);
                var room = '';
                for (var w = 0; w < data.classroom.length; w++) {
                    room += '<option value="' + data.classroom[w].id + '"  ';
                    if (data.course.classroom == data.classroom[w].id) {
                        room += ' selected';
                    }
                    room += '>' + data.classroom[w].classroom + '</option>';
                }
                $('#classroom-result').html(room);
            } else {
                alert('操作失败，请重试');
            }
            var editStr = '#editId' + id;
            var returnStr = '#return' + id;
            $('.alledit').css('display', '');
            $('.allreturn').css('display', 'none');
            $(editStr).css('display', 'none');
            $(returnStr).css('display', '');
        }
    });
}

//班课 改版2.0  课程获取
function classCourse(date = '', teacher = 0) {
    $('.classCourse').remove();
    $('#bk_class_date').css('display', 'none');
    $('#bk_class_time1').css('display', 'none');
    $('#bk_class_time2').css('display', 'none');
    $('#bk_class_classTime').css('display', 'none');
    $('#bk_class_teacher').css('display', 'none');
    $('#bk_teacher-result').val('').attr('data-setId', '0');
    $('#bk_class_classroom').css('display', 'none');
    var area = $('#bk_area').val();
    var classType = $('#bk_classType').val();
    var begin = $('#rang-start').val();
    var end = $('#rang-end').val();
    clearClassEdit();
    var levelUser = $('#levelUser').val();
    var dateStr = "";
    var timeStr = "";

    if (area > 0 && classType > 0 && begin && end) {
        $.ajax({
            url: '/cn/api/class',
            type: 'post',
            dataType: 'json',
            data: {
                area: area,
                classType: classType,
                begin: begin,
                end: end,
                date: date,
                teacher: teacher,
            },
            success: function (e) {
                $('#editVipCourse').css('display', 'none');
                if (e.code == 1) {
                    $('#bk_useTime').html(e.data.useTime);
                    var cou = '';
                    for (var i = 0; i < e.data.course.length; i++) {
                        cou += '<tr class="classCourse">';
                        cou += '<th>' + e.data.course[i].subject + '</th>';
                        cou += '<th>' + e.data.course[i].dateStr + '（' + e.data.course[i].week + '）</th>';
                        cou += '<th>' + e.data.course[i].time + '</th>';
                        cou += '<th>' + e.data.course[i].classTime + '</th>';
                        cou += '<th>' + e.data.course[i].teacher + '</th>';
                        cou += '<th>' + e.data.course[i].classroom + '</th>';
                        cou += '<th><p class="d-flex flex-wrap  justify-content-around mb-0">';
                        if (levelUser == 6) {
                            cou += '<span class="purple-font alledit" id="editId' + e.data.course[i].id + '" onclick="classCourseEdit(' + e.data.course[i].id + ')">编辑</span>';
                            cou += '<span class="purple-font allreturn" id="return' + e.data.course[i].id + '"  onclick="returnClass(' + e.data.course[i].id + ',2)" style="display: none;">取消</span>';
                            cou += '<span class="purple-font" onclick="classDelete(' + e.data.course[i].id + ',2)">删除</span>';
                        }
                        cou += '</p></th></tr>';
                    }
                    $('.classCourse').remove();
                    $('#classCourses').append(cou);
                    //存入班课下的科目信息
                    var sub = '<option value="0">请选择</option>';
                    for (var t = 0; t < e.data.subject.length; t++) {
                        sub += '<option value="' + e.data.subject[t].id + '">' + e.data.subject[t].name + '</option>';
                    }
                    $('#bk_subject').html(sub);
                    $('#setTime').val(e.data.setTime);
                    $('#bk_studentNum').val(e.data.studentNum);
                    $('#bk_totalTime').val(e.data.totalTime);
                    $('#bk_useTime').val(e.data.useTime);
                    $('#bk_useTime2').html(e.data.useTime);
                    dateStr += "<select class='dateSelect' onchange='changeDate()'><option value='' style='display: none'></option>";
                    for (i = 0; i < e.data.dataSelect.length; i++) {
                        dateStr += "<option  value='" + e.data.dataSelect[i].date + "' >" + e.data.dataSelect[i].date + "</option>"
                    }
                    dateStr += "</select>";
                    $('#bk_class_date').html(dateStr);
                    if (e.data.setTime == 1) {
                        for (j = 0; j < e.data.timeSelect.length; j++) {
                            timeStr += "<p><input type='checkbox' class='timeSelect' name='timeSelect' value='" + e.data.timeSelect[j] + "' onclick='changeDate()'><span>" + e.data.timeSelect[j] + "</span></p>"
                        }
                        $('#bk_class_time1').html(timeStr);
                    }
                }
            }
        });
    }
}

var hour = 0;

/*判断日期和时间段是否有选择*/
function changeDate() {
    var date = $('.dateSelect').val();
    console.log('date', date);
    var obj = document.getElementsByName('timeSelect');
    var check_val = [];
    for (k in obj) {
        if (obj[k].checked) {
            check_val.push(obj[k].value);
        }
    }
    console.log('check_val', check_val);
    if (date !== '' && check_val.length > 0) {
        classData(1);
    } else {
        hour = 0;
        console.log('hour', hour);
        $('#bk_class_classTime').html(hour);

    }

}

//科目选择 改版 2.0
function sureClass(str) {
    var setTime = $('#setTime').val();//1-后台已设置时间 0-未设置
    if (str == 'bk_class_date') {
        if (setTime == 1) {
            $('#bk_class_time1').css('display', '');
            $('#bk_class_time2').css('display', 'none');
            classData(1);
        } else {
            $('#bk_class_time1').css('display', 'none');
            $('#bk_class_time2').css('display', '');
        }
    }
    if (str == 'bk_class_teacher') {
        var begin = $('#bk_start-time').val();
        var end = $('#bk_end-time').val();
        if (!begin || !end) {
            alert('请选全时间');
            return false;
        }
        classData(1);
    }

    var obj = '#' + str;
    $(obj).css('display', '');
}

//班课排课 改版 2.0  排课编辑  数据更改
function editClassCourse() {
    var editId = $('#editId').val();
    var classType = $('#bk_classType').val();
    var date = $('#chooseDate_input').val();
    var begin = $('#start-time-edit').val();
    var end = $('#end-time-edit').val();
    if (!begin || !end) {
        alert('请选全时间');
        return false;
    }
    var hour = getHour(begin, end);
    $('#edit_classTime').html(hour);
    $.ajax({
        url: '/cn/api/class-course-edit-data',
        type: 'post',
        dataType: 'json',
        data: {
            classType: classType,
            date: date,
            begin: begin,
            end: end,
            setId: editId,
            classTime: hour,
        },
        success: function (e) {
            var teacher = '';
            for (var o = 0; o < e.teacher.length; o++) {
                teacher += '<li class="float-left teacher-option ';
                if (e.teacher[o].isTired == 1) {
                    teacher += ' tired';
                }
                teacher += '" data-setId="' + e.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this,2)"><a class="name">' + e.teacher[o].name + ' </a><a class="surplus">（剩余' + e.teacher[o].hasHour + ' 本月' + e.teacher[o].monthHour + '）</a></li>';
            }
            $('#chooseTeacher-edit').html(teacher);
            var room = '<option value="0">请选择</option>';
            for (var p = 0; p < e.classroom.length; p++) {
                room += '<option value="' + e.classroom[p].id + '"';
                if (e.classroom[p].id == e.roomId) {
                    room += 'selected ';
                }
                room += '>' + e.classroom[p].classroom + '</option>';
            }
            $('#classroom-result-edit').html(room);
        }
    });
}

//数据获取 班课 改版 2.0
function classData(type = 1) {//1-获取老师 2-获取教室
    console.log('classData');
    var classType = $('#bk_classType').val();
    var rangBegin = $('#rang-start').val();
    var rangEnd = $('#rang-end').val();
    var setTime = $('#setTime').val();
    var timeBegin = $('#bk_start-time').val();
    var timeEnd = $('#bk_end-time').val();
    var subject = $('#bk_subject').val();
    hour = 0;
    var date;
    var times
    if (!classType || !rangBegin || !rangEnd || !subject) {
        alert('数据出错，重试！');
        return false;
    }
    if (setTime != 1) {
        hour = getHour(timeBegin, timeEnd);
    }
    if (setTime == 1) {
        date = $('.dateSelect').val();
        console.log('date', date);
        var obj = document.getElementsByName('timeSelect');
        var check_val = [];
        for (k in obj) {
            if (obj[k].checked) {
                check_val.push(obj[k].value);
            }
        }
        console.log('check_val', check_val);
        times = check_val.join(';');
        console.log('times', times);
        if (date !== '' && check_val.length > 0) {
            for (k in obj) {
                if (obj[k].checked) {
                    var time = obj[k].value;
                    console.log('time', time);
                    var timeArr = time.split('-');
                    console.log('times', times);
                    var start = timeArr[0];
                    var end = timeArr[1];
                    console.log('hour0', hour);

                    var hour1 = getHour(start, end);
                    hour += hour1;
                    console.log('hour1', hour1);
                    console.log('hour', hour);
                }
            }
        }
    }
    if (type == 1) {
        //老师数据获取嵌套
        $.ajax({
            url: '/cn/api/class-data',
            type: 'post',
            dataType: 'json',
            data: {
                type: 1,//type 1 老师 2-教室
                classType: classType,
                rangBegin: rangBegin,
                rangEnd: rangEnd,
                setTime: setTime,
                timeBegin: timeBegin,
                timeEnd: timeEnd,
                hour: hour,
                subject: subject,
                date: date,
                times: times,
            },
            success: function (e) {
                var teacher = '';
                for (var o = 0; o < e.teacher.length; o++) {
                    teacher += '<li class="float-left teacher-option ';
                    if (e.teacher[o].isTired == 1) {
                        teacher += ' tired';
                    }
                    teacher += '" data-setId="' + e.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this)"><a class="name">' + e.teacher[o].name + ' </a><a class="surplus">（剩余' + e.teacher[o].hasHour + ' 本月' + e.teacher[o].monthHour + '）</a></li>';
                }
                $('#bk_chooseTeacher').html(teacher);
                $('#bk_class_teacher').css('display', '');
                $('#bk_class_classTime').html(e.hours);
                $('#bk_class_classTime').css('display', '');
            }
        });
    } else {
        var tea = $('#bk_chooseTeacher li.purple-font a:first').html();
        var teaId = $('#bk_chooseTeacher li.purple-font').attr('data-setId');
        $('#bk_teacher-result').val(tea);
        $('#bk_teacher-result').attr('data-setId', teaId);
        $.ajax({
            url: '/cn/api/class-data',
            type: 'post',
            dataType: 'json',
            data: {
                type: 2,//type 1 老师 2-教室
                classType: classType,
                rangBegin: rangBegin,
                rangEnd: rangEnd,
                setTime: setTime,
                timeBegin: timeBegin,
                timeEnd: timeEnd,
                date: date,
                times: times,
            },
            success: function (e) {
                var room = '<select id="bk_classroom-result"><option value="0">请选择</option>';
                for (var p = 0; p < e.length; p++) {
                    room += '<option value="' + e[p].id + '">' + e[p].classroom + '</option>';
                }
                room += '</select>';
                $('#bk_class_classroom').html(room).css('display', '');
            }
        });
    }
}

//班课 修改 改版2.0
function classCourseEdit(id) {
    $.ajax({
        url: '/cn/api/class-edit',
        type: 'post',
        dataType: 'json',
        data: {
            type: 2,//type 1-vip 2-班课
            id: id,
        },
        success: function (e) {
            if (e.code == 1) {
                $('#addClassCourse').css('display', 'none');
                var sub = '';
                var data = e.data;
                $('#editId').val(id).attr('data-hour', data.course.classTime);
                for (var i = 0; i < data.subjects.length; i++) {
                    sub += '<option value="' + data.subjects[i].id + '"  ';
                    if (data.course.subject == data.subjects[i].id) {
                        sub += 'selected';
                    }
                    sub += '>' + data.subjects[i].name + '</option>';
                }
                $('#subject-edit').html(sub);

                $('#chooseDate_input').val(data.course.date);
                $('#time-result-edit').val(data.course.time);
                $('#start-time-edit').val(data.course.begin);
                $('#end-time-edit').val(data.course.end);
                $('#edit_classTime').html(data.course.classTime);
                $('#teacher-result-edit').val(data.course.teacherName);
                $('#teacher-result-edit').attr('data-setId', data.course.teacher);
                var teacher = '';
                for (var o = 0; o < data.teacher.length; o++) {
                    teacher += '<li class="float-left teacher-option ';
                    if (data.teacher[o].tired == 1) {
                        teacher += 'class="tired"';
                    }
                    if (data.teacher[o].teacherId == data.course.teacher) {
                        teacher += ' selected';
                    }
                    teacher += '" data-setId="' + data.teacher[o].teacherId + '" onclick="classDo(this);chooseTeacher(this,2)"><a class="name">' + data.teacher[o].name + ' </a><a class="surplus">（剩余' + data.teacher[o].hasHour + ' 本月' + data.teacher[o].monthHour + '）</a></li>';
                }
                $('#chooseTeacher-edit').html(teacher);
                var room = '';
                for (var w = 0; w < data.classroom.length; w++) {
                    room += '<option value="' + data.classroom[w].id + '"  ';
                    if (data.course.classroom == data.classroom[w].id) {
                        room += ' selected';
                    }
                    room += '>' + data.classroom[w].classroom + '</option>';
                }
                $('#classroom-result-edit').html(room);
                $('#editClassCourse').css('display', '');
            } else {
                alert('操作失败，请重试！');
            }
            var editStr = '#editId' + id;
            var returnStr = '#return' + id;
            $('.alledit').css('display', '');
            $('.allreturn').css('display', 'none');
            $(editStr).css('display', 'none');
            $(returnStr).css('display', '');
        }

    });
}

//班课排课 2.0  记录排课数据
function classSetCourseAdd() {
    var area = $('#bk_area').val();
    var classType = $('#bk_classType').val();
    var rangBegin = $('#rang-start').val();
    var rangEnd = $('#rang-end').val();
    var studentNum = $('#bk_studentNum').val();
    var totalTime = $('#bk_totalTime').val();
    var subject = $('#bk_subject').val();

    var setTime = $('#setTime').val();//1-后台已设置时间 0-未设置
    console.log('setTime', setTime);
    var timeBegin = '';
    var timeEnd = '';
    var times;
    var date = $('#bk_class_date').children('.dateSelect').val();

    if (setTime == 1) {
        var obj = document.getElementsByName('timeSelect');
        var check_val = [];
        for (k in obj) {
            if (obj[k].checked) {
                check_val.push(obj[k].value);
            }
        }
        times = check_val.join(';');
        console.log('times', times);
    } else {
        timeBegin = $('#bk_start-time').val();
        timeEnd = $('#bk_end-time').val();
        times = timeBegin + '-' + timeEnd;
    }
    var classTime = $('#bk_class_classTime').html();
    var teacher = $('#bk_teacher-result').attr('data-setId');
    var classroom = $('#bk_classroom-result').val();
    if (area < 1) {
        alert('请选择地区！');
        return false;
    }
    if (classType < 1) {
        alert('请选择班型');
        return false;
    }
    if (!rangBegin) {
        alert('请选择开始日期');
        return false;
    }
    if (!rangEnd) {
        alert('请选择结束日期');
        return false;
    }
    if (!studentNum || parseInt(studentNum) < 1) {
        alert('请填入正确的学生数');
        return false;
    }
    if (totalTime <= 0) {
        alert('请输入正确的课时数！');
        return false;
    }
    if (subject < 1) {
        alert('请选择科目');
        return false;
    }
    if (!date) {
        alert('请选择日期');
        return false;
    }
    if (setTime == 1) {

        if (check_val.length < 1) {
            alert('请选择时间段时间');
            return false;

        }
    }
    if (setTime != 1) {
        if (!timeBegin || !timeEnd || parseFloat(classTime) <= 0 || !classTime) {
            alert('请填选正确的上课时间');
            return false;
        }
    }
    if (!classTime) {
        alert('课时数据有误，请重试');
        return false;
    }
    if (parseFloat(teacher) < 1 || !teacher) {
        alert('请选择老师');
        return false;
    }
    if (setTime == 1) {

        if (classroom < 1) {
            alert('请选择上课教室');
            return false;
        }
    }
    var useTime = $('#bk_useTime').val();
    useTime = useTime ? parseFloat(useTime) : 0;
    var hadTime = parseFloat(useTime) + parseFloat(classTime);
    totalTime = parseFloat(totalTime);
    if (hadTime > totalTime) {
        alert('课时已超出总课时，请调整！');
        return false;
    }

    $.ajax({
        url: '/cn/api/set-class-course',
        type: 'post',
        dataType: 'json',
        data: {
            editId: 0,
            area: area,
            classType: classType,
            rangBegin: rangBegin,
            rangEnd: rangEnd,
            studentNum: studentNum,
            totalTime: totalTime,
            subject: subject,
            setTime: setTime,
            begin: timeBegin,
            end: timeEnd,
            classTime: classTime,
            teacher: teacher,
            classroom: classroom,
            date: date,
            times: times,
        },
        success: function (e) {
            alert(e.message);
            if (e.code == 1) {
                classCourse();
                clearClassAdd(1);
            }
        }
    });
}

//班课新增数据清除
function clearClassAdd() {
    $('#bk_class_date').css('display', 'none');
    $('#bk_class_time1').css('display', 'none');
    $('#bk_class_time2').css('display', 'none');
    $('#bk_time-result').val('');
    $('#bk_class_classTime').html('').css('display', 'none');
    $('#bk_teacher-result').val('').attr('data-setId', '');
    $('#bk_class_classroom').html('').css('display', 'none');
    $('#bk_class_teacher').css('display', 'none');
}

//班课修改数据清除
function clearClassEdit() {
    $('#editId').val('').css('data-hour', 0);
    $('#subject-edit').html('');
    $('#chooseDate_input').val('');
    $('#time-result-edit').val('');
    $('#teacher-result-edit').val('');
    $('#classroom-result-edit').html('');
    $('#editClassCourse').css('display', 'none');
    $('#addClassCourse').css('display', '');
}

//班课 课程修改 2.0
function classSetCourseEdit() {
    var editId = $('#editId').val();
    var subject = $('#subject-edit').val();
    var date = $('#chooseDate_input').val();
    var begin = $('#start-time-edit').val();
    var end = $('#end-time-edit').val();
    var classTime = $('#edit_classTime').html();
    var teacher = $('#teacher-result-edit').attr('data-setId');
    var classroom = $('#classroom-result-edit').val();
    var studentNum = $('#bk_studentNum').val();
    var totalTime = $('#bk_totalTime').val();
    var useTime = $('#bk_useTime').val();
    var hadHour = $('#editId').attr('data-hour');
    if (!editId) {
        alert('数据有误，请重试！');
        return false;
    }
    if (!studentNum || parseInt(studentNum) < 1) {
        alert('请填入正确的学生数');
        return false;
    }
    if (totalTime <= 0) {
        alert('请输入正确的课时数！');
        return false;
    }
    var hadTime = parseFloat(useTime) + parseFloat(classTime) - parseFloat(hadHour);
    if (parseFloat(totalTime) < hadTime) {
        alert('课时已超出总课时，请调整!');
        return false;
    }
    if (subject < 1) {
        alert('请选择科目');
        return false;
    }
    if (!date) {
        alert('请选择日期');
        return false;
    }
    if (!begin || !end || parseFloat(classTime) <= 0 || !classTime) {
        alert('请填选正确的上课时间');
        return false;
    }
    if (!classTime) {
        alert('课时数据有误，请重试');
        return false;
    }
    if (parseFloat(teacher) < 1 || !teacher) {
        alert('请选择老师');
        return false;
    }
    if (classroom < 1) {
        alert('请选择上课教室');
        return false;
    }
    $.ajax({
        url: '/cn/api/set-class-course',
        type: 'post',
        dataType: 'json',
        data: {
            editId: editId,
            studentNum: studentNum,
            totalTime: totalTime,
            subject: subject,
            date: date,
            begin: begin,
            end: end,
            classTime: classTime,
            teacher: teacher,
            classroom: classroom,
        },
        success: function (e) {
            alert(e.message);
            if (e.code == 1) {
                classCourse();
                clearClassEdit();
            }
        }
    });
}

//班课课表  时间变化 改版2.0
function monthChange(area, teacher) {
    var month = $('count_month').val();
}

//老师课表筛选  改版 2.0
function getTeacherCourseNew(teacherId, area, year = 0, month = '01') {
    $.ajax({
        url: '/cn/api/teacher-area-course',
        type: 'post',
        dataType: 'json',
        data: {
            teacher: teacherId,
            area: area,
            year: year,
            month: month,
        },
        success: function (w) {
            var details = '';
            for (var t = 0; t < w.details.length; t++) {
                details += '<tr>';
                details += '<th rowspan="' + w.details[t].total + '"><p class="mb-0">' + w.details[t].dateStr + '</p><p class="mb-0">' + w.details[t].week + '</p></th>';
                details += '<th>' + w.details[t].time + '</th>';
                details += '<th>' + w.details[t].classTime + '</th>';
                details += '<th>' + w.details[t].setCourse + '</th>';
                details += '<th>' + w.details[t].object + '</th>';
                details += '<th>' + w.details[t].areaStr + '</th>';
                details += '<th>' + w.details[t].classroom + '</th>';
                details += '<th class="font-7">' + w.details[t].username + '</th>';
                if (year > 0) {
                    if (w.details[t].classStatus == -1) {
                        details += '<th>已上课</th>';
                    }
                    if (w.details[t].classStatus == 2) {
                        details += '<th>未开始</th>';
                    }
                    if (w.details[t].classStatus == 1) {
                        details += '<th><a href="' + w.details[t].classroomUrl + '">进入教室</a></th>';
                    }
                }
                details += '</tr>';
                if (w.details[t].total > 1) {
                    for (var r = 0; r < w.details[t].child.length; r++) {
                        details += '<tr>';
                        details += '<th>' + w.details[t].child[r].time + '</th>';
                        details += '<th>' + w.details[t].child[r].classTime + '</th>';
                        details += '<th>' + w.details[t].child[r].setCourse + '</th>';
                        details += '<th>' + w.details[t].child[r].object + '</th>';
                        details += '<th>' + w.details[t].child[r].areaStr + '</th>';
                        details += '<th>' + w.details[t].child[r].classroom + '</th>';
                        details += '<th class="font-7">' + w.details[t].child[r].username + '</th>';
                        if (year > 0) {
                            if (w.details[t].child[r].classStatus == -1) {
                                details += '<th>已上课</th>';
                            }
                            if (w.details[t].child[r].classStatus == 2) {
                                details += '<th>未开始</th>';
                            }
                            if (w.details[t].child[r].classStatus == 1) {
                                details += '<th><a href="' + w.details[t].classroomUrl + '">进入教室</a></th>';
                            }
                        }
                        details += '</tr>';
                    }
                }
            }
            $('#teacherCourse').html(details);
        }
    });
}

//班级课表 查看课表 cy seeId 1-消息查看 0-其他
function classCourseLook(setId, seeId = 0, date = '', teacher = 0) {
    $.ajax({
        url: '/cn/api/class-course-detail',
        type: 'post',
        dataType: 'json',
        data: {
            setId: setId,
            seeId: seeId,
            date: date,
            teacher: teacher
        },
        success: function (e) {
            $('#bk_area').val(e.area);
            $('#bk_classType').val(e.classType);
            $('#rang-start').val(e.rangStart);
            $('#rang-end').val(e.rangEnd);
            classCourse(date, teacher);
        }
    });
}

//vip课表 查看课表 cy seeId 1-消息查看 0-其他
function vipCourseLook(setId, seeId = 0, myCourse = 1) {
    $.ajax({
        url: '/cn/api/vip-course-detail',
        type: 'post',
        dataType: 'json',
        data: {
            setId: setId,
            seeId: seeId,
        },
        success: function (e) {
            $('#areaStr').html(e.areaStr);
            $('#area').val(e.area);
            $('#classType').val(e.classType);
            $('#student').val(e.studentName).attr('data-stuId', e.student);
            classTypeClass(myCourse);
        }
    });
}

/**
 * 教务 vip课程审核
 * cy
 * 改版 2.0
 */
function jwCheck(setId, type) {
    var obj = '#jwCheck' + setId;
    var status = $(obj).val();
    var str = '';
    if (status == 0) {
        str = '待审核';
    }
    if (status == -1) {
        str = '拒绝';
    }
    if (status == 1) {
        str = '通过';
    }
    var optStr = '';
    if (type == 0) {
        optStr = '#jwCheck' + setId + ' option:eq(0)';
    }
    if (type == 1) {
        optStr = '#jwCheck' + setId + ' option:eq(1)';
    }
    if (type == -1) {
        optStr = '#jwCheck' + setId + ' option:eq(2)';
    }
    if (confirm('确定把该排课状态改为' + str + '吗？')) {
        $.ajax({
            url: '/cn/api/check-course',
            type: 'post',
            dataType: 'json',
            data: {
                setId: setId,
                status: status,
            },
            success: function (e) {
                alert(e.message);
                if (e.code != 1) {
                    $(optStr).attr('selected', true);
                    $(obj).val(type);
                }
            }
        });
    } else {
        $(optStr).attr('selected', true);
        $(obj).val(type);
    }
}

$(document).scroll(function () {
    var srollHeight = $(window).scrollTop();
    var srollWidth = $(window).scrollLeft();
    var thead_left = Number(215 - srollWidth);
    var height = $(".statistics-table").offset();//这是离文档顶部
    height = height.top;
    var distance = Number(height) - Number(srollHeight);
    var width_first_th = Number($('.statistics-excel thead tr th:first-child').width() + 21);
    var width_second_th = Number($('.statistics-excel thead tr th:nth-child(2)').width() + 21);
    var width_last_th = Number($('.statistics-excel thead tr th:nth-child(8)').width() + 21);

    var widths = [];
    for (i = 1; i < $('.class-excel thead tr').children('td').length + 1; i++) {
        var width = Number($('.class-excel thead tr td:nth-child(' + i + ')').width() + 2);
        widths.push(width);
    }


    var statistics_width_thead = $('.statistics-excel').width();
    var class_width_thead = $('.class-excel').width();
    if (distance == 45 || distance < 45) {
        $('.main-right .class-excel  thead').css({
            'position': 'fixed',
            'top': '0',
            'width': class_width_thead,
            'left': thead_left,
            'z-index': '1'
        });
        for (i = 1; i < $('.class-excel thead tr').children('td').length + 1; i++) {
            $('.class-excel thead tr td:nth-child(' + i + ')').css({'width': widths[i - 1]});
        }
        $('.main-right .statistics-excel  thead').css({
            'position': 'fixed',
            'top': '0',
            'width': statistics_width_thead,
            'left': thead_left,
            'z-index': '1'
        });


        $('.statistics-excel thead tr th:first-child').css({'width': width_first_th});
        $('.statistics-excel thead tr th:nth-child(2)').css({'width': width_second_th});
        $('.statistics-excel thead tr th:nth-child(3)').css({'width': width_second_th});
        $('.statistics-excel thead tr th:nth-child(4)').css({'width': width_second_th});
        $('.statistics-excel thead tr th:nth-child(5)').css({'width': width_second_th});
        $('.statistics-excel thead tr th:nth-child(6)').css({'width': width_second_th});
        $('.statistics-excel thead tr th:nth-child(7)').css({'width': width_second_th});
        $('.statistics-excel thead tr th:nth-child(8)').css({'width': width_last_th});
    } else {
        $('.statistics-table table thead').css({'position': 'relative'})
    }
});
