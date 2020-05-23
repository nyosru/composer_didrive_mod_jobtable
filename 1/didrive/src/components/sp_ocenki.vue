<template>
  <div
    class="trow"
    data-aos="fade-right"
    data-aos-anchor-placement="bottom-bottom"
  >
    <div class="tcell stick-left bg-white">
      <b>Оценка дня</b>
    </div>

    <div
      class="tcell tr text-center ras"
      v-for="(now_ocenka, a1_date) in ar_ocenka__date_d.res"
      v-bind:item="now_ocenka"
      v-bind:index="a1_date"
      v-bind:key="now_ocenka.id"
    >
      <!-- --{{ show_new_ocenka_txt }}==<Br/> -->

      <p v-if="loading">загружаем ...</p>

      <div>
        <div v-bind:class="{ new_ocenka_active: now_ocenka.txt }">
          <span
            class="btn_refresh"
            v-on:click="refresh(sp, a1_date, a1_date)"
            :title="
              'обновить оценку ( ' +
                a1_date +
                ' / ' +
                sp +
                ' / ' +
                now_ocenka.s +
                ' )'
            "
          >
            <!--
        <span class="'fa fa-refresh'"></span>
        -->
            &#8635;
          </span>

          <nobr>
            <div v-bind:class="'ocenka_show all' + now_ocenka.ocenka">
              Оценка дня: {{ now_ocenka.ocenka }}
            </div>
            <br />
            <abbr
              v-bind:title="'Оценка по обороту'"
              v-bind:class="'ocenka_show ob' + now_ocenka.ocenka_oborot"
              >{{ now_ocenka.ocenka_oborot }}</abbr
            >
            <abbr
              v-bind:title="'Оценка по времени ожидания'"
              v-bind:class="'ocenka_show time' + now_ocenka.ocenka_time"
              >{{ now_ocenka.ocenka_time }}</abbr
            >
            <abbr
              v-bind:title="'На 1 руки'"
              v-bind:class="'ocenka_show naruki' + now_ocenka.ocenka_naruki"
              >{{ now_ocenka.ocenka_naruki }}</abbr
            >
          </nobr>
        </div>

        <div v-if="now_ocenka.txt" class="ocenka-new">
          <span v-html="now_ocenka.txt"></span>
        </div>

        <!-- <div
            v-for="(v2, a_date) in now_ocenka"
            v-bind:item="v2"
            v-bind:index="a_date"
            v-bind:key="a_date"
          >
            {{ a_date }} : {{ v2 }}

          </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      loading: false,
      ar_ocenka__date_d: null
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
    this.loading = true;

    axios
      // .get("https://jsonplaceholder.typicode.com/posts/2/comments")
      .get(
        // "https://adomik.uralweb.info/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?action=timeo_show_vars&d[1][sp]=1&d[1][date]=2020-05-01&d[2][date]=2020-06-01&d[2][sp]=1"
        "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
        {
          params: {
            action: "show_vars_ocenki",
            date_start: this.date_start,
            date_finish: this.date_finish,
            sp: this.sp
          }
        }
      )
      .then(res => {
        this.loading = false;
        this.ar_ocenka__date_d = res.data;
      });
  },

  methods: {
    refresh: function(a_sp, a_date, a_s) {
      axios
        // .get("https://jsonplaceholder.typicode.com/posts/2/comments")
        .get(
          // "https://adomik.uralweb.info/vendor/didrive_mod/jobdesc/1/didrive/ajax.php?action=timeo_show_vars&d[1][sp]=1&d[1][date]=2020-05-01&d[2][date]=2020-06-01&d[2][sp]=1"
          "/vendor/didrive_mod/jobdesc/1/didrive/ajax.php",
          {
            params: {
              // action: "show_vars_ocenki",
              // date_start: this.date_start,
              // date_finish: this.date_finish,
              // sp: this.sp

              action: "calc_full_ocenka_day",
              id: a_sp,
              id2: a_sp,
              s: a_s,
              s2: a_s,
              show_timer: "da",
              sp: a_sp,
              date: a_date
            }
          }
        )
        .then(res => {
          // this.loading = false;
          // console.log("кликнули кнопу обновить","получили ответ от запроса", res.data)
          this.ar_ocenka__date_d.res[a_date] = res.data.data;
          this.ar_ocenka__date_d.res[a_date]["id"] = "new";
        })
        .catch(function(e) {
          console.log(
            "кликнули кнопу обновить",
            "получили ответ от запроса",
            "ошибка",
            e
          );
          this.error = e;
        });
    }
  }

  // mounted: function() {
  //   this.loadArticlesPreviewLinks(); //загружаем все ссылки из сервера
  // }
};
</script>

<style scoped>
.ocenka-new {
  padding: 5px;
  background-color: rgba(0, 255, 255, 0.1);
  min-width: 200px;
  animation: all 2s infinite ease-in-out;
}
.new_ocenka_active {
  background-color: rgba(0, 255, 0, 0.2);
  padding: 10px;
  border-radius: 5px;
}
.btn_refresh {
  float: right;
  font-weight: bold;
  cursor: pointer;
}
</style>
