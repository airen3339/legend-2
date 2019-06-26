$('.c-datepicker-data-input').click(function () {
    $('.c-datepicker-picker').addClass('have_hour');
    // $('.c-datepicker-picker .c-datepicker-picker__footer').children('.c-datepicker-button:first').remove();
    $('.c-datepicker-picker .c-datepicker-picker__footer').children('.confirm ').css({'left': '119px'});
    var id = $(this).attr('id');
    if(id == 'rang-start' || id == 'rang-end'){
        $('.c-datepicker-picker').eq(0).addClass('time-rang');
        $('.c-datepicker-picker').eq(1).addClass('time-rang');
    }
    if(id == 'chooseDate_input'){
        $('.c-datepicker-picker').eq(2).addClass('time-edit');
    }
    if(id == 'count_month'){
        $('.c-datepicker-picker').eq(3).addClass('count_month');
    }
})


