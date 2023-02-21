if (document.getElementById('vue_formobject')) {

	Vue.component('form_orderlist', {
		props: ["list_number"],
	    template: `
			<div class="row mx-0 mb-3">	
				<div class="col-12 col-sm-6 col-lg-25 px-0 mr-lg-1">
					<div>Бренд</div>
					<div><input class="form-control" :name="'brend_' + this.list_number" type="text"></div>
				</div>
				<div class="col-12 col-sm-6 col-lg-25 px-0 mr-lg-1">
					<div>Наименование</div>
					<div><input class="form-control" :name="'prodname_' + this.list_number" type="text"></div>
				</div>
				<div class="col-12 col-sm-6 col-lg-25 px-0 mr-lg-1">
					<div>Количество</div>
					<div><input class="form-control" :name="'count_' + this.list_number" type="text"></div>
				</div>
				<div class="col-12 col-sm-6 col-lg-25 px-0 mr-lg-1">
					<div>Фасофка</div>
					<div><input class="form-control" :name="'fasovka_' + this.list_number" type="text"></div>
				</div>
				<div class="col-12 col-sm-6 col-lg-25 px-0">
					<div>Клиент</div>
					<div><input class="form-control" :name="'customer_' + this.list_number" type="text"></div>
				</div>				
			</div>
	    `
	});

	var vue_formobject = new Vue({
        el: '#vue_formobject',
        data: {
        	num: 0,
            orders_list_count: ['0'],
        },
        methods:{
        	upcount(){
        		this.num++;
        		if(this.num>0){
	        		this.orders_list_count = [];
					for (var i = 0; i <= this.num; i++) {
					   this.orders_list_count.push(i);
					}	
        		}
        		else{
        			orders_list_count = ['0'];
        		}

        	},
        	downcount(){
        		if(this.num>0){
        			this.num--;
        			this.orders_list_count = [];
					for (var i = 0; i <= this.num; i++) {
					   this.orders_list_count.push(i);
					}
        		}else{
        			orders_list_count = ['0'];
        		}        		
        	}
        }
    });
}