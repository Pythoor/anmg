<?php

$r_enabled = STM_LMS_Helpers::g_recaptcha_enabled();
enqueue_login_script();
stm_lms_register_style('login');

?>

<div id="stm-lms-login" class="stm-lms-login active">

    <div class="stm-lms-login__top">
		<?php if (defined('WORDPRESS_SOCIAL_LOGIN_ABS_PATH')) do_action('wordpress_social_login'); ?>
        <h3><?php esc_html_e('Login', 'masterstudy-lms-learning-management-system'); ?></h3>
    </div>

    <div class="stm_lms_login_wrapper">

        <div class="form-group">
            <label class="heading_font">
                <?php echo apply_filters('stm_lms_login_label', esc_html__('Login', 'masterstudy-lms-learning-management-system')); ?>
            </label>
            <input class="form-control"
                   type="text"
                   name="login"
                   v-model="login"
                   placeholder="<?php esc_html_e('Enter login', 'masterstudy-lms-learning-management-system'); ?>"/>
        </div>

        <div class="form-group">
            <label class="heading_font">
                <?php echo apply_filters('stm_lms_password_label', esc_html__('Password', 'masterstudy-lms-learning-management-system')); ?>
            </label>
            <input class="form-control"
                   type="password"
                   name="password"
                   v-model="password"
                   placeholder="<?php esc_html_e('Enter password', 'masterstudy-lms-learning-management-system'); ?>"/>
        </div>

		<?php if ($r_enabled):
            $recaptcha = STM_LMS_Helpers::g_recaptcha_keys();
            ?>
            <div class="form-group">
                <vue-recaptcha
                        ref="recaptcha"
                        @verify="onCaptchaVerified"
                        @expired="onCaptchaExpired"
                        sitekey="<?php echo esc_attr($recaptcha['public']); ?>">
                </vue-recaptcha>
            </div>
		<?php endif; ?>

        <div class="stm_lms_login_wrapper__actions">

            <label class="stm_lms_styled_checkbox">
                <span class="stm_lms_styled_checkbox__inner">
                    <input type="checkbox" name="remember_me"/>
                    <span><i class="fa fa-check"></i> </span>
                </span>
                <span><?php esc_html_e('Remember me', 'masterstudy-lms-learning-management-system'); ?></span>
            </label>

            <span class="lostpassword"
                  @click.prevent="open_lost_password = !open_lost_password"
                  title="<?php esc_html_e('Lost Password', 'masterstudy-lms-learning-management-system'); ?>">
                <?php esc_html_e('Lost Password', 'masterstudy-lms-learning-management-system'); ?>
            </span>

            <a href="#"
               class="btn btn-default"
               v-bind:class="{'loading': loading}"
               @click.prevent="logIn()">
                <span><?php esc_html_e('Login', 'masterstudy-lms-learning-management-system'); ?></span>
            </a>

        </div>

        <div class="stm_lms_lost_password_form" v-if="open_lost_password">

            <div class="form-group">
                <label class="heading_font">
                    <?php echo apply_filters('stm_lms_lost_password_label', esc_html__('Login/E-mail', 'masterstudy-lms-learning-management-system')); ?>
                </label>
                <input class="form-control"
                       type="text"
                       name="login"
                       v-model="lost_password"
                       placeholder="<?php esc_html_e('Enter login/e-mail', 'masterstudy-lms-learning-management-system'); ?>"/>
            </div>

            <a href="#"
               class="btn btn-default"
               v-bind:class="{'loading': lost_password_process}"
               @click.prevent="lostPassword()">
                <span><?php esc_html_e('Send', 'masterstudy-lms-learning-management-system'); ?></span>
            </a>

        </div>

    </div>

    <transition name="slide-fade">
        <div class="stm-lms-message" v-bind:class="status" v-if="message">
            {{ message }}
        </div>
    </transition>

    <script type="text/javascript">
        vueRecaptchaApiLoaded();
    </script>
</div>