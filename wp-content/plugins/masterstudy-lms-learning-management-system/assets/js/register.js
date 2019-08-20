(function ($) {
    $(window).load(function () {
        stm_lms_register(true);
    })
})(jQuery);

function stm_lms_register(redirect) {
    var vue_obj = {
        el: '#stm-lms-register',
        data: function () {
            return {
                loading: false,
                login: '',
                email: '',
                password: '',
                password_re: '',
                message: '',
                status: '',
                become_instructor: '',
                degree: '',
                expertize: '',
                recaptcha: '',
                captcha_error: '',
                privacy_policy: true,
                has_privacy_policy: false
            }
        },
        methods: {
            hasPrivacyPolicy() {
                if(!this.has_privacy_policy) {
                    this.has_privacy_policy = true;
                    this.privacy_policy = false;
                }
            },
            register() {
                var vm = this;
                vm.loading = true;
                vm.message = '';
                var data = {
                    'user_login' : vm.login,
                    'user_email' : vm.email,
                    'user_password' : vm.password,
                    'user_password_re' : vm.password_re,
                    'become_instructor' : vm.become_instructor,
                    'privacy_policy' : vm.privacy_policy,
                    'degree' : vm.degree,
                    'expertize' : vm.expertize,
                    'recaptcha' : vm.recaptcha,
                };

                this.$http.post(stm_lms_ajaxurl + '?action=stm_lms_register', data).then(function(response){
                    vm.message = response.body['message'];
                    vm.status = response.body['status'];
                    vm.loading = false;

                    if (response.body['user_page']) {
                        if (redirect) {
                            window.location = response.body['user_page'];
                        } else {
                            location.reload();
                        }
                    }

                    if(typeof VueRecaptcha !== 'undefined') this.$refs.recaptcha.reset();
                });
            },
            onCaptchaVerified(recaptchaToken) {
                this.recaptcha = recaptchaToken;
                this.captcha_error = false;
            },
            onCaptchaExpired() {
                this.recaptcha = '';
            }
        }
    };

    if(typeof VueRecaptcha !== 'undefined') {
        vue_obj['components'] = {VueRecaptcha};
    }

    new Vue(vue_obj);
}