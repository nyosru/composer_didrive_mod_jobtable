<template>
	<tr>
		<td>
			<!-- {{ indx }} -->
			<!-- <span v-show="v.date != last_date" > -->
			<!-- {{ last_date = v.date }} -->
			{{ v.date }}
			<!-- </span> -->

{{ v }}

		</td>
		<td>
			<div class="btn-group btn-group-xs" role="group" aria-label="...">
				<button
					class="btn btn-xs "
					:class="[v.status == 'show' ? 'btn-success' : 'btn-light']"
					@click="methodChangeStatus(v.id, 'show', v.s)"
				>
					вкл
				</button>
				<button
					class="btn btn-xs "
					:class="[v.status == 'hide' ? 'btn-warning' : 'btn-light']"
					@click="methodChangeStatus(v.id, 'hide', v.s)"
				>
					выкл
				</button>
				<button
					class="btn btn-xs "
					:class="[v.status == 'delete' ? 'btn-danger' : 'btn-light']"
					@click="methodChangeStatus(v.id, 'delete', v.s)"
				>
					удалить
				</button>
			</div>

			<button @click="show_div_copy_to_sp = !show_div_copy_to_sp">скопировать</button>

			<div v-if="show_div_copy_to_sp" class="copy_to_sps">
				<span v-for="v_sp in getterSps.data" :key="v_sp.id">
					<label v-if="v_sp.head != 'default' && v_sp.id != getterSpNow">
						<input type="checkbox" v-model="form_send_sp" :value="v_sp.id" /> {{ v_sp.head }}
					</label>
				</span>

				<button @click="method__sendPaysToSps({ id: v.id, ar: form_send_sp, s: v.s })">
					скопировать скопировать
				</button>
			</div>

			<div class="alert alert-warning" v-if="copy_to_sp_show_div">{{ copy_to_sp_show_div_text }}</div>
			<!-- {{ form_send_sp }} -->
		</td>
		<td v-if="!getterDolgnostNow || getterDolgnostNow == 'all'">
			{{ getterDolgnosti.data[v.dolgnost]['head'] }}
		</td>
		<td>
			<div v-if="v.oborot_sp_last_monht_bolee > 0" title="равно или более">
				в месяц
				<nobr>&gt;= {{ v.oborot_sp_last_monht_bolee }}</nobr>
			</div>
			<div v-if="v.oborot_sp_last_monht_menee > 0" title="равно или менее">
				в месяц
				<nobr>&lt;= {{ v.oborot_sp_last_monht_menee }}</nobr>
			</div>
		</td>
		<td>{{ v['ocenka-hour-base'] }}</td>
		<td>{{ v['ocenka-hour-3'] }}</td>
		<td>{{ v['premiya-3'] }}</td>
		<td>{{ v['ocenka-hour-5'] }}</td>
		<td>{{ v['premiya-5'] }}</td>
		<td>
			{{ v['bonus_proc_from_oborot'] }}
		</td>
		<td>
			{{ v['if_kurit'] }}
		</td>
	</tr>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
	data() {
		return {
			show_div_copy_to_sp: false,
			form_send_sp: [],
			copy_to_sp_show_div: false,
			copy_to_sp_show_div_text: '',
		};
	},

	props: {
		v: { type: Object },
	},

	// created() {	},

	computed: {
		...mapGetters([
			// 'getterSpNow',
			// 'getterDateNow',
			'getterDolgnosti',
			'getterDolgnostNow',
			// 'getterDolgnostiPays',
			'getterSps',
			'getterSpNow',
		]),

		// dolgnostNow(){
		// 	return this.$store.getters.getterDolnostNow;
		// },

		// computed_get_now_sp() {
		//   return this.$store.getters.getterSpNow;
		// },
		// computed_get_now_date() {
		//     console.log('computed_get_now_sp',this.$store.getters.getterDateNow);
		//   return this.$store.getters.getterDateNow;
		// }

		// computed_pays() {
		// 	// return [ this.sp, this.dolgnost,34];

		// 	const sp_now = this.sp;
		// 	const dolgnost_now = this.dolgnost;

		// 	return this.getterDolgnostiPays.data.filter(function(item) {
		// 		if (item.sale_point == sp_now) {
		// 			if (!dolgnost_now || item.dolgnost == dolgnost_now || dolgnost_now == 'all') {
		// 				return true;
		// 			} else {
		// 				return false;
		// 			}
		// 		}

		// 		return false;
		// 		//return item > 0;
		// 	});
		// },
	},

	// определяйте методы в объекте `methods`
	methods: {
		// setNewSpNow: function(sp_id) {
		//   console.log("method", "setNewSpNow", sp_id);
		//   this.actionSetActiveSp(sp_id);
		// }

		...mapActions([
			'actions_getItems',
			// 'getItems',
			'getterItems',
			'action_getJobDescSmens',
			'actionChangeStatus',
			'aсtions__sendPaysToSps',
		]),



		async methodChangeStatus(id_item, status_new, s) {
			// `this` внутри методов указывает на экземпляр Vue
			// alert('Привет, ' + id_item + ' // ' + status_new )
			// `event` — нативное событие DOM
			//   if (event) {
			//     alert(event.target.tagName)
			//   }

			await this.actionChangeStatus({
				item_id: id_item,
				status_new: status_new,
				s: s,
				resresh_module: 'dolgnosti_pays',
			});

			await this.actions_getItems({
				var1: 'dolgnosti_pays',
				module: '071.set_oplata',
				sort: 'date_desc',
				show: 'all',
			});
		},

		async method__sendPaysToSps(ar = { id: '', ar: [], s: '' }) {
			console.log('method__sendPaysToSps', ar);

			/** вносим изменения **/
			let res1 = await this.aсtions__sendPaysToSps({ item_id: ar.id, to_sps: ar.ar, s: ar.s });

			/** обновляем данные, тащим по новой **/
			await this.actions_getItems({
				var1: 'dolgnosti_pays',
				module: '071.set_oplata',
				sort: 'date_desc',
				show: 'all',
			});

			console.log('method__sendPaysToSps', 'готово');

			this.show_div_copy_to_sp = false;

			this.copy_to_sp_show_div_text = 'готово, запрос отправлен';
			this.copy_to_sp_show_div = true;
		},
	},

	async mounted() {
		// this.$store.dispatch("fetchPosts");
		// this.getItems({ var1: "sps", module: "sale_point" });
		// this.getterSpNow();
		// this.activeSp = getterSpNow;
		// this.action_getJobDescSmens( { 'date_start' : getterDateNow } )
		// if (!store['dolgnosti_pays'])
		// if (this.dolgnost && this.sp) {
		// 	console.log('тащим данные по новой', this.dolgnost, this.sp);
		// 	// this.action_getJobDescSmens({ 'uri_dop' : '&date_start='+this.date_start });
		// 	// console.log("запускаем сбор plus");
		// 	// this.getItems({
		// 	//   var1: "plus",
		// 	//   module: "072.plus",
		// 	//   xsort: "sort_asc",
		// 	//   dop: '&sp='+this.sp+'&date='+this.date_start
		// 	// });
		// 	// this.actions_getItems({ var1: 'sps', module: 'sale_point', sort: 'sort_asc' });
		// }
	},

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

<style scoped>
.copy_to_sps {
	border: 1px solid gray;
	padding: 10px 15px;
	margin: 5px;
}
label {
	padding: 3px 5px;
	margin: 1px;
	background-color: rgba(0, 0, 0, 0.1);
}
button {
	/* padding: 5px 10px;
	margin: 2px; */
	cursor: pointer;
	/* border: 1px solid gray; */
}
.success {
	background-color: rgba(0, 255, 0, 0.2);
}
table {
	font-size: 12px;
}
</style>
