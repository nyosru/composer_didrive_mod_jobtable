<template>
	<div style="text-align: left;">
		<h4>Должности</h4>

		<span v-if="!getterDolgnosti.data">Загружаю ...</span>

		<button
			class="btn btn-xs "
			:class="{ 'btn-success': getterDolgnostNow === 'all' || !getterDolgnostNow }"
			@click="actionSet({ var: 'dolgnost_now', data: 'all' })"
		>
			Все
		</button>

		<button
			class="btn btn-xs"
			:class="{ 'btn-success': getterDolgnostNow === v.id, 'btn-light': getterDolgnostNow !== v.id }"
			v-for="v in getterDolgnosti.data"
			:key="v.id"
			@click="actionSet({ var: 'dolgnost_now', data: v.id })"
		>
			{{ v.head }}
		</button>

	</div>
</template>

<script>
// import { mapMutations } from "vuex";

import { mapGetters, mapActions } from 'vuex';

export default {
	data() {
		return {};
	},

	computed: {
		...mapGetters(['getterDolgnosti', 'getterDolgnostNow']),
	},

	methods: {
		...mapActions(['actionSet', 'action_getJobDescSmens', 'actions_getItems', 'actionSetActiveSp']),
	},

	async mounted() {
		// this.getItems({ var1: "sps", module: "sale_point", sort: "sort_asc" });
		//this.action_getJobDescSmens({ sp: this.sp, date_start: this.date_start })
		this.actions_getItems({ var1: 'dolgnosti', module: '061.dolgnost', sort: 'sort_desc' });
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
/* button {
  padding: 5px 10px;
  margin: 2px;
  cursor: pointer;
  border: 1px solid gray;
} */
/* .success {
  background-color: rgba(0, 255, 0, 0.2);
} */
</style>
