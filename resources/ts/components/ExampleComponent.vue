
<template>
    <div>
        <div v-for="(parsedUserPost, index) in parsedUserPosts" :key="index">
            {{ parsedUserPost }}
        </div>
        <div v-for="(showId, index) in showIds" :key="index">
            {{ showId }}
        </div>
        <v-btn @click="update()">aaa</v-btn>
    </div>
</template>

<script>
export default {
    props: ['posts'],
    data(){
        return {
            allPosts: this.posts,
            ids: '',
        }
    },
    computed: {
        parsedUserPosts(){
            return this.allPosts;
        },
        showIds(){
            return this.ids;
        },
    },
    mounted(){
        var allPosts = this.allPosts;
        if(allPosts.length == 0){
            return false;
            } else {
            for(var i=0; i<allPosts.length; i++){
                allPosts[i].image = JSON.parse(allPosts[i].image);
            }
        }
        var abcs = allPosts.map((allpost) => {
            return allpost.image;
        })
        this.allPosts = abcs.map((abc) => {
            return abc.map((ab) => {
                return ab.src
            });
        })
        this.ids = allPosts.map((allPost) => {
            return allPost.id;
        })
        console.log(allPosts)
    },
    methods: {
        update(){
            axios.post('/api/jsonjson', {
                json: this.allPosts,
                id: this.ids,
            })
            .then(response => {
                console.log(response.data)
            })
            .catch(error => {
                console.log('fail')
            })
        }
    }
}
</script>