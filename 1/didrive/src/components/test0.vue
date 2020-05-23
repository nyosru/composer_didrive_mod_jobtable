<template>
    <template v-for="(article, index) in allArticles">

        <a :href="article.link">{{ article.title }}</a>
        <!-- Добавили поддержку лайков для каждой ссылки на материал. -->
        <span class="like" v-on:click="setLike(article.id)">Лайк</span>
        <span class="likeCount">{{ article.likesCount }}</span>

    </template>
</template>

  <script>
    import axios from 'axios'

    export default {
        data () {
            return {
                allArticles: [],
                countArticles: 5,// сколько материалов запрашивать на сервере
                error: ''
            }
        },

        methods: {

            async setLike: function (id) {
                try {
                    let data  = await axios.post( "http://my-site.com/set-like", {article-id: id});

                    this.allArticles = this.allArticles.map((art)=> {
                        if(art.id == id)
                             art.likesCount += 1;//или от сервера data.likesCount

                        return art;
                    });

                    if(data.status == 'success'){

                    } else {
                        throw new Error(data.status);
                    }

                } catch(e) {
                    this.error = e;
                }
            },


            async loadArticlesPreviewLinks(){
                 axios
                    .get("http://my-site.com/rand-articles/count/" + this.countArticles)
                    .then(response => {
                        this.allArticles = response.data;
                    })
                    .catch(function(e){
                        this.error = e;
                    });
            }

        },

        mounted: function() {
            this.loadArticlesPreviewLinks(); //загружаем все ссылки из сервера
        },

    }
  </script>

  <style lang="sass">
  span{ 
      border: 1ps colid gray;
      margin: 5px;
      padding: 5px;
  }
  </style>
  
  </style>
