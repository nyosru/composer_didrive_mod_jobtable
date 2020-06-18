<template>
	<div style="text-align:left;">
		<h4>Точки продаж</h4>
		<div v-if="!getterSps.data">Загружаю точки продаж ...</div>
			<button
				class="btn btn-xs "
				:class="{ 'btn-success': getterSpNow === v.id , 'btn-light': getterSpNow !== v.id }"
        		v-for="v in computed_sps" :key="v.id"
				@click="actionSetActiveSp(v.id)"
			>
				{{ v.head }}
				<!-- {{ v }} -->
			</button>
	</div>
</template>

<script>
// import { mapMutations } from "vuex";

import { mapGetters, mapActions } from 'vuex';

export default {
	data() {
		return {
			// loaded_sps: true
			//     activeSp: ""
			// title: "",
			// body: ""
		};
	},

	computed: {
		...mapGetters(['getterSps', 'getterSpNow']),
		// ...mapGetters([ "getterSpNow"]),
		// computed_get_now_sp() {
		//   return this.$store.getters.getterSpNow;
		// }
		computed_sps() {

			if( !this.getterSps.data )
			return false;

			return this.getterSps.data.filter(function(item) {
				if (item.head != 'default') {
					return true;
				} else {
					return false;
				}
			});

		},
	},

	methods: {
		...mapActions(['action_getJobDescSmens', 'actions_getItems', 'actionSetActiveSp']),
		// setNewSpNow: function(sp_id) {
		//   console.log("method", "setNewSpNow", sp_id);
		//   this.actionSetActiveSp(sp_id);
		// }
	},

	async mounted() {
		// this.$store.dispatch("fetchPosts");
		this.actions_getItems({ var1: 'sps', module: 'sale_point', sort: 'sort_asc' });

		// this.action_getJobDescSmens({ sp: this.sp, date_start: this.date_start })

		// this.getterSpNow();
		// this.activeSp = getterSpNow;
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
h4 {
	margin-right: 10px;
}
h4,
span {
	display: inline-block;
}
</style>
