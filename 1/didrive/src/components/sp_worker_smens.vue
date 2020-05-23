<template>
  <div>

<!-- 
    123123
<br />---11------

<div class="todo-item" v-for="item in todoList"></div>
---22---
      <div v-for="(v, k) in GET_TODO" :key="k">{{ k }} : {{ v }}</div>
----33---
      <div v-for="(v, k) in TODOS" :key="k">{{ k }} : {{ v }}</div>


<br />---------
 -->

    <!-- - {{ res.v1 ? res.v1 : "x" }} / {{ res.vv ? res.vv : "x2" }} // -->

    <br />----1-----
    {{ date_now }}
<br />----2-----
    <p v-if="loading">загружаем ...</p>
<br />----3-----
    <div v-for="(v1, k1, i) in otvet" :key="v1.id">
      {{ k1 }} : {{ v1 }} : {{ i }}
    </div>
<br />---------
    <!-- <div v-if="1 == 2 ">

      <br />
      {{ date_now }}

      <div v-for="(v1, k1) in otvet" :key="v1.id">
        {{ k1 }} : {{ v1 }}
      </div>

      ----------------

      < !-- <div v-for="(v, k) in res[1]" :key="k">{{ k }} : {{ v }}</div> -- >
      <div v-for="(v, k) in otvet" :key="k">{{ k }} : {{ v }}</div>

      ----------- -->
  </div>
</template>

<script>
import axios from "axios";

import Vuex from "vuex";
Vue.use(Vuex);

export default {
  data: function() {
    return {
      loading: true,
      date_now: "",
      otvet: [],
      res: null
    };
  }
//   ,
//   store
  ,
  props: {
    start: {
      type: String
    },

    sp: {
      type: String,
      required: true
    },

    jobman: {
      type: String,
      required: true
    },

    dnow: {
      type: String
      //   ,
      //   required: true
    },

    dstart: {
      type: String
      //   ,
      //   required: true
    },

    dfinish: {
     type: String
      //   ,
      //   required: true
    }
  },

  created: function() {
    this.loading = false;

    if (this.start) {
      axios
        .get("/vendor/didrive_mod/jobdesc/1/didrive/ajax.php", {
          params: {
            action: "ajax_in_smens_jm",
            jobman: this.jobman,
            date_start: this.dstart,
            date_finish: this.dfinish,
            sp: this.sp
          }
        })
        .then(res => {
          // this.$store.smens = res.data;
          // window.nyos_smens = res.data;
          this.otvet = res.data;
        });
    } else {
      // this.otvet = this.$store.smens;
      // this.otvet = window.nyos_smens;
      this.otvet = { sd: "sdfsd", we: "sdvsdv" };
    }
  }
  
//   ,
//   mounted() {
//     this.$store.dispatch("GET_TODO");
//   },
//   computed: {
//     todoList() {
//       return this.$store.getters.TODOS;
//     }
//   }

};
</script>

<style lang="scss" scoped>
div {
  padding: 5px;
  margin: 5px;
  border: 1px solid green;
}
</style>
