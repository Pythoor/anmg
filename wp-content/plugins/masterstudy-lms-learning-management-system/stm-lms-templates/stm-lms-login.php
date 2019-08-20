<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php get_header();
wp_enqueue_script('vue.js');
wp_enqueue_script('vue-resource.js');

?>

    <div class="stm-lms-wrapper stm-lms-wrapper__login">

        <div class="container">
            <div class="row">
                <div class="col-md-6">
					<?php STM_LMS_Templates::show_lms_template('account/login'); ?>
                </div>
                <div class="col-md-6">
					<?php STM_LMS_Templates::show_lms_template('account/register'); ?>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>