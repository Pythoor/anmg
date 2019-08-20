(function($) {
    $(document).ready(function(){
        new Vue({
            el: '#stm_lms_instructor_courses',
            data: {
                courses: [],
                loading: true,
                offset: 0,
                total: false
            },
            created: function() {
                this.getCourses();
            },
            methods: {
                getCourses() {
                    var vm = this;
                    var url = stm_lms_ajaxurl + '?action=stm_lms_get_instructor_courses&offset=' + vm.offset;
                    vm.loading = true;

                    this.$http.get(url).then(function (response) {
                        response.body['posts'].forEach(function(course){
                            vm.courses.push(course);
                        });
                        vm.total = response.body['total'];
                        vm.loading = false;
                        vm.offset++;
                    });
                },
                loadCourses() {
                    this.getCourses();
                }
            }
        });
    });
})(jQuery);