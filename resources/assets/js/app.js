$(document).ready(function () {

    // *************
    // * VARIABLES *
    // *************

    // time of animations in milliseconds
    var animation_time_very_fast = 200;
    var animation_time_fast      = 350;
    var animation_time_normal    = 500;
    var animation_time_slow      = 750;
    var animation_time_very_slow = 1000;

    // height of navigation bar in pixels
    var navigation_height         = 60;

    // when clicking an "ajax" link, what parameter contains the url
    var ajax_url_parameter_name = 'data-ajax-url';


    // *************
    // * FUNCTIONS *
    // *************

    // scrolls down to the top of given page at given animation speed
    function scrollToPageID(page_id, scroll_speed) {
        $('body,html').animate({ scrollTop: window.innerHeight * page_id}, scroll_speed);
    }

    // TODO: Finish up with correct html
    // displays alert immediately
    function addAlert(type, message) {
        if (type == 'error') type = 'danger';
        $('.alert-container').append("<div class='alert alert-" + type + " alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + message + "!</div>");
    }


    // ******************
    // * EVENT HANDLING *
    // ******************

    // when the window is scrolled for more than 30px
    // display "back-to-top" button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 30) {
            $("div.back-to-top-button-container").fadeIn(animation_time_normal);
        } else {
            $("div.back-to-top-button-container").fadeOut(animation_time_very_fast);
        }
    });

    $("button.back-to-top").click(function () {
        scrollToPageID($(this).attr("data-scroll-target"), animation_time_fast);
    });

    $("a.scroll-down").click(function () {
        scrollToPageID($(this).attr("data-scroll-target"), animation_time_slow);
    });

    $(".trip-day-head").click(function (e) {
        // console.log(event.target);

        if ($(event.target).hasClass('non-collapse')) {
            e.stopPropagation();
            return;
        }

        // TODO: First load content, then collapse
        var action_container = $(this).parent().parent().find(".trip-day-body");
        if (action_container.html() == "") {
            e.stopPropagation();
            $.ajax({
                url: "/trip/day/" + ($(this).attr("data-target").substring(4)) + "/actions",
                success: function (result) {
                    // console.log(result);
                    action_container.html(result);
                    action_container.parent().collapse("toggle");
                }
            });
        }

    });

    $("a.day-edit-btn").click(function () {
        alert('Editing day');
    });

    $("a.day-delete-btn").click(function () {
        $.ajax({
            url: $(this).attr(ajax_url_parameter_name),
            success: function (result) {
                console.log(result);
            }
        });
    });

    $("a.trip-add-day").click(function () {
        // TODO: Add a new day
        $("div.trip-days-container").append("new day");
    });

    $("button.action-add-btn").click(function () {
        $.ajax({
            url: "/action-types",
            success: function (result) {
                console.log(result);
                $("select.action-type-select").html(result);
            }
        });
    });

    if ($('.trip-navigation').length) {
        $(window).scroll(function () {
            $('.trip-navigation').toggleClass('navbar-fixed-top', $(this).scrollTop() > navigation_height);
        });
    }

    $('.remove-trip-user-link').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr(ajax_url_parameter_name),
            dataType: 'json',
            success: function (result) {
                // TODO: Remove user-trip table row from table
                addAlert(result.type, result.message);
            },
            error: function (result) {
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    });

    $('.add-day-link').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr(ajax_url_parameter_name),
            dataType: 'json',
            success: function (result) {
                console.log(result);
                $('.trip-days-container').append(result.html);
                console.log(result.html);
                addAlert(result.type, result.message);
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    })
});
