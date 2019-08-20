(function ($) {
    document.addEventListener('lazybeforeunveil', function(e){
        $(e.target).closest('.stm_lms_lazy_image').addClass('stm_lms_lazyloaded');
    });

    $(document).ready(function () {

        $('.stm_lms_log_in[data-lms-modal]').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if ($('.stm-lms-wrapper__login').length) {
                $(this).removeAttr('data-lms-modal');
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(".stm-lms-wrapper__login").offset().top
                }, 300);
            }
        });



        var modal_body = [];

        $('[data-lms-modal]').on('click', function (e) {
            console.log('kek?');
            e.preventDefault();

            var modal_target = $(this).attr('data-target');

            if (!$(modal_target).length) {
                var modal = $(this).attr('data-lms-modal');
                var params = $(this).attr('data-lms-params');
                $.ajax({
                    url: stm_lms_ajaxurl,
                    dataType: 'json',
                    context: this,
                    data: {
                        action: 'stm_lms_load_modal',
                        modal: modal,
                        params: params
                    },
                    beforeSend: function () {
                        $(this).addClass('loading');
                    },
                    complete: function (data) {
                        var data = data['responseJSON'];
                        $(this).addClass('modal-loaded');
                        $(this).removeClass('loading');

                        modal_body[modal_target] = $(data['modal']).appendTo('body');

                        toggleModal(modal_target);

                    }
                });
            } else {
                toggleModal(modal_target);
            }

        });

        function toggleModal(modal) {
            $(modal).modal('toggle');
        }

        $('[data-buy-course]').on('click', function (e) {
            var item_id = $(this).attr('data-buy-course');

            if (typeof item_id === 'undefined') {
                window.location = $(this).attr('href');
                return false;
            }


            $.ajax({
                url: stm_lms_ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    action: 'stm_lms_add_to_cart',
                    item_id: item_id,
                },
                beforeSend: function () {
                    $(this).addClass('loading');
                },
                complete: function (data) {
                    var data = data['responseJSON'];
                    $(this).removeClass('loading');

                    $(this).find('span').text(data['text']);

                    if (data['cart_url']) {
                        if (data['redirect']) window.location = data['cart_url'];
                        $(this).attr('href', data['cart_url']).removeAttr('data-buy-course');
                    }

                }
            });

            e.preventDefault();
        });

        $('[data-delete-course]').on('click', function (e) {
            e.preventDefault();
            var item_id = $(this).data('delete-course');
            $.ajax({
                url: stm_lms_ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    action: 'stm_lms_delete_from_cart',
                    item_id: item_id,
                },
                beforeSend: function () {
                    $(this).addClass('loading');
                },
                complete: function (data) {
                    $(this).removeClass('loading');
                    $(this).closest('.item_can_hide').slideUp();
                    location.reload();
                }
            });
        });

        $('.stm_lms_logout').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: stm_lms_ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    action: 'stm_lms_logout',
                },
                complete: function (data) {
                    location.reload();
                }
            });
        });

    })
})(jQuery);

function vueRecaptchaApiLoaded() {
    if (typeof grecaptcha !== 'undefined') {
        grecaptcha.render(document.getElementsByClassName('g-recaptcha')[0]);
    }
}