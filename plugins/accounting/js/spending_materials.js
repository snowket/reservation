$( window ).resize(function() {
    var sp_board_w=$("#sp_board").width();
    var sp_left_w=$("#sp_left").outerWidth(true);
    $("#sp_center").css('width',(sp_board_w-sp_left_w-103)+'px');
});
$(document).ready(function () {
    var sp_board_w=$("#sp_board").width();
    var sp_left_w=$("#sp_left").outerWidth(true);
    $("#sp_center").css('width',(sp_board_w-sp_left_w-103)+'px');

    $("#period_start").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() + (1000 * 60 * 60 * 24));
            $("#period_end").datepicker("option", "minDate", date);
        }
    });

    $("#period_end").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        //minDate: 'today',
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var date = $(this).datepicker('getDate');
            date.setTime(date.getTime() - (1000 * 60 * 60 * 24));
            $("#period_start").datepicker("option", "maxDate", date);
        }
    });

    $('.sptda, .sptda2').mouseover(function () {
        var msg= $(this).find('.content').html();

        $(this).notify(
            {
                title:'',
                text:msg
            },
            {
                autoHide: false,
                hideDuration: 0,
                showDuration: 0,
                autoHideDelay: 0,
                position:"top center",
                elementPosition: 'left',
                className:'info',
                style: 'metro'
            });
        $(this).find('.image').hide();
    });

    $('.sptda, .sptda2').mouseout(function () {
        $(this).parent().find(".notifyjs-hidable").trigger('notify-hide');
    });

    $('#excel_downloader').click(function () {
        $('#excel_download_form').submit();
    });

});