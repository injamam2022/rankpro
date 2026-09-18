(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    
    // Initiate the wowjs
    new WOW().init();


    // Fixed Navbar
    $('.fixed-top').css('top', $('.top-bar').height());
    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('.fixed-top').addClass('bg-shadow').css('top', 0);
        } else {
            $('.fixed-top').removeClass('bg-shadow').css('top', $('.top-bar').height());
        }
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500,'');
        return false;
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: false,
        smartSpeed: 1500,
        loop: true,
        nav: true,
        dots: false,
        items: 1,
        navText : [
            '<img src="images/banner/banner_nav_prev.png">',
            '<img src="images/banner/banner_nav_next.png">'
        ]
    });


    // Facts counter
    // $('[data-toggle="counter-up"]').counterUp({
    //     delay: 10,
    //     time: 2000
    // });

    //rankers carousel
    $('.rankers-carousel').each(function(i){
        var $this = $(this);
        var $slides = $('.slider__items',this);
        var currentSlide = 1;
        var activeSlidesLength = 0;
        var slidePositions = {};
        var totalSlides = 0;
        var trackStart = 0;
        var lastSlideWidth = 0;
        var interval = null;
        var sliderSpeed = 6000; //ms for slide to scroll
        var tweenNext = '';
        var tweenPrev = '';
        var backClicked = false;
        var autoAdvance = true;
        $slides.on('init',function(e,slick){
            var $track = $('.slick-track',$this);
            function setSizeVars() {
            activeSlidesLength = 0;
            trackStart = $('.slick-slide',$this).first().outerWidth();
            slidePositions[1] = trackStart;
            $('.slick-slide:not(.slick-cloned)',$this).each(function(i){
                var width = $(this).outerWidth();
                activeSlidesLength += width;
                slidePositions[i + 2] = trackStart + activeSlidesLength;
                lastSlideWidth = width;
                totalSlides = i + 2;
            });
            };
            setSizeVars();
            $(window).on('resize',function(){
            setSizeVars();
            });
            $track.css({'transform':'translate(-'+slidePositions[currentSlide]+'px,0,0)'});
            function goToNextSlide(transitionTime,transitionEasing) {
            if (currentSlide == totalSlides - 1) { // Go to first cloned slide if reached end
                var nextSlide = currentSlide + 1;
                tweenNext = TweenLite.to($track, 0,{
                x:-(slidePositions[1] - lastSlideWidth),
                ease: Power0.easeNone,
                onComplete: function(){
                    currentSlide = nextSlide;
                    goToNextSlide(transitionTime,transitionEasing);
                }
                });
            } else {
                var nextSlide = (currentSlide == totalSlides ? 1 : currentSlide + 1);
                tweenNext = TweenLite.to($track, transitionTime,{
                x:-(slidePositions[nextSlide]),
                ease: transitionEasing,
                onComplete: function(){
                    currentSlide = nextSlide;
                    if (autoAdvance) {
                    goToNextSlide(sliderSpeed/1000, Power0.easeNone);
                    }
                }
                });
            }
            }
            function goToPrevSlide(transitionTime,transitionEasing) {
            backClicked = true;
            if (currentSlide == totalSlides) {//Go to last Slide
                var prevSlide = currentSlide - 1;
                tweenPrev = TweenLite.to($track, 0,{
                x:-(slidePositions[prevSlide]),
                ease: Power0.easeNone,
                onComplete: function(){
                    currentSlide = prevSlide;
                    goToPrevSlide(transitionTime,transitionEasing);
                }
                });
            } else if (currentSlide == 1) {//Go to cloned slide instead of last slide
                var prevSlide = totalSlides;
                tweenPrev = TweenLite.to($track, transitionTime,{
                x:-(slidePositions[1] - lastSlideWidth),
                ease: transitionEasing,
                onComplete: function(){
                    currentSlide = prevSlide;
                }
                });
            } else {
                var prevSlide = currentSlide - 1;
                tweenPrev = TweenLite.to($track, transitionTime,{
                x:-(slidePositions[prevSlide]),
                ease: transitionEasing,
                onComplete: function(){
                    currentSlide = prevSlide;
                }
                });
            }
            }
            setTimeout(function(){
            goToNextSlide(sliderSpeed/1000, Power0.easeNone);
            },1000);
            $this.hover(function() {
            tweenNext.pause();
            },function() {
            if (backClicked) {
                goToNextSlide(sliderSpeed/1000, Power0.easeNone);
            } else {
                tweenNext.play();
            }
            autoAdvance = true;
            backClicked = false;
            });
            $('.slider-prev',$this).on('click',function(){
            if (backClicked) {
                goToPrevSlide(0.2, Power1.easeInOut);
            } else {
                tweenNext.reverse().timeScale(6);
            }
            backClicked = true;
            autoAdvance = false;
            });
            $('.slider-next',$this).on('click',function(){
            goToNextSlide(0.2, Power1.easeInOut);
            autoAdvance = false;
            });
        });
        $slides.slick({
            infinite: true,
            variableWidth: true,
            arrows:false,
            accessibility:false,
            draggable:true,
            swipe:false,
            touchMove:true,
        });
    });

    // upcoming carousel
    $(".upcoming-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 25,
        loop: true,
        center: false,
        autoWidth: true,
        dots: false,
        nav: false,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:2
            },
            1400:{
                items:3
            }
        }
    });
    $(".realstory-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        margin: 25,
        loop: true,
        dots: false,
        nav: true,
        navText : [
            '<img src="images/real_story_left.png">',
            '<img src="images/real_story_right.png">'
        ],
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:2
            }
        }
    });
    
    $("#saFlip").click(function(){
        $("#saPanel").slideToggle("slow");
        $(this).toggleClass("active");
    });
    $("#ciFlip").click(function(){
        $("#ciPanel").slideToggle("slow");
        $(this).toggleClass("active");
    });
    $("#hwFlip").click(function(){
        $("#hwPanel").slideToggle("slow");
        $(this).toggleClass("active");
    });

    
})(jQuery);

