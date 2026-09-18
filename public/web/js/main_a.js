(function ($) {
 
    // upcoming carousel
    $(".upcoming-carousel_a").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 0,
        loop: true,
        center: true,
        autoWidth: true,
        dots: false,
        nav: false,
        responsive: {
            0:{
                items:1
            },
            650:{
                items:2
            },
            992:{
                items:3
            },
            1400:{
                items:4
            },
            1600:{
                items:5
            }
        }
    });
})(jQuery);

