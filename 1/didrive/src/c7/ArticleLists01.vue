<template>
<ul class="article-lists">
   <li v-for="(article, index) in allArticles">
      <a :href="article.link">{{article.title}}</a>
   </li>
</ul>
</template>

<script>
import axios from 'axios'

export default {
    data () {
        return {
            allArticles: [],
            countArticles: 5,
            error: ''
        }
    }

    mounted: function() {
        axios
                .get("http://my-site.com/rand-articles/count/" + this.countArticles)
                .then(response => {
                    this.allArticles = response.data;
                })
                .catch(function(e){
                    this.error = e;
                });
    },

}
</script>