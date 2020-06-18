<template>
	<div>
		<!-- <div v-if="getterDolgnostiPays.data"></div>
		<div v-else="">Пару секунд, загружаю даныные</div> -->

		<!-- <div style="border: 1px solid green; max-height:150px; overflow:auto;">{{ computed_pays }}</div> -->

<!-- <div class="alert-danger" style="padding:10px 5px" >у каждой должности работают настройки только за самую свежую дату</div> -->

		<table class="table table-sm table-bordered table-striped">
			<thead>
				<tr>
					<th rowspan="2">дата назначения</th>
					<th rowspan="2">действия</th>
					<th v-if="!dolgnost || dolgnost == 'all'" rowspan="2">должность</th>
					<th rowspan="2">условия оборот</th>
					<th rowspan="2">з/п в час</th>
					<th colspan="2">оценка 3</th>
					<th colspan="2">оценка 5</th>
					<th rowspan="2">ежедневный<br />бонус<br/>от&nbsp;дневного оборота&nbsp;ТП</th>
					<th rowspan="2">если курит</th>
				</tr>
				<tr>
					<th>р/час</th>
					<th>бонус</th>
					<th>р/час</th>
					<th>бонус</th>
				</tr>
			</thead>
			<tbody>

				<dolgnosti-pays-tr v-for="v in computed_pays" :dolgnost_now="dolgnost" :v="v" :key="v.id"  ></dolgnosti-pays-tr>

			</tbody>
		</table>

		<br />

		<dolgnosti-pays-form-add v-if="sp && dolgnost && dolgnost != 'all'"></dolgnosti-pays-form-add>

	</div>
</template>

<script>
// import { mapGetters, mapActions } from 'vuex';
import { mapGetters } from 'vuex';

import DolgnostiPaysFormAdd from './dolgnosti-pays-form-add';
import DolgnostiPaysTr from './dolgnosti-pays-tr';

export default {

	components: {
		DolgnostiPaysFormAdd,
		DolgnostiPaysTr
	},

	// data() {
	// 	return {
	// 	};
	// },

	props: {
		sp: [String, Number],
		dolgnost: { type: String, default: 'all', required: false },
	},

	// created() {
	// 	console.log('created', 'dolgnost-pays');
	// },

	computed: {
		...mapGetters([
			'getterDolgnostiPays',
		]),

		computed_pays() {

			const sp_now = this.sp;
			const dolgnost_now = this.dolgnost;

			return this.getterDolgnostiPays.data.filter(function(item) {
				if (item.sale_point == sp_now) {
					if (!dolgnost_now || item.dolgnost == dolgnost_now || dolgnost_now == 'all') {
						return true;
					} else {
						return false;
					}
				}

				return false;
			});
		},
	},

	// определяйте методы в объекте `methods`
	// methods: {
	// 	// setNewSpNow: function(sp_id) {
	// 	//   console.log("method", "setNewSpNow", sp_id);
	// 	//   this.actionSetActiveSp(sp_id);
	// 	// }

	// 	...mapActions([
	// 		// 'actions_getItems',
	// 		// 'getItems',
	// 		'getterItems',
	// 		'action_getJobDescSmens',
	// 		// 'actionChangeStatus',
	// 	]),

	// 	async methodChangeStatus(id_item, status_new, s) {
	// 		// `this` внутри методов указывает на экземпляр Vue
	// 		// alert('Привет, ' + id_item + ' // ' + status_new )
	// 		// `event` — нативное событие DOM
	// 		//   if (event) {
	// 		//     alert(event.target.tagName)
	// 		//   }

	// 		await this.actionChangeStatus({
	// 			item_id: id_item,
	// 			status_new: status_new,
	// 			s: s,
	// 			resresh_module: 'dolgnosti_pays',
	// 		});

	// 		await this.actions_getItems({
	// 			var1: 'dolgnosti_pays',
	// 			module: '071.set_oplata',
	// 			sort: 'date_desc',
	// 			show: 'all',
	// 		});
	// 	},
	// },

	// async mounted() {
	// 	// this.$store.dispatch("fetchPosts");
	// 	// this.getItems({ var1: "sps", module: "sale_point" });
	// 	// this.getterSpNow();
	// 	// this.activeSp = getterSpNow;

	// 	// this.action_getJobDescSmens( { 'date_start' : getterDateNow } )

	// 	// if (!store['dolgnosti_pays'])

	// 	if (this.dolgnost && this.sp) {
	// 		console.log('тащим данные по новой', this.dolgnost, this.sp);
	// 		// this.action_getJobDescSmens({ 'uri_dop' : '&date_start='+this.date_start });
	// 		// console.log("запускаем сбор plus");
	// 		// this.getItems({
	// 		//   var1: "plus",
	// 		//   module: "072.plus",
	// 		//   xsort: "sort_asc",
	// 		//   dop: '&sp='+this.sp+'&date='+this.date_start
	// 		// });

	// 		// this.actions_getItems({ var1: 'sps', module: 'sale_point', sort: 'sort_asc' });
	// 	}
	// },

	// increment() {
	//   this.$store.commit('increment')
	//   console.log(this.$store.state.count)
	// }

	// methods: {
	//   ...mapMutations(["createPost"]),
	//   submit() {
	//     this.createPost({
	//       title: this.title,
	//       body: this.body,
	//       id: Date.now()
	//     });
	//     this.title = this.body = "";
	//   }
	// }
};
</script>