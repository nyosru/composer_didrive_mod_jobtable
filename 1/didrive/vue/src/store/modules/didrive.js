import store from '..';

export default {

	// namespaced: true,

	state: {
		// sale_point
		sps: [],
		sp_now: '',

		date_now: '',

		dolgnosti: [],
		dolgnost_now: '',

		dolgnosti_pays: [],
		// getter__where_jobs_jobman:[],
		where_jobs_jobman: []
	},

	actions: {

/**
 * тащим кто где работает на точке в выбранный месяц
 * @param {*} param0 
 * @param {*} ar 
 */
		async actions__getPeriodWhereJobMans(
			{ commit, getters, dispatch },
			ar = { sp: "", date: "" }
		  ) {
			//console.log('action', 'actions__getPeriodWhereJobMans', 'in', ar);
	  
			// let uri_dop = '';
	  
			// if (ar.sort) {
			// 	uri_dop += '&sort=' + ar.sort;
			// }
	  
			// if (typeof ar.sp !== 'undefined') {
			// 	uri_dop += '&sp=' + ar.sp;
			// }
			// if (typeof ar.date !== 'undefined') {
			// 	uri_dop += '&date=' + ar.date;
			// }
	  
			const domain1 =
			  process.env.NODE_ENV === "development"
				? "https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info"
				: "";
	  
			// const domain1 = 'https://adomik.dev.uralweb.info';
			console.log("domain dev", domain1);
	  
			ar["action"] = "getPeriodWhereJobMans";
	  
			// const res = await fetch( 'https://cors-anywhere.herokuapp.com/https://adomik.dev.uralweb.info/vendor/didrive_mod/items/1/didrive/ajax.php?_limit=' + limit )
			const res = await fetch(
			  // domain1 + '/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?' + uri_dop
			  domain1 + "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
			  {
				method: "POST",
				body: JSON.stringify(ar)
			  }
			);
	  
			// const res1 = await res;
			// const data = res1.json();
			const data = await res.json();
	  
			console.log("action", "actions__getPeriodWhereJobMans", "in2", ar);
			// console.log('action', 'actions__getPeriodWhereJobMans', 'res1', res1);
			console.log("action", "actions__getPeriodWhereJobMans", "res data", data);
			// console.log('action', 'actions__getPeriodWhereJobMans', 'uri_dop', uri_dop);
	  
			//     dispatch('sayHello')
	  
			//     commit('updatePosts', posts)
			commit("updateItems", { var: ar.var1, data: data });
		  },
	  

		/**
		 * изменяем статус записи
		 * @param {*} param0
		 * @param {*} ar
		 * { item_id: "", status_new: "", s:'' }
		 */
		async actionChangeStatus(
			{ commit, getters, dispatch },
			ar = { item_id: '', status_new: '', s: '', resresh_module: '' }
		) {
			console.log('actionChangeStatus', ar);

			const domain1 =
				process.env.NODE_ENV === 'development'
					? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
					: '';
			console.log('domain dev', domain1);

			// const res = await fetch( 'https://cors-anywhere.herokuapp.com/https://adomik.dev.uralweb.info/vendor/didrive_mod/items/1/didrive/ajax.php?_limit=' + limit )
			const res = await fetch(
				domain1 +
					'/vendor/didrive_mod/items/1/ajax.php?' +
					'action=edit_pole&' +
					'pole=status&' +
					'val=' +
					ar.status_new +
					'&' +
					'id=' +
					ar.item_id +
					'&' +
					's=' +
					ar.s
				// +'&'
			);
			const data = await res.json();

			console.log('res', data);

			// commit('updateItems', { var: ar.var1, data: data });

			// if (typeof ar.resresh_module !== 'undefined' && ar.resresh_module == 'dolgnosti_pays' ){
			//   console.log('обновляем модуль ' + delete_data_module);
			//   this.actions_getItems({ var1: 'dolgnosti_pays', module: '071.set_oplata', sort: 'date_desc', show : 'all' });
			// }
		},

		/**
		 * добавляем итем
		 */
		async action__addNewItem({ commit, getters, dispatch }, ar = { module: '', data: {} }) {
			console.log('action__addNewItem', 'start');

			// let put_uri = '';

			// for (let kk in ar.data) {
			// 	if (ar.data[kk] == null) continue;
			// 	if (put_uri != '') put_uri += '&';
			// 	put_uri += 'add[' + kk + ']=' + ar.data[kk];
			// }

			// console.log( 'строка запроса', put_uri);
			// //return false;
			// console.log('actionChangeStatus', ar);

			const domain1 =
				process.env.NODE_ENV === 'development'
					? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
					: '';
			console.log('domain dev', domain1);

			// let uri = domain1 + '/vendor/didrive_mod/items/1/ajax.php?' + put_uri;
			// console.log('uri', uri );

			// const res = await fetch( uri );

			let uri = domain1 + '/vendor/didrive_mod/items/1/ajax.php';
			const res = await fetch(uri, {
				method: 'POST',
				// headers: { 'Content-Type': 'application/json' },
				headers: { 'Content-Type': 'multipart/form-data' },
				body: JSON.stringify({
					add_module: ar.module,
					sys: 'vue',
					action: 'add_new',
					add: ar.data,
				}),
			});
			const data = await res.json();

			console.log('res', data);

			// commit('updateItems', { var: ar.var1, data: data });

			console.log('action__addNewItem', 'finish');

			return data;
		},

		/**
		 * получаем данные с бд
		 * @param {*} param0
		 * @param {*} ar
		 */
		async actions_getItems({ commit, getters, dispatch }, ar = { var1: '', module: '', sort: '', show: 'all' }) {
			console.log('action', 'getItems', 'in', ar);

			let uri_dop = '&add_secret=da';

			if (ar.sort) {
				uri_dop += '&sort=' + ar.sort;
			}

			if (typeof ar.show !== 'undefined') {
				uri_dop += '&status=' + ar.show;
			} else if (ar.show == 'all') {
				uri_dop += '&status=0';
			} else {
				uri_dop += '&status=show';
			}

			const domain1 =
				process.env.NODE_ENV === 'development'
					? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
					: '';
			console.log('domain dev', domain1);

			// const res = await fetch( 'https://cors-anywhere.herokuapp.com/https://adomik.dev.uralweb.info/vendor/didrive_mod/items/1/didrive/ajax.php?_limit=' + limit )
			const res = await fetch(
				domain1 + '/vendor/didrive_mod/items/1/ajax.php?' + 'action=get&module=' + ar.module + uri_dop
			);
			const data = await res.json();

			console.log('action', 'getItems', 'in2', ar);
			console.log('action', 'getItems', 'res', data);
			console.log('action', 'getItems', 'uri_dop', uri_dop);

			//     dispatch('sayHello')

			//     commit('updatePosts', posts)
			commit('updateItems', { var: ar.var1, data: data });
		},

		async aсtions__sendPaysToSps({ commit }, ar = { item_id: '', to_sps: [], s: '' }) {

			console.log('aсtions__sendPaysToSps', ar);




			const domain1 =
				process.env.NODE_ENV === 'development'
					? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
					: '';
			console.log('domain dev', domain1);

			// let uri = domain1 + '/vendor/didrive_mod/items/1/ajax.php?' + put_uri;
			// console.log('uri', uri );

			// const res = await fetch( uri );

			let uri = domain1 + '/vendor/didrive_mod/items/1/ajax.php';
			const res = await fetch(uri, {
				method: 'POST',
				// headers: { 'Content-Type': 'application/json' },
				headers: { 'Content-Type': 'multipart/form-data' },
				body: JSON.stringify({
					// add_module: ar.module,
					item_id: ar.item_id,
					sys: 'vue',
					action: 'pays_copy_to_sps',
					to_sps: ar.to_sps,
				}),
			});
			const data = await res.json();

			console.log('res', data);






			// return false;

			// let uri_dop = '&add_secret=da';

			// if (ar.sort) {
			// 	uri_dop += '&sort=' + ar.sort;
			// }

			// const domain1 =
			// 	process.env.NODE_ENV === 'development'
			// 		? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
			// 		: '';
			// console.log('domain dev', domain1);

			// // const res = await fetch( 'https://cors-anywhere.herokuapp.com/https://adomik.dev.uralweb.info/vendor/didrive_mod/items/1/didrive/ajax.php?_limit=' + limit )
			// const res = await fetch(
			// 	domain1 +
			// 		'/vendor/didrive_mod/items/1/ajax.php?' +
			// 		'action=edit_pole' +
			// 		'&item=' +
			// 		item_id +
			// 		'&status_new=' +
			// 		status_new +
			// 		'&s=' +
			// 		s
			// );
			// const data = await res.json();

			// console.log('action', 'getItems', 'in2', ar);
			// console.log('action', 'getItems', 'res', data);

			// //     dispatch('sayHello')

			// //     commit('updatePosts', posts)
			// commit('updateItems', { var: ar.var1, data: data });
		},

		async actions_editStatusItem({ commit }, ar = { item_id: '', status_new: '', s: 'secret' }) {
			console.log('actions_editStatusItem', ar);

			let uri_dop = '&add_secret=da';

			if (ar.sort) {
				uri_dop += '&sort=' + ar.sort;
			}

			const domain1 =
				process.env.NODE_ENV === 'development'
					? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
					: '';
			console.log('domain dev', domain1);

			// const res = await fetch( 'https://cors-anywhere.herokuapp.com/https://adomik.dev.uralweb.info/vendor/didrive_mod/items/1/didrive/ajax.php?_limit=' + limit )
			const res = await fetch(
				domain1 +
					'/vendor/didrive_mod/items/1/ajax.php?' +
					'action=edit_pole' +
					'&item=' +
					item_id +
					'&status_new=' +
					status_new +
					'&s=' +
					s
			);
			const data = await res.json();

			console.log('action', 'getItems', 'in2', ar);
			console.log('action', 'getItems', 'res', data);

			//     dispatch('sayHello')

			//     commit('updatePosts', posts)
			commit('updateItems', { var: ar.var1, data: data });
		},

		//   sayHello() {}
		// actionSeActiveSps({ commit, getters, dispatch }, sp_id) {
		actionSetActiveSp({ commit }, sp_id) {
			console.log('action', 'actionSetActiveSp', sp_id);
			commit('setActiveSp', sp_id);
			// commit("setActiveSp", sp_id);
		},

		actionSetActiveDate({ commit }, date) {
			console.log('action', 'actionSetActiveDate', date);
			commit('setActiveDate', date);
			// commit("setActiveSp", sp_id);
		},

		/**
		 * тащим смены норм и спец назначения за период  date_start date_finish
		 */
		async action_getJobDescSmens({ commit, getters, dispatch }, ar = { uri_dop: '' }) {
			console.log('action_getJobDescSmens', 'start');

			let uri_dop = 't=1';

			if (ar.uri_dop) {
				uri_dop += ar.uri_dop;
			}

			const domain1 =
				process.env.NODE_ENV === 'development'
					? 'https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info'
					: '';
			console.log('domain dev', domain1);

			// const res = await fetch( 'https://cors-anywhere.herokuapp.com/https://adomik.dev.uralweb.info/vendor/didrive_mod/items/1/didrive/ajax.php?_limit=' + limit )
			const res = await fetch(
				domain1 + '/vendor/didrive_mod/jobdesc/1/didrive/ajax.vue.php?' + 'action=get_jobs_norm&' + uri_dop
			);
			const data = await res.json();

			// var status = function (response) {  if (response.status !== 200) {    return Promise.reject(new Error(response.statusText))  }  return Promise.resolve(response)}
			// var json = function (response) {  return response.json()}
			// fetch('https://cors-anywhere.herokuapp.com/http://adomik.dev.uralweb.info/vendor/didrive_mod/jobdesc/1/didrive/ajax.vue.php', ).then(status).then(json)
			//   .then(function (data) {
			//     console.log('data', data)
			//   })
			//   .catch(function (error) { console.log('error', error) })

			console.log('action_getJobDescSmens', 'data', data);

			// //     dispatch('sayHello')

			// //     commit('updatePosts', posts)
			commit('updateItems', { var: 'smens', data: data });
		},

		actionSet({ commit }, ar = { var: '', data: {} }) {
			console.log('action', 'actionSet', ar);
			commit('updateItems', ar);
			// commit("setActiveSp", sp_id);
		},
	},

	mutations: {
		updateItems(state, ar = { var: '', data: {} }) {
			console.log('mutation', 'updateItems / var > data', ar.var, ar.data);
			state[ar.var] = ar.data;
		},

		setActiveDate(state, date) {
			console.log('mutation', 'setActiveDate', date);
			state.date_now = date;
		},
		setActiveSp(state, sp_id) {
			console.log('mutation', 'setActiveSp', sp_id);
			state.sp_now = sp_id;

			// state.sps.push({ message: 'Baz' })
			// state.obj = state.obj.map();
			// console.log('state.sp_now',state.sp_now);
			// state['sps']['data']['x'] = [ 'id' : 'xede' ];
			// console.log('state.sps',state.sps);
			// state[ar.var] = ar.data;
		},

		//   updatePosts(state, posts) {
		//     state.posts = posts
		//   },
		//   createPost(state, newPost) {
		//     state.posts.unshift(newPost)
		//   }
	},

	getters: {
		//   validPosts(state) {
		//     return state.posts.filter(p => {
		//       return p.title && p.body
		//     })
		//   },
		getterSps(state) {
			return state['sps'];
		},
		getterSpNow(state) {
			return state.sp_now;
		},
		getterDateNow(state) {
			return state.date_now;
		},
		getterDolgnosti(state) {
			console.log('getterDolgnosti', state['dolgnosti']);
			return state['dolgnosti'];
		},
		getterDolgnostNow(state) {
			console.log('getterDolgnostNow', state['dolgnost_now']);
			return state['dolgnost_now'];
		},

		// getterItems(state, mod) {
		//   console.log("getterItems", "mod", mod);
		//   if (mod && state[mod]) {
		//     console.log("getterItems", "есть данных");
		//     return state[mod];
		//   } else {
		//     console.log("getterItems", "нет данных");
		//     return false;
		//   }
		// },

		//   postsCount(state, getters) {
		//     return getters.validPosts.length
		//   }

		getterDolgnosti(state) {
			return state['dolgnosti'];
		},
		getterDolgnostNow(state) {
			return state['dolgnost_now'];
		},

		getterDolgnostiPays(state) {
			console.log('getterDolgnostiPays', state['dolgnosti_pays']);
			return state['dolgnosti_pays'];
		},

		// тащщим данные пользователей что работают на точке в этом месяце
		getter__where_jobs_jobman(state) {
			if (process.env.NODE_ENV === "development")
			  console.log("getter__where_jobs_jobman", state.where_jobs_jobman);
			// return [1,2,3,4];
			return state.where_jobs_jobman;
		  }
	  

	},
};
