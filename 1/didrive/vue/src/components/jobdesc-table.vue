<template>
  <div>
    <h4>вывод таблицы</h4>
    sp {{ getterSpNow }} + date {{ getterDateNow }}

    <!-- <div class="trow alert-warning"  > -->

    <div class="ttable">

      <div class="trow">
        <div class="tcell" v-for="k in 32">

          <b v-if="k == 1" >точка продаж</b>

        </div>
      </div>

      <div
        class="trow"
        v-for="(v0, user_id) in getter__where_jobs_jobman.data"
        :key="user_id"
      >
        <!-- {{ getterDolgnosti }} -->
        <div class="tcell">
            <!-- {{ v }} -->
            <small> сотрудник {{ v0[0]['jobman'] }} </small>
            <br />
            <!-- должность {{ v0[0].dolgnost }} /  -->
            {{
              getterDolgnosti.data[v0[0]['dolgnost']]["head"]
                ? getterDolgnosti.data[v0[0]['dolgnost']]["head"]
                : "#" + v0[0]['dolgnost']
            }}
            <br />
            c {{ v0[0]['date'] }}
        </div>

        <template v-for="k3 in 31">
          <div class="tcell">{{ k3 }}</div>
        </template>
      </div>
    </div>

    <!-- {{ getter__where_jobs_jobman }} -->

    <!--
    список точек продаж

    <span v-for="(v, k) in getterSps.data" :key="v.id">
      <button
        class="{ success: getterSpNow === v.id }"
        v-if="v.head"
        @click="setNewSpNow(v.id)"
      >
        {{ v.head }} / {{ k }}
      </button>
    </span>
 -->
    <!-- 
<br/>
now: {{ activeSp }}
 -->
    <br />
    <!-- -- {{ getterItems('sps') }} --  -->
    <!-- 
<br/>
// {{ sps }} // -->
  </div>
  <!-- <form @submit.prevent="submit">
    <input type="text" placeholder="title" v-model="title">
    <input type="text" placeholder="body" v-model="body">
    <button type="submit">Create Post</button>
    <hr>
  </form> -->
</template>

<script>
// import { mapMutations } from "vuex";

import { mapGetters, mapActions } from "vuex";

// console.log('table_start');

export default {
  // data() {
  //   return {
  //     activeSp: ""
  // title: "",
  // body: ""
  //   };
  // },

  props: ["sp", "date", "date_start", "date_finish"],

  //computed: mapGetters(["getterSps", "getterSpNow"]),

  created() {
    console.log("jobDesc-table", "cr");
  },

  computed: {
    ...mapGetters([
      "getterSpNow",
      "getterDateNow",
      //   "getterSps",
      //   "getterSpNow"
      "getter__where_jobs_jobman",
      "getterDolgnosti"
    ])

    // ...mapActions(['actions__getPeriodWhereJobMans']),

    // computed_get_now_sp() {
    //   return this.$store.getters.getterSpNow;
    // },
    // computed_get_now_date() {
    //     console.log('computed_get_now_sp',this.$store.getters.getterDateNow);
    //   return this.$store.getters.getterDateNow;
    // }
  },

  methods: {
    // ...mapActions(['action_getJobDescSmens']),
    // ...mapActions(["getItems", "actionSetActiveSp"]),
    // ...mapActions(['action_getJobDescSmens']),
    // setNewSpNow: function(sp_id) {
    //   console.log("method", "setNewSpNow", sp_id);
    //   this.actionSetActiveSp(sp_id);
    // }

    ...mapActions([
      "actions_getItems",
      // "getItems",
      "getterItems",
      "action_getJobDescSmens",
      "actions__getPeriodWhereJobMans"
    ])
  },

  async mounted() {
    // this.$store.dispatch("fetchPosts");
    // this.getItems({ var1: "sps", module: "sale_point" });
    // this.getterSpNow();
    // this.activeSp = getterSpNow;

    // this.action_getJobDescSmens( { 'date_start' : getterDateNow } )

    if (this.date && this.sp) {
      // if( getterSpNow && getterDateNow ){

      // this.getItems({ var1: "dolgnost", module: "" });

      this.actions_getItems({
        var1: "dolgnosti",
        module: "061.dolgnost",
        sort: "sort_desc"
      });

      this.actions__getPeriodWhereJobMans({
        var1: "where_jobs_jobman",
        sp: this.sp,
        date: this.date
      });

      console.log("тащим данные по новой", this.date);
      //this.action_getJobDescSmens({ 'uri_dop' : '&date_start='+this.date_start });

      // console.log("запускаем сбор plus");
      // this.getItems({
      //   var1: "plus",
      //   module: "072.plus",
      //   xsort: "sort_asc",
      //   dop: '&sp='+this.sp+'&date='+this.date_start
      // });
    }
  }

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
button {
  padding: 5px 10px;
  margin: 2px;
  cursor: pointer;
  border: 1px solid gray;
}
.success {
  background-color: rgba(0, 255, 0, 0.2);
}

.ttable {
  display: table;
}
.trow {
  display: table-row;
}
.tcell {
  display: table-cell;
  min-width: 120px;
  border: 1px solid gray;
}
</style>
