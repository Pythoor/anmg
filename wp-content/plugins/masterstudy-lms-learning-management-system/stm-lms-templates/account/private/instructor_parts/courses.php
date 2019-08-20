<?php
/**
 * @var $current_user
 */

wp_enqueue_script('vue-resource.js');
stm_lms_register_script('instructor_courses');

$links = STM_LMS_Instructor::instructor_links();
stm_lms_register_style('instructor_courses');

?>

<div class="stm_lms_instructor_courses__top">
    <h3><?php esc_html_e('Courses', 'masterstudy-lms-learning-management-system'); ?></h3>
    <a href="<?php echo esc_url($links['add_new']); ?>" class="btn btn-default" target="_blank">
        <i class="fa fa-plus"></i>
		<?php esc_html_e('Add New course', 'masterstudy-lms-learning-management-system'); ?>
    </a>
</div>

<div class="stm_lms_instructor_courses" id="stm_lms_instructor_courses" v-if="courses.length">

    <div class="stm_lms_instructor_courses__grid">

        <div class="stm_lms_instructor_courses__single" v-for="course in courses">
            <div class="stm_lms_instructor_courses__single__inner">
                <div class="stm_lms_instructor_courses__single--image">

                    <div class="stm_lms_post_status heading_font"
                         v-if="course.post_status"
                         v-bind:class="course.post_status.status">
                        {{ course.post_status.label }}
                    </div>

                    <div class="stm_lms_instructor_courses__single--actions heading_font">
                        <a v-bind:href="course.edit_link" target="_blank"><?php esc_html_e('Edit', 'masterstudy-lms-learning-management-system'); ?></a>
                        <a v-bind:href="course.link" target="_blank"><?php esc_html_e('View', 'masterstudy-lms-learning-management-system'); ?></a>
                    </div>
                    <div v-html="course.image"></div>
                </div>
                <div class="stm_lms_instructor_courses__single--inner">

                    <div class="stm_lms_instructor_courses__single--terms" v-if="course.terms">
                        <div class="stm_lms_instructor_courses__single--term"
                             v-for="(term, key) in course.terms"
                             v-html="term + ' >'" v-if="key === 0">
                        </div>
                    </div>

                    <div class="stm_lms_instructor_courses__single--title">
                        <a v-bind:href="course.link">
                            <h5 v-html="course.title"></h5>
                        </a>
                    </div>

                    <div class="stm_lms_instructor_courses__single--meta">
                        <div class="average-rating-stars__top">
                            <div class="star-rating">
                                <span v-bind:style="{'width' : course.percent + '%'}">
                                    <strong class="rating">{{ course.average }}</strong>
                                </span>
                            </div>
                            <div class="average-rating-stars__av heading_font">
                                {{ course.average }} ({{course.total}})
                            </div>
                        </div>
                        <div class="views">
                            <i class="lnr lnr-eye"></i>
                            {{ course.views }}
                        </div>
                    </div>

                    <div class="stm_lms_instructor_courses__single--bottom">
                        <div class="stm_lms_instructor_courses__single--status" v-bind:class="course.status">
                            <i class="lnr lnr-checkmark-circle" v-if="course.status == 'publish'"></i>
                            {{ course.status_label }}
                        </div>
                        <div class="stm_lms_instructor_courses__single--price heading_font">
                            <span v-if="course.sale_price">{{ course.sale_price }}</span>
                            <strong v-if="course.price">{{ course.price }}</strong>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <a href="#"
       class="btn btn-default"
       @click.prevent="loadCourses()"
       v-if="!total"
       v-bind:class="{'loading': loading}">
        <span><?php esc_html_e('Load more', 'masterstudy-lms-learning-management-system') ?></span>
    </a>


</div>
