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


    // *************
    // * FUNCTIONS *
    // *************

    // scrolls down to the top of given page at given animation speed
    function scrollToPageID(page_id, scroll_speed) {
        $('body,html').animate({ scrollTop: window.innerHeight * page_id}, scroll_speed);
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

    $("a.day-collapse-btn").click(function () {
        $(this).find(">:first-child").toggleClass("fa-plus");
        $(this).find(">:first-child").toggleClass("fa-minus");
        // TODO: First load content, then collapse
        // $(this).parent().parent().children(".trip-day-body-container").collapse("toggle");

        var action_container = $(this).parent().parent().find(".trip-day-body");
        if (action_container.html() == "") {
            $.ajax({
                url: "/trip/day/" + ($(this).attr("data-target").substring(4)) + "/actions",
                success: function (result) {
                    console.log(result);
                    action_container.html(result);
                }
            });
        }

    });

    $("a.trip-add-day").click(function () {
        // TODO: Add a new day
        $("div.trip-days-container").append("new day");
    });
});
