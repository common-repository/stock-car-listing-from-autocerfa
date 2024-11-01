jQuery(function($){

    // $('.autocerfa-down-content-grid').();
    var count = $(".autocerfa-down-content-grid .autocerfa-car-info p").text().length;
    if (count > 16) {
       $(".autocerfa-down-content-grid .autocerfa-car-info p").css(
        {
            "font-size": '10px',
            "line-height": '10px'
        }
    );
   }

    const get_models = () => {

        $.ajax({
            method: "POST",
            url: frontend_form_object.ajaxurl,
            data: {
                action: "autocerfa_get_model_by_make",
                make: $('[name="mark"]').val()
            }
        })
            .done(function( response ) {
                if(response.success){
                    let search_param = new URLSearchParams(location.search);
                    let selected_model = search_param.get('model');
                    let options = `<option value="">${response.data._model_text}</option><option value="">---</option>`;
                    response.data.models.map(option => {
                        let selected = option === selected_model ? 'selected' : '';
                        options += `<option ${selected}>${option}</option>`;
                    })
                    $('[name="model"]').html(options);
                }
                else{

                }
            });
    }

    $('[name="mark"]').change(function (e){
        get_models();
    })
    if($('[name="mark"]').length > 0){
        get_models();
    }


    if($('.autocerfa-sp-search-box-wrapper').length > 0){
        let default_min_price = $('[name="default_min_price"]').val() * 1;
        let default_max_price = $('[name="default_max_price"]').val() * 1;
        let search_min_price = $("#autocerfa-price-range").data('min-price') * 1;
        let search_max_price = $("#autocerfa-price-range").data('max-price') * 1;

        let localFr = Intl.NumberFormat('en-FR');
        $("#autocerfa-price-range").slider({
            range: true,
            step: 100,
            min: default_min_price,
            max: default_max_price,
            values: [search_min_price > 0 ? search_min_price : default_min_price, search_max_price > 0 ? search_max_price : default_max_price],
            format: function (value){
                return localFr.format(value)
            },
            slide: function(event, ui) {
                $('.autocerfa-sp-search-box-wrapper [name="min-price"]').val(ui.values[0]);
                $('.autocerfa-sp-search-box-wrapper [name="max-price"]').val(ui.values[1]);
                $("#autocerfaPriceRange").val("€" + localFr.format(ui.values[0]) + " - €" + localFr.format(ui.values[1]));
            }
        });

        let values = $("#autocerfa-price-range").slider('values');
        $("#autocerfaPriceRange").val("€" + localFr.format(values[0]) + " - €" + localFr.format(values[1]));
    }



    // $('.autocerfa-select').awselect();

    // Car Details Page Gallery
    $( '#single-car' ).sliderPro({
        width: 920,
        fade: true,
        arrows: true,
        buttons: false,
        fullScreen: true,
        shuffle: false,
        smallSize: 500,
        mediumSize: 1000,
        largeSize: 3000,
        thumbnailArrows: true,
        autoplay: false,
        imageScaleMode: 'exact',
        autoHeight: true
    });

    // Home slider-2 js
    $('.autocerfa_short_listed_slider_2').owlCarousel({
        items: 1,
        loop: true,
        margin:30,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        dots: false,
        autoplay:true
    });//Home slider-2 js

    $('.autocerfa_short_listed_car_slider_3').owlCarousel({
        items: 3,
        loop: true,
        margin:5,
        nav:false,
        //navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        dots: false,
        center: true,
        animateOut: 'slideOutLeft',
        autoplay:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:3
            }
        }
    });

    // Home slider 3 js
    $('.autocerfa_short_listed_car_wrapper').owlCarousel({
        items: 3,
        loop: true,
        margin:30,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        dots: false,
        animateOut: 'slideOutLeft',
        autoplay:true,
        responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
    });//Home slider 3 js

 new autocerfa_swiper( '.autocerfa_swiper-container.two', {
        // nextButton: '.autocerfa_swiper-button-next',
        // prevButton: '.autocerfa_swiper-button-prev',
        effect: 'coverflow',
        loop: true,
        autoplay:
        {
            delay: 20000000,
        },
        centeredSlides: true,
        speed: 2800,
        slidesPerView: 3,
        coverflow: {
            rotate: 0,
            stretch: -5,
            depth: 150,
            modifier: 1.5,
            slideShadows : false,
        },
        breakpoints: {
            767: {
                slidesPerView: 1,
            },
            999: {
                slidesPerView: 2,
                spaceBetweenSlides: 50
            }
        }
    } );


    // Home slider js
    $('.autocerfa_front_slider_wrapper').owlCarousel({
        items: 1,
        loop: true,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        dots: true,
        animateOut: 'slideOutLeft',
        autoplay:true
    });//Home slider js

    //new WOW().init();

});