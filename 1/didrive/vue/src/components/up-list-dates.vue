<template>
  <div>
    <h4>список дат</h4>

    <span v-for="v in dates" :key="v">
      <button
        class="btn btn-sm border"
        :class="{ 'btn-success': getterDateNow === v }"
        click="setNewSpNow(v.id)"
        @click="actionSetActiveDate(v)"
      >
        {{ v }}
      </button>
    </span>
  </div>
</template>

<script>
// import { mapMutations } from "vuex";

import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      dates: [
        // "01-01-2020",
        // "01-02-2020",
        // "01-03-2020",
        "01-04-2020",
        "01-05-2020",
        "01-06-2020",
        "01-07-2020"
      ]
    };
  },

  computed: {
         // ...mapGetters(["getterSps", "getterSpNow"]),
    //     // ...mapGetters([ "getterSpNow"]),


    // computed_get_now_date() {
    //     console.log('computed_get_now_sp',this.$store.getters.getterDateNow);       
    //   return this.$store.getters.getterDateNow;
    // }

  ...mapGetters(["getterDateNow"]),


  },
  methods: {
    //     ...mapActions(["getItems", "actionSetActiveSp"]),
    ...mapActions(["actionSetActiveDate"]),
    // setNewDateNow: function(v) {
    //   console.log("method", "setNewDateNow", v);
    //   this.actionSetActiveDate(v);
    // }
  }

  //   async mounted() {
  //     // this.$store.dispatch("fetchPosts");
  //     // this.getItems({ var1: "sps", module: "sale_point" });
  //     // this.getterSpNow();
  //     // this.activeSp = getterSpNow;
  //   }

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

<style scoped></style>
