
// import Vue from 'vue'
// import Vuex from 'vuex'

// Vue.use(Vuex);

const app_ocenka = new Vue({

    el: '#app_ocenka',

    data: {
        posts: []
    },

    created() {

        fetch('https://jsonplaceholder.typicode.com/posts/1')

            .then((response) => {

                if (response.ok) {
                    return response.json();
                }

                throw new Error('Network response was not ok');

            })

            .then((json) => {
                this.posts.push({
                    title: json.title,
                    body: json.body
                });
            })

            .catch((error) => {
                console.log(error);
            });

    }
});

const app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        now: new Date()
    },
    methods: {
        updateDate() {
            this.now = new Date();
        }
    },
    mounted() {
        setInterval(() => {
            this.updateDate();
        }, 1000);
    }
})