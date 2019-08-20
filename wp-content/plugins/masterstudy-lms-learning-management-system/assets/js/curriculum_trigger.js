(function ($) {
    $(document).ready(function(){

        $('.stm-lms-curriculum-trigger').on('click', function(){
            $('body').toggleClass('curriculum-opened');
        });

        $('.stm-curriculum__close, .stm-lms-course__overlay').on('click', function(){
            $('body').removeClass('curriculum-opened');
        });

    });
})(jQuery);