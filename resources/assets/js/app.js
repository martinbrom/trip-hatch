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

    // displays alert immediately
    function addAlert(type, message) {
        if (type == 'error') type = 'danger';
        $('.alert-container').append("<div class='alert alert-" + type + " alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + message + "!</div>");
    }

    function addValidationErrorMessages(validation_errors) {
        console.log('validation failed');

        for (var field in validation_errors) {
            if (validation_errors.hasOwnProperty(field)) {
                var errors = validation_errors[field];
                var input = $("#" + field);
                input.parent().addClass('has-error');
                for (var error in errors) {
                    if (errors.hasOwnProperty(error)) {
                        input.before('<p class=validation-error-message>' + errors[error] + '</p>');
                    }
                }
            }
        }
    }

    function clearValidationErrorMessages() {
        $('.form-group p.validation-error-message').remove();
        $('.form-group.has-error').removeClass('has-error');
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

    // $(document).on('click', '.non-collapse', function (e) {
    //     e.stopPropagation();
    // });

    $(document).on('click', '.trip-day-head', function (e) {
        console.log(this);
        // e.stopPropagation();
        // console.log($(e.target));

        if ($(e.target).hasClass('non-collapse')) {
            e.stopPropagation();
            return;
        }

        var action_container = $(this).parent().parent().find(".trip-day-body");
        if (action_container.html() == "") {
            e.stopPropagation();
            $.ajax({
                url: $(this).parent().parent().find(".trip-day-head").attr(ajax_url_parameter_name),
                success: function (result) {
                    console.log(result);
                    action_container.html(result.html);
                    action_container.parent().collapse("toggle");
                }
            });
        } else {
            action_container.parent().collapse("toggle");
        }
    });

    $(document).on('click', 'a.day-edit-modal-btn', function (e) {
        e.preventDefault();
        var url = $(this).attr(ajax_url_parameter_name);
        $('.day-edit-btn').attr(ajax_url_parameter_name, url);
        $('form.day-edit-form').attr(ajax_url_parameter_name, url);

        $.ajax({
            url: url,
            success: function (result) {
                console.log(result);
                console.log(result.day);
                console.log(result.trip);
                $('#day_title').val(result.day.title);
                $('#day-edit-modal').modal('show');
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    });

    $(".day-edit-btn").click(function (e) {
        e.preventDefault();
        var url  = $(this).attr(ajax_url_parameter_name);
        $.ajax({
            url: url,
            method: 'post',
            data: $('form.day-edit-form').serialize(),
            success: function (result) {
                console.log(result);

                clearValidationErrorMessages();
                if (result.validation_errors != null) {
                    addValidationErrorMessages(result.validation_errors);
                } else {
                    $("#day-container-" + result.day_id).remove();
                    $('.trip-days-container').append(result.html);

                    // TODO: More elegant solution?
                    $("div.trip-day-container").sort(function (prev, next) {
                        return parseInt(prev.dataset.order) - parseInt(next.dataset.order);
                    }).appendTo(".trip-days-container");
                    $('#day-edit-modal').modal('hide');
                }
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                $('#day-edit-modal').modal('hide');
                addAlert(r['type'], r['message']);
            }
        });
    });

    $(document).on('click', 'a.day-delete-btn', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr(ajax_url_parameter_name),
            success: function (result) {
                console.log(result);
                $("#day-container-" + result.day_id).remove();
                addAlert(result.type, result.message);
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    });

    $(document).on('click', '.action-edit-modal-btn', function (e) {
        e.preventDefault();
        var url = $(this).attr(ajax_url_parameter_name);
        $('.action-edit-btn').attr(ajax_url_parameter_name, url);
        $('form.action-edit-form').attr(ajax_url_parameter_name, url);
        $('.action-delete-btn').attr(ajax_url_parameter_name, $(this).attr(ajax_url_parameter_name + "2"));

        $.ajax({
            url: url,
            success: function (result) {
                console.log(result);
                $('#action_edit_title').val(result.action.title);
                $('#action_edit_content').val(result.action.content);
                $('#action_edit_type option[value=' + result.action.action_type_id + ']').attr('selected', 'selected');
                $('#action-edit-modal').modal('show');
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    });

    $('.action-delete-btn').click(function (e) {
        e.preventDefault();
        var url = $(this).attr(ajax_url_parameter_name);
        $.ajax({
            url: url,
            success: function (result) {
                console.log(result);
                $("#action-container-" + result.action_id).remove();
                $('#action-edit-modal').modal('hide');
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                $('#action-edit-modal').modal('hide');
                addAlert(r['type'], r['message']);
            }
        });
    });

    $('.action-edit-btn').click(function (e) {
        e.preventDefault();
        var url = $(this).attr(ajax_url_parameter_name);

        $.ajax({
            url: url,
            method: 'post',
            data: $('form.action-edit-form').serialize(),
            success: function (result) {
                console.log(result);

                clearValidationErrorMessages();
                if (result.validation_errors != null) {
                    addValidationErrorMessages(result.validation_errors);
                } else {
                    var actions_container = $("#day" + result.day_id + " .actions-container");
                    $("#action-container-" + result.action_id).remove();
                    actions_container.append(result.html);
                    console.log(result.html);

                    // TODO: More elegant solution?
                    actions_container.children().sort(function (prev, next) {
                        return parseInt(prev.dataset.order) - parseInt(next.dataset.order);
                    }).appendTo(actions_container);
                    $('#action-edit-modal').modal('hide');
                }
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                $('#action-edit-modal').modal('hide');
                addAlert(r['type'], r['message']);
            }
        });
    });

    $(document).on('click', '.action-add-modal-btn', function () {
        var url = $(this).attr(ajax_url_parameter_name);
        $('.action-add-btn').attr(ajax_url_parameter_name, url);
        $('form.action-add-form').attr(ajax_url_parameter_name, url);

        $.ajax({
            url: url,
            success: function (result) {
                console.log(result);
                $('#action_title').val('');
                $('#action_content').val('');
                $('#action_type').val('');  // TODO: Select action_type: OTHER
                $('#action-add-modal').modal('show');
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    });

    $('.action-add-btn').click(function (e) {
        e.preventDefault();
        var url  = $(this).attr(ajax_url_parameter_name);
        $.ajax({
            url: url,
            method: 'post',
            data: $('form.action-add-form').serialize(),
            success: function (result) {
                console.log(result);

                clearValidationErrorMessages();
                if (result.validation_errors != null) {
                    addValidationErrorMessages(result.validation_errors);
                } else {
                    $('#day' + result.day_id + ' .action-add-modal-btn').parent().before(result.html);
                    $('#action-add-modal').modal('hide');
                }
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                $('#action-add-modal').modal('hide');
                addAlert(r['type'], r['message']);
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
    });

    $('.trip-visibility-btn').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr(ajax_url_parameter_name),
            dataType: 'json',
            success: function (result) {
                console.log(result);
                addAlert(result.type, result.message);
                $('.trip-visibility-btn').toggleClass('hidden');
            },
            error: function (result) {
                console.log(result);
                console.log(result.responseText);
                var r = JSON.parse(result.responseText);
                addAlert(r['type'], r['message']);
            }
        });
    });

    $('.comment-delete-btn').click(function (e) {
       e.preventDefault();
       $.ajax({
           url: $(this).attr(ajax_url_parameter_name),
           success: function (result) {
               console.log(result);
               $('#trip-comment-' + result.comment_id).remove();
           },
           error: function (result) {
               console.log(result);
               console.log(result.responseText);
               var r = JSON.parse(result.responseText);
               addAlert(r['type'], r['message']);
           }
       });
    });

    $('.file-delete-modal-btn').click(function (e) {
        e.preventDefault();
        $('.file-delete-btn').attr(ajax_url_parameter_name, $(this).attr(ajax_url_parameter_name));
    });

    $('.file-delete-btn').click(function (e) {
       e.preventDefault();
       $.ajax({
           url: $(this).attr(ajax_url_parameter_name),
           success: function (result) {
               console.log(result);
               $('#trip-file-' + result.file_id).remove();
               $('#file-delete-modal').modal('hide');
           },
           error: function (result) {
               console.log(result);
               console.log(result.responseText);
               var r = JSON.parse(result.responseText);
               addAlert(r['type'], r['message']);
               $('#file-delete-modal').modal('hide');
           }
       });
    });
});

// TODO: Client-side form validation
