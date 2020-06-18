<template>
	<div id="app">
		<div class="container">
			<!-- <div class="col-xs-12 col-md-6 col-sm-6 col-xl-6 col-lg-6"> -->
			<div class="col-xs-12">
				<up-list-sps></up-list-sps>
				<up-list-dates></up-list-dates>
				<!-- <up-list-dolgnosti></up-list-dolgnosti> -->
				

				keypole: {{ keypole }} 
				<Br />
				<jobdesc-table
						:key="keypole"
						:date="getterDateNow"
						:sp="getterSpNow"
					></jobdesc-table>

				<!-- 1 - {{ getterDolgnostNow }} -->

				<!-- <br />
				<dolgnosti-pays
					v-if="keypole"
					:key="keypole"
					:sp="getterSpNow"
					:dolgnost="getterDolgnostNow"
				></dolgnosti-pays> -->
			</div>

			<!-- <div class="col-xs-12 col-md-6 col-sm-6 col-xl-6 col-lg-6">
        < !-- <PostForm />
        <h1>{{ postsCount }}</h1>
        <div class="post" v-for="post in validPosts" :key="post.id">
          <h2>{{ post.title }}</h2>
          <p>{{ post.body }}</p>
        </div> -- >
      </div> -->
		</div>
	</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

// import PostForm from "./components/PostForm";
import upListSps from './components/up-list-sps';
import upListDates from "./components/up-list-dates";
// import upListDolgnosti from './components/up-list-dolgnosti';

import jobdescTable from "./components/jobdesc-table";
// import DolgnostiPays from './components/dolgnosti-pays';


export default {
	name: 'app',

	components: {
		// PostForm,
		upListSps,
		upListDates,
		// upListDolgnosti,
		jobdescTable,
		// DolgnostiPays,
	},

	// data:{
	//   activeSp: ''
	// },

	created() {
		//  ...mapGetters([
		//    'getterDolgnostiPays'
		//   'getterDolgnostNow',
		//   "getterSpNow"
		// ]),

		//get_pays();

		// this.actions_getItems({ var1: 'dolgnosti_pays', module: '071.set_oplata', sort: 'date_desc', show: 'all' });
	},

	// computed: mapGetters(["validPosts", "postsCount", "getterSpNow"]),
	computed: {
		// computed_get_now_sp() {
		//   return this.$store.getters.getterSpNow;
		// },
		// computed_get_now_date() {
		//   console.log("computed_get_now_sp", this.$store.getters.getterDolgnostNow);
		//   return this.$store.getters.getterDolgnostNow;
		// }

		...mapGetters([
			// 'getterDolgnostNow',
			'getterSpNow',
			'getterDateNow',
			// 'getterDolgnostiPays'
		]),

		keypole() {

			// if (this.getterSpNow && this.getterDolgnostNow) {
			// 	return this.getterSpNow + this.getterDolgnostNow;
			// } else 
			if (this.getterSpNow && this.getterDateNow) {
				return this.getterSpNow + this.getterDateNow;
			} else if (this.getterSpNow) {
				return this.getterSpNow;
			} else {
				return false;
			}
		},

		// get_pays() {
		// 	this.actions_getItems({ var1: 'dolgnosti_pays', module: '071.set_oplata', sort: 'date_asc' });
		// },
	},
	// methods: mapActions(["fetchPosts"]),

	methods: {
		...mapActions(['actions_getItems']),
	},

	async mounted() {
		// this.$store.dispatch("fetchPosts");
		// this.fetchPosts();
		// if (!this.getterItems("dolgnosti")) {
		//   console.log("запускаем сбор должностей");
		//   this.getItems({
		//     var1: "dolgnosti",
		//     module: "061.dolgnost",
		//     xsort: "sort_asc"
		//   });
		// }
		// if (!this.getterItems("pays")) {
		// if (!getterDolgnostiPays) {
		//   console.log("запускаем сбор размера зарплат");
		//   this.getItems({
		//     var1: "dolgnosti_pays",
		//     module: "071.set_oplata",
		//     xsort: "sort_asc"
		//   });
		// }
		// if (!this.getterItems("metki")) {
		//   console.log("запускаем сбор metki");
		//   this.getItems({
		//     var1: "metki",
		//     module: "072.metki",
		//     xsort: "sort_asc"
		//   });
		// }
	},
};
</script>

<style>
/* @import url(https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css); */

#app {
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #2c3e50;
	/*
  xmargin: 60px auto;
  xwidth: 400px;
  */
}

.post {
	border: 1px solid #ccc;
	border-radius: 5px;
	margin-bottom: 1rem;
}
</style>
