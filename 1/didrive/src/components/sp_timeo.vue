<template>
  <div
    class="trow"
    data-aos="fade-right"
    data-aos-anchor-placement="bottom-bottom"
  >
    <div class="tcell stick-left bg-white">
      <b>Время ожидания</b>
    </div>

    <div
      class="tcell tr text-center ras"
      v-for="timer_d in result1.res"
      :key="timer_d.id"
    >
      <!-- v-bind:item="now_ocenka"
      v-bind:index="a1_date"
    > -->
      <p v-if="loading">загружаем ...</p>

      <nobr>
        <Abbr title="Холодный цех" v-if="timer_d.cold != 0">{{
          timer_d.cold
        }}</Abbr>
        <Abbr title="Холодный цех" v-else>-</Abbr>
        /
        <Abbr title="Горячий цех" v-if="timer_d.hot != 0">{{
          timer_d.hot
        }}</Abbr>
        <Abbr title="Горячий цех" v-else>-</Abbr>
        /
        <Abbr title="Доставка" v-if="timer_d.delivery != 0">{{
          timer_d.delivery
        }}</Abbr>
        <Abbr title="Доставка" v-else>-</Abbr>
      </nobr>

      <div v-if="1 == 2">
        <div v-for="(v2, a_date) in timer_d" :key="a_date">
          {{ a_date }} : {{ v2 }}

          <div v-if="1 == 2">
            <div style=" padding:30px; ">
              <div v-for="(v3, k3) in v2" :key="v3" >
                {{ k3 }} : {{ v3 }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data: function() {
    return {
      loading: false,
      result1: null
    };
  },

  props: {
    sp: {
      type: String,
      required: true
    },
    date_start: {
      type: String,
      required: true
    },
    date_finish: {
      type: String,
      required: true
    }
  },

  created: function() {
    console.log("sp_ocenki", "created", 1);

    this.loading = true;

    axios
      // .get("https://jsonplaceholder.typicode.com/posts/2/comments")
      .get(
        // "https://adomik.uralweb.info/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?action=timeo_show_vars&d[1][sp]=1&d[1][date]=2020-05-01&d[2][date]=2020-06-01&d[2][sp]=1"
        "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
        {
          params: {
            action: "timeo_show_vars2",
            // action: "show_vars_ocenki",
            date_start: this.date_start,
            date_finish: this.date_finish,
            sp: this.sp
          }
        }
      )
      .then(res => {
        this.loading = false;
        this.result1 = res.data;
      });

    console.log("sp_ocenki", "created", 2);

    // this.pos = {
    //   in: {
    //     action: "show_vars_ocenki",
    //     date_start: "2020-05-01",
    //     date_finish: "2020-05-31",
    //     sp: "3125",
    //     add_list_date: "da"
    //   },
    //     "2020-05-14": {
    //       id: "100948",
    //       head: "1",
    //       sort: "50",
    //       status: "show",
    //       date: "2020-05-14",
    //       sale_point: "3125",
    //       ocenka_time: "5",
    //       ocenka_naruki: "5",
    //       ocenka: "5"
    //     },
    //     "2020-05-15": { skip: "da" }
    //   },
    //   status: "ok",
    //   html: "\u043e\u043a"
    // };
  }
};
</script>

<!-- style lang="scss" scoped>
.icon_refresh_ocenka {
  display: block;
  float: right;
}
div{ padding: 5px; margin: 5px; border: 1px solid green; }
</style -->
