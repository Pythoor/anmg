
new Vue({
	el:"#stm_lms_statistics",
	data:{
		pay_now_result: null,
		pay_now_loader: false
	},
	created(){

	},
	methods:{
		pay_now: function() {
			var vm = this;
			vm.pay_now_loader = true;
			vm.pay_now_result = null;
			this.$http.get('stm-lms-pauout/pay-now').then(function(response){
				vm.pay_now_loader = false;
				vm.pay_now_result = response.body;
			});
		}
	}
});