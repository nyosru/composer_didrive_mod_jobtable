import Vue from "vue";
import Vuex from "vuex";

import post from "./modules/post";
import didrive from "./modules/didrive";
// import dolgnostiPays from "./modules/dolgnosti-pays";
import jobdesc from "./modules/jobdesc";

Vue.use(Vuex);

export default new Vuex.Store({

  // data: {
  //   sp_now: "",
  //   sps: {}
  // },

  modules: {
    post,
    didrive,
    // dolgnostiPays
    jobdesc: jobdesc,
  }
});


/*
var limit = 24 * 3600 * 1000; // 24 часа
var localStorageInitTime = localStorage.getItem('localStorageInitTime');
if (localStorageInitTime === null) {
    localStorage.setItem('localStorageInitTime', +new Date());
} else if(+new Date() - localStorageInitTime > limit)
    localStorage.clear();
    localStorage.setItem('localStorageInitTime', +new Date());
}
*/