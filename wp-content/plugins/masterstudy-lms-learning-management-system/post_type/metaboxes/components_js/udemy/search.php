<script>
	<?php
	ob_start();
	include STM_LMS_PATH . '/post_type/metaboxes/components/udemy/search.php';
	$template = preg_replace("/\r|\n/", "", addslashes(ob_get_clean()));
	?>



    Vue.component('stm-udemy-search', {
        data: function () {
            return {
                loading: false,
                search_name: 'wordpress',
                courses: []
            }
        },
        mounted: function () {
            this.search();
        },
        template: '<?php echo $template; ?>',
        methods: {
            search: function () {
                var _this = this;
                _this.loading = true;
                if (this.timer) {
                    clearTimeout(this.timer);
                    this.timer = null;
                }
                this.timer = setTimeout(function () {
                    _this.$http.get(stm_lms_ajaxurl + '?action=stm_lms_pro_search_courses&s=' + _this.search_name).then(function (r) {
                        _this.loading = false;
                        _this.courses = r.body;
                    })
                }, 800);
            },
            importCourse: function (course_id, index) {
                var _this = this;
                _this.$set(_this.courses[index], 'loading', true);
                _this.$set(_this.courses[index], 'loading_text', '<?php esc_html_e('Importing Course', 'masterstudy'); ?>');
                _this.$http.get(stm_lms_ajaxurl + '?action=stm_lms_pro_udemy_import_courses&id=' + course_id).then(function (r) {
                    var r = r.body;

                    _this.$set(_this.courses[index], 'loading_text', r.message);
                    _this.$http.get(stm_lms_ajaxurl + '?action=stm_lms_pro_udemy_import_curriculum&id=' + r.course_id).then(function (r) {
                        var r = r.body;
                        _this.$set(_this.courses[index], 'loading', false);
                        _this.$set(_this.courses[index], 'imported', true);
                        _this.$set(_this.courses[index], 'stm_lms_url', r.course_url);
                        _this.$set(_this.courses[index], 'stm_lms_url_edit', r.course_url_edit);
                        _this.$set(_this.courses[index], 'loading_text', r.message);

                        STM_LMS_EventBus.$emit('stm_lms_udemy_course_imported');
                    })
                });
            },
            publish: function (index, course_id) {
                var _this = this;
                _this.$set(_this.courses[index], 'lms_publishing', true);
                _this.$http.get(stm_lms_ajaxurl + '?action=stm_lms_pro_udemy_publish_course&id=' + course_id).then(function (r) {
                    _this.$set(_this.courses[index], 'lms_publishing', false);
                    _this.$set(_this.courses[index], 'lms_published', r.body);
                });
            }
        },
        watch: {}
    })
</script>