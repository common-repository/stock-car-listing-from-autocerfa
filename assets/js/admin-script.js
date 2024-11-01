jQuery(function ($) {

    $(document).on('click', '.autocerfa_del', function () {
        let _this = $(this);
        swal({
            title: frontend_form_object.localize.are_you_sure,
            text: frontend_form_object.localize.not_able_to_revert,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: frontend_form_object.localize.yes_delete,
            cancelButtonText: frontend_form_object.localize.cancel,
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    method: "POST",
                    url: frontend_form_object.ajaxurl,
                    data: {
                        action: "autocerfa_delete_car",
                        id: $(this).parents('tr').data('id'),
                        _wpnonce: $('#_wpnonce').val()
                    }
                })
                    .done(function (response) {
                        if (response.success) {
                            swal('', response.data.message, 'success');
                            location.reload();
                        } else {
                            swal('', response.data.message, 'error');
                        }
                    });
            }
        })
    });


    $('.autocerfa-advance-configure-change').click(function () {
        $('.autocerfa-advance-configure').toggle();
    })
    var autocerfa_slider_demo_link;
    var autocerfa_slider_link_1 = "https://www.opcodespace.com/demo/?tab=short-listed-car#short_listed_car";
    var autocerfa_slider_link_2 = "https://www.opcodespace.com/demo/?tab=short-listed-car#slider_4";
    var autocerfa_slider_link_3 = "https://www.opcodespace.com/demo/?tab=short-listed-car#slider_2";
    var autocerfa_slider_link_4 = "https://www.opcodespace.com/demo/?tab=short-listed-car#slider_3";

    $('#autocerfa_select_short_listed_car').on('change', function () {
        var current_value = this.value;
        $('#autocerfa_copy_slider_shortcode').attr("value", current_value);
        if (current_value === "[autocerfa-slider id=1]") {
            autocerfa_slider_demo_link = autocerfa_slider_link_1;
        }
        if (current_value === "[autocerfa-slider id=2]") {
            autocerfa_slider_demo_link = autocerfa_slider_link_2;
        }
        if (current_value === "[autocerfa-slider id=3]") {
            autocerfa_slider_demo_link = autocerfa_slider_link_3;
        }
        if (current_value === "[autocerfa-slider id=4]") {
            autocerfa_slider_demo_link = autocerfa_slider_link_4;
        }
        $('.autocerfa_demo_btn_link').attr("href", autocerfa_slider_demo_link);
    });
    $('.autocerfa_copy_shortcode_btn').on('click', function () {

        var copyText = document.getElementById("autocerfa_copy_slider_shortcode");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
    });
    $('.autocerfa-color-picker').wpColorPicker();
    $('input[type=radio][name=short_listed_cars_option]').change(function () {
        if ($(this).is(':checked') && $(this).val() == 'auto') {
            $('.autocerfa_latest_car_showing_content').addClass('active');
            $('.autocerfa_custom_list_car_showing_content').removeClass('active');
        } else if ($(this).is(':checked') && $(this).val() == 'custom') {
            $('.autocerfa_custom_list_car_showing_content').addClass('active');
            $('.autocerfa_latest_car_showing_content').removeClass('active');
        }
    });
    $('input[type=radio][name=slider_option]').change(function () {
        if ($(this).is(':checked') && $(this).val() == 'auto') {
            $('.autocerfa_slider_latest_car_showing_content').addClass('active');
            $('.autocerfa_slider_custom_list_showing_content').removeClass('active');
        } else if ($(this).is(':checked') && $(this).val() == 'custom') {
            $('.autocerfa_slider_custom_list_showing_content').addClass('active');
            $('.autocerfa_slider_latest_car_showing_content').removeClass('active');
        }
    });

    let param = new URLSearchParams(location.search);
    /* Search Box */
    $('.autocerfa_sp_search_box .autocerfa-cmn-btn').click(function () {
        let _this = $(this);
        _this.addClass('loading');
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_save_search_box",
                search_box: {
                    box_bg_color: $('.box_bg_color').val(),
                    field_bg_color: $('.field_bg_color').val(),
                    field_border_color: $('.field_border_color').val(),
                    field_font_color: $('.field_font_color').val(),
                    range_color: $('.range_color').val(),
                    min_price: $('.min_price').val(),
                    max_price: $('.max_price').val(),
                },
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                _this.removeClass('loading').addClass('completed');
                setTimeout(() => {
                    _this.removeClass('completed');
                }, 1500, _this);
            }, _this);
    })

    /*Slider*/
    $('.autocerfa_slider_cars [name="slider_option"], .autocerfa_slider_cars [name="latest_car"]').change(function () {
        slider_settings();
    });

    function slider_settings() {
        let option = $('.autocerfa_slider_cars [name="slider_option"]:checked').val();
        let latest_car = $('.autocerfa_slider_cars [name="latest_car"]').val();
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_save_slider_settings",
                option: option,
                latest_car: latest_car,
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                // console.log(response);
            });
    }

    $('.shortcodes .autocerfa_slider_cars .add_car').click(function () {
        let _this = $(this);
        _this.addClass('loading');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'autocerfa_get_car_details',
                id: $('.shortcodes .autocerfa_slider_cars [name="short_selected_car_id"]').val(),
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                _this.removeClass('loading')
                    .addClass('completed');

                if (response.success) {
                    let row = `<tr data-id="${response.data.car.id}">
                <td>${response.data.car.marque}</td>
                <td>${response.data.car.model}</td>
                <td>${response.data.car.immat}</td>
                <td>${response.data.car.milage}</td>
                <td>${response.data.car.reg_date}</td>
                <td>${response.data.car.price}</td>
                <td class="text-center"><i class="delete_row fa fa-trash"></i></td>
                </tr>`;

                    $('#sliderShortTable tbody').append(row).sortable('refresh');
                    slider_cars();
                }

                setTimeout(function () {
                    _this.removeClass('completed');
                }, 3000, _this);
            })
    });

    $("#sliderShortTable tbody").sortable({
        cursor: "move",
        update: function (event, ui) {
        }
    });

    function slider_cars() {
        let cars = [];
        $("#sliderShortTable tbody").children().each(function (index) {
            cars.push($(this).data('id'));
        });

        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_save_slider_cars",
                cars: cars,
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                // console.log(response);
            });
    }

    /*End Slider*/
    /*Short Listed Car*/
    $('.autocerfa_short_listed_cars [name="short_listed_cars_option"], .autocerfa_short_listed_cars [name="latest_car"]').change(function () {
        short_listed_car_settings();
    });

    function short_listed_car_settings() {
        let option = $('.autocerfa_short_listed_cars [name="short_listed_cars_option"]:checked').val();
        let latest_car = $('.autocerfa_short_listed_cars [name="latest_car"]').val();
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "save_short_listed_car_settings",
                option: option,
                latest_car: latest_car,
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                // console.log(response);
            });
    }

    $('.shortcodes .autocerfa_short_listed_cars .add_car').click(function () {
        let _this = $(this);
        _this.addClass('loading');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'autocerfa_get_car_details',
                id: $('.shortcodes .autocerfa_short_listed_cars [name="short_selected_car_id"]').val(),
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                _this.removeClass('loading')
                    .addClass('completed');

                if (response.success) {
                    let row = `<tr data-id="${response.data.car.id}">
                <td>${response.data.car.marque}</td>
                <td>${response.data.car.model}</td>
                <td>${response.data.car.immat}</td>
                <td>${response.data.car.milage}</td>
                <td>${response.data.car.reg_date}</td>
                <td>${response.data.car.price}</td>
                <td class="text-center"><i class="delete_row fa fa-trash"></i></td>
                </tr>`;

                    $('#shortListedCars tbody').append(row).sortable('refresh');
                    short_listed_cars();
                }

                setTimeout(function () {
                    _this.removeClass('completed');
                }, 3000, _this);
            })
    });

    $(document).on('click', '.autocerfa_short_listed_cars .delete_row', function () {
        let _this = $(this);
        swal({
            title: frontend_form_object.localize.are_you_sure,
            text: frontend_form_object.localize.not_able_to_revert,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: frontend_form_object.localize.yes_delete,
            cancelButtonText: frontend_form_object.localize.cancel,
        }).then(willDelete => {
            if (willDelete) {
                _this.parents('tr').remove();
                short_listed_cars();
            }
        })
    });

    $(document).on('click', '.autocerfa_slider_cars .delete_row', function () {
        let _this = $(this);
        swal({
            title: frontend_form_object.localize.are_you_sure,
            text: frontend_form_object.localize.not_able_to_revert,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: frontend_form_object.localize.yes_delete,
            cancelButtonText: frontend_form_object.localize.cancel,
        }).then(willDelete => {
            if (willDelete) {
                _this.parents('tr').remove();
                slider_cars();
            }
        })
    });

    $("#shortListedCars tbody").sortable({
        cursor: "move",
        containment: "parent",
        update: function (event, ui) {
            short_listed_cars();
        }
    });

    function short_listed_cars() {
        let cars = [];
        $("#shortListedCars tbody").children().each(function (index) {
            cars.push($(this).data('id'));
        });

        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_save_short_listed_cars",
                cars: cars,
                autocerfa_shortcodes: $('#autocerfa_shortcodes').val()
            }
        })
            .done(function (response) {
                // console.log(response);
            });
    }

    /*End Short Listed Car*/
    $('.autocerfa-single-page-btn').click(function () {
        $(this).addClass('loading');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_autocerfa_single_page',
                id: $('[name="autocerfa-single-page"]').val(),
                _wpnonce: $('.autocerfa-general-wrappper #_wpnonce').val()
            }
        })
            .done(function (response) {
                $('.autocerfa-single-page-btn').removeClass('loading')
                    .addClass('completed');

                setTimeout(function () {
                    $('.autocerfa-single-page-btn').removeClass('completed');
                }, 3000)
            })
    });

    $('.autocerfa-car-list-page-btn').click(function () {
        $(this).addClass('loading');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_autocerfa_car_list_page',
                id: $('[name="autocerfa-car-list-page"]').val(),
                _wpnonce: $('.autocerfa-general-wrappper #_wpnonce').val()
            }
        })
            .done(function (response) {
                $('.autocerfa-car-list-page-btn').removeClass('loading')
                    .addClass('completed');

                setTimeout(function () {
                    $('.autocerfa-car-list-page-btn').removeClass('completed');
                }, 3000)
            })
    });

    $('.autocerfa-save-general-settings').click(function () {
        $(this).addClass('loading');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'autocerfa_save_general_settings',
                car_per_page: $('[name="car_per_page"]').val(),
                single_page_slug: $('[name="single_page_slug"]').val(),
                autocerfa_theme_color_1: $('[name="autocerfa_theme_color_1"]').val(),
                autocerfa_theme_color_2: $('[name="autocerfa_theme_color_2"]').val(),
                autocerfa_theme_color_3: $('[name="autocerfa_theme_color_3"]').val(),
                filter_option: $('[name="filter_option"]:checked').val(),
                autocerfa_debug: $('[name="autocerfa_debug"]:checked').val(),
                cropping_image_as_aspect_ration: $('[name="cropping_image_as_aspect_ration"]:checked').val(),
                daily_sync: $('[name="daily_sync"]').val(),
                autocerfa_view_style: $('[name="autocerfa_view_style"]').val(),
                _wpnonce: $('.autocerfa-general-wrappper #_wpnonce').val()
            }
        })
            .done(function (response) {
                $('.autocerfa-save-general-settings').removeClass('loading')
                    .addClass('completed');

                setTimeout(function () {
                    $('.autocerfa-save-general-settings').removeClass('completed');
                }, 3000)
            })
    });

    if (param.get('page') === 'autocerfa') {
        setInterval(autocerfa_bg_process_check, 10000);
    }

    $(".choosen_select").chosen();

    $('.insert-page-btn').on('click', function () {
        $(this).addClass('loading');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'autocerfa_creating_page',
                name: $('.step-creating-page-content [name="page_name"]').val(),
                _wpnonce: $('.step-bar-wrapper #_wpnonce').val()
            },
        })
            .done(function (response) {
                $('.step-creating-page-content .new-permalink')
                    .text(response.data.link)
                    .attr('href', response.data.link);
                $('.insert-page-btn').removeClass('loading');
            })
    });


    $('.synchronize-now').on('click', function () {
        $('#autocerfa_bg_processing_message').show();
        swal({
            title: '',
            text: frontend_form_object.localize.optimize_notice,
            type: '',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    url: frontend_form_object.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'autocerfa_sync_now',
                        daily_sync: $('.step-synchronization-content [name="daily_sync"]').val(),
                        sending_email: true,
                        _wpnonce: $('.step-bar-wrapper #_wpnonce').val()
                    },
                })
                    .done(function (response) {
                        if (!response.success) {
                            $('#autocerfa_bg_processing_message').hide();
                            swal('', response.data.message, 'error');
                        } else {
                            addStep(3);
                            $('.autocerfa-step-bar ul li.creating-page').trigger('click');
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        swal('', xhr.responseText, 'error');
                    })
            }
        })
    });

    $('.autocerfa-general-wrappper .view_log_btn').click(function (e) {
        e.preventDefault();

    })

    $('.refresh-now').on('click', function () {
        $('#autocerfa_bg_processing_message').show();
        swal({
            title: '',
            text: frontend_form_object.localize.optimize_notice,
            type: '',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    url: frontend_form_object.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_cars_from_autocerfa',
                        _wpnonce: $('.auto-cerfa-wrapper #_wpnonce').val()
                    },
                })
                    .done(function (response) {
                        if (!response.success) {
                            $('#autocerfa_bg_processing_message').hide();
                            swal('', response.data.message, 'error');
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        swal('', xhr.responseText, 'error');
                    })
            }
        })
    });


    $(document).on('change', '.multi-diff input', function (e) {
        let id = $(this).data('id');
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: "POST",
            data: {
                action: "save_multi_diff",
                hidden: $(this).is(":checked") ? 1 : 0,
                id: id
            }
        })
            .done(function (response) {
                //toastr.success('Successfully saved', 'Success', {timeOut: 5000});
                // response.data.test('Successfully saved');
            });
    });

    function autocerfa_bg_process_check() {
        $.ajax({
            url: frontend_form_object.ajaxurl,
            type: 'POST',
            data: {action: 'autocerfa_bg_process_check'},
        })
            .done(function (response) {
                if (!response.data.status.autocerfa_processing && response.data.status.reload) {
                    location.reload();
                }
            })
    }

    $('.autocerfa-step-bar ul li').on('click', function () {
        $(this).addClass('active');
        $(this).prev().addClass('complete');
        $(this).siblings().removeClass('active');
    });

    $('.autocerfa-step-bar ul li.authorization').on('click', function () {
        addStep(1);
        $('.step-authorization-content').addClass('active');
        $('.step-synchronization-content, .step-creating-page-content').removeClass('active');
    });

    $('.autocerfa-step-bar ul li.synchronization').on('click', function () {
        addStep(2);
        $('.step-synchronization-content').addClass('active');
        $('.step-authorization-content, .step-creating-page-content').removeClass('active');
    });

    $('.autocerfa-step-bar ul li.creating-page').on('click', function () {
        addStep(3);
        $('.step-creating-page-content').addClass('active');
        $('.step-authorization-content, .step-synchronization-content').removeClass('active');
    });

    const addStep = (step) => {
        let stateObj = {id: "100"};
        let param = new URLSearchParams(location.search);
        param.set('step', step);
        window.history.pushState(stateObj,
            `Step ${step}`, location.origin + location.pathname + '?' + param.toString());
    }

    $(window).on('load', function () {
        $('#windowModal').modal('show');
    });

    $('.badge_show_clr').on('click', function () {
        let bg_color = $(this).css('background-color');
        let font_color = $(this).css('color');
        $(this).parent().siblings(".autocerfa_dropdown_btn").text($(this).text());
        $(this).parent().siblings(".autocerfa_dropdown_btn").css(
            {
                "background-color": bg_color,
                "color": font_color
            }
        );

        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_set_badge",
                id: $(this).parents('tr').data('id'),
                _wpnonce: $('#_wpnonce').val(),
                badge_id: $(this).data('id')
            }
        })
            .done(function (response) {
                if (response.success) {

                } else {
                    swal('', response.data.message, 'error');
                }
            });
    });

    $('.remove_badge').click(function () {
        let row = $(this).parents('tr');
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_remove_badge",
                id: $(this).parents('tr').data('id'),
                _wpnonce: $('#_wpnonce').val(),
            }
        })
            .done(function (response) {
                if (response.success) {
                    row.find('.autocerfa_dropdown_btn').attr('style', false);
                } else {
                    swal('', response.data.message, 'error');
                }
            }, row);
    })

    $('.add_badge').click(function () {
        let id = $(this).parents('tr').data('id');
        $('#addBadge [name="id"]').val(id);

    })

    $('.autocerfa-badge-save').click(function () {
        let id = $('#addBadge [name="id"]').val();
        let badge_id = $('#addBadge [name="badge_id"]').val();
        let autocerfa_badge_text = $('#addBadge [name="autocerfa_badge_text"]').val();
        let autocerfa_badge_bg_color = $('#addBadge [name="autocerfa_badge_bg_color"]').val();
        let autocerfa_badge_txt_color = $('#addBadge [name="autocerfa_badge_txt_color"]').val();
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_badge_save",
                _wpnonce: $('#autocerfa-badge-modal').val(),
                id: id,
                badge_id: badge_id,
                autocerfa_badge_text: autocerfa_badge_text,
                autocerfa_badge_bg_color: autocerfa_badge_bg_color,
                autocerfa_badge_txt_color: autocerfa_badge_txt_color,
            }
        })
            .done(function (response) {
                if (response.success) {
                    swal('', response.data.message, 'success');
                    location.reload();
                } else {
                    swal('', response.data.message, 'error');
                }
            });
    })

    $('.autocerfa_badge_edit').click(function () {
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_get_badge",
                badge_id: $(this).parents('.autocerfa_view_badge').data('badge_id')
            }
        })
            .done(function (response) {
                if (response.success) {
                    $('#addBadge [name="badge_id"]').val(response.data.badge.badge_id);
                    $('#addBadge [name="autocerfa_badge_text"]').val(response.data.badge.label);
                    $('#addBadge [name="autocerfa_badge_bg_color"]').val(response.data.badge.background_color);
                    $('#addBadge [name="autocerfa_badge_txt_color"]').val(response.data.badge.text_color);
                } else {
                    swal('', response.data.message, 'error');
                }
            });
    })

    $(document).on('click', '.autocerfa_badge_delete', function () {
        let _this = $(this);
        swal({
            title: frontend_form_object.localize.are_you_sure,
            text: frontend_form_object.localize.not_able_to_revert,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: frontend_form_object.localize.yes_delete,
            cancelButtonText: frontend_form_object.localize.cancel,
        }).then(willDelete => {
            if (willDelete) {
                $.ajax({
                    method: "POST",
                    url: frontend_form_object.ajaxurl,
                    data: {
                        action: "autocerfa_delete_badge",
                        badge_id: $(this).parents('.autocerfa_view_badge').data('badge_id'),
                        _wpnonce: $('#_wpnonce').val()
                    }
                })
                    .done(function (response) {
                        if (response.success) {
                            swal('', response.data.message, 'success');
                            location.reload();
                        } else {
                            swal('', response.data.message, 'error');
                        }
                    });
            }
        })
    });

    $('#addSoldCar .chosen-search-input').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: frontend_form_object.ajaxurl,
                type: "POST",
                data: {
                    registration: request.term,
                    action: 'autocerfa_sold_car_by_registration'
                },
                success: function (result) {
                    if (result.success) {
                        response($.map(result.data.leads, function (item) {
                            return {
                                label: item.extra_column_16,
                                value: item.id
                            };
                            // $('#addSoldCar .choosen_select').append('<option value="'+item.id+'">' + item.extra_column_16 + '</option>');
                        }));
                        $('#addSoldCar .chosen-search-input').removeClass('ui-autocomplete-loading')
                    }
                },

            });
        },
        select: function (event, ui) {
            $('#addSoldCar .chosen-search-input').val(ui.item.label);
            $("[name='sold_lead_id']").val(ui.item.value);
            return false;
        },
        minLength: 2,
    });

    $('.autocerfa-sold-car').click(function () {
        let _this = $(this);
        _this.addClass('loading');
        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_save_sold_car",
                sold_lead_id: $("[name='sold_lead_id']").val(),
                _wpnonce: $('#sold_lead_nonce').val()
            }
        })
            .done(function (response) {
                $('#addSoldCar').modal('hide');
                if (response.success) {
                    _this.removeClass('loading')
                        .addClass('completed');

                    setTimeout(function () {
                        _this.removeClass('completed');
                    }, 3000, _this)
                } else {
                    _this.removeClass('loading');
                    swal('', response.data.message, 'error');
                }
            }, _this);
    })

});

