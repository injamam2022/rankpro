/*=========================================
 * modalanimations.js: Version 1.0
 * author: Suman Kunwar
 * website: http://www.humanassistai.com
 * email: sumn2u@gmail.com
 * Licensed MIT
=========================================*/

(function ($) {

    $.fn.modalAnimate = function(options) {
        var modal = $(this);
        var attackModal = $(this).attr('data-id');
        var myDataEffect = $(this).attr('data-effect');
        //alert(attackModal);
        //Defaults
        var settings = $.extend({
            modalTarget: attackModal,
            effect: myDataEffect,
            autoEffect:false,
            // Callbacks
            modalClose: function() {}
        }, options);

        

        var closeBt = $('#'+ attackModal);

        if(settings.autoEffect === true){
            var effect = $(modal).attr('data-effect')
            //alert(effect);
            //closeBt.removeAttr('class').addClass(effect)
            closeBt.removeClass(effect)
            closeBt.removeClass('out').addClass(effect)
            $('body').addClass('modal-active')
        }else{
           modal.click(function(event) {
              event.preventDefault();
              var effect = $(modal).attr('data-effect')
              closeBt.removeClass(effect)
              closeBt.removeClass('out').addClass(effect)
              $('body').addClass('modal-active')
          });
        }



        // function modalClose () {
        //   $('#'+settings.modalTarget).addClass('out');
        //   $('body').removeClass('modal-active');//modal close
        // }


    }; // End modalAnimate.js

}(jQuery));
