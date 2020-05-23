// Vue.js
window.Vue = require("vue");

// import Vuex from "vuex";
// Vue.use(Vuex);

// const store = new Vuex.Store({
//   state: {
//     smens: {},
//     smens_run: 0
//   },

//   mutations: {
//     get(state) {
//       // state.count++;
//       state.smens_run++;
//     }
//   }
// });




// import {store} from './store';


Vue.component("sp_ocenki", require("./components/sp_ocenki.vue").default);
Vue.component("sp_timeo", require("./components/sp_timeo.vue").default);
Vue.component(  "sp_worker_smens",  require("./components/sp_worker_smens.vue").default );

// var $sp_worker_smens = require('./components/sp_worker_smens.vue')
// var SpTimeo = require('./components/sp_timeo.vue').default

const app = new Vue({
  el: "#body1",

  // store
  // ,
  // render: h => h(App)

  // ,
  // components:{

  //   sp_ocenki : () => import("./sp_ocenki.vue"),
  //   sp_timeo : () => import("./sp_timeo.vue"),
  //   sp_worker_smens : () => import("./sp_worker_smens.vue")

  // }
});

// import 'bootstrap';
// import 'bootstrap/dist/css/bootstrap.min.css';

// //import 'jquery';
// //import 'popper.js';

// // JS
// // import './js/'

// // SCSS
// // import './assets/scss/main.scss'

// // CSS (example)
// // import './assets/css/main.css'

// // Vue components (for use in html)
// Vue.component('example-component', require('./components/Example.vue').default)

// Vue.component('workman_ocenki', require('./components/workman_ocenki.vue').default)

// // // Vue init

// const app = new Vue({
//   el: '#app'
// //   ,
// //   data: {
// //     posts: []
// //   }
// //   ,
// //   created() {
// //     fetch('https://jsonplaceholder.typicode.com/posts/1')
// //       .then((response) => {
// //         if (response.ok) {
// //           return response.json();
// //         }

// //         throw new Error('Network response was not ok');
// //       })
// //       .then((json) => {
// //         this.posts.push({
// //           title: '123 ' + json.title,
// //           body: '123 ' + json.body
// //         });
// //       })
// //       .catch((error) => {
// //         console.log(error);
// //       });
// //   }

// })

// // alert('111234');

// // $(document).ready(function (re) {

// //   $('#td2').html('background');
// //   alert('234');

// // })
