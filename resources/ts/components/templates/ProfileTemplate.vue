<template>
    <div v-resize="onResize">
        <profile-card :mainUser="mainUser" :requestedUser="requestedUser"></profile-card>
        <keep-alive>
        <template v-if="value == 0">
            <user-posts :mainUser="mainUser" :requestedUser="requestedUser" :isMainUser="isMainUser"></user-posts>
        </template>
        <!-- <template v-else-if="value == 1">
            <like-posts :mainUserPostLikes="mainUserPostLikes" :likedPosts="likedPosts" :postLikes="postLikes" :postLikeUsers="postLikeUsers" :comments="postComments" :postCommentUsers="postCommentUsers" :mainUser="mainUser" :requestedUser="requestedUser"></like-posts>
        </template> -->
        <!-- <template v-else>
            
        </template> -->
        </keep-alive>
        <!-- <v-bottom-navigation :value="value" color="teal" grow fixed v-if="isMainUser">
            <v-btn @click="value = 0">
            <span>My Post</span>

            <v-icon>mdi-post</v-icon>
            </v-btn>

            <v-btn @click="value = 1">
            <span>Like Post</span>

            <v-icon>mdi-heart</v-icon>
            </v-btn>
            TODO:
            <v-btn  @click="value = 2">
            <span>Nearby</span>

            <v-icon>mdi-map-marker</v-icon>
            </v-btn>
        </v-bottom-navigation> -->
        <v-btn medium fab fixed right :style="isMainUser ? 'bottom: 80px' : 'bottom: 10px'" @click="scrollTop()">
            <v-icon size="16">mdi-triangle</v-icon>
        </v-btn>
    </div>
</template>

<script lang="ts">
import axios from 'axios';
import Vue, { PropType } from "vue"
import UserPosts from "../organisms/profile/UserPosts.vue";
import LikePosts from "../organisms/profile/LikePosts.vue";
import ProfileCard from "../organisms/profile/ProfileCard.vue";
export type PropUserObjType = {
    id: number
    screen_name: string
    name: string
    email: string
}
export type DataType = {
    value: number,
    likedPosts: PropUserObjType[] | string,
    postLikes: PropUserObjType[] | string,
    postLikeUsers: PropUserObjType[] | string,
    postComments: PropUserObjType[] | string,
    postCommentUsers: PropUserObjType[] | string,
    mainUserPostLikes: PropUserObjType[] | string,
    requestedUser:  any,
    windowSize: any,
}
export default Vue.extend({
        components: {
            UserPosts,
            LikePosts,
            ProfileCard,
        },
    props: {
        mainUser: Object as PropType<PropUserObjType>,
        // requestedUser: Object as PropType<PropUserObjType>,
        requestId: String,
    },
    data (): DataType {
        return {
            likedPosts: '',
            postLikes: '',
            postLikeUsers: '',
            postComments: '',
            postCommentUsers: '',
            mainUserPostLikes: '',
            value: 0,
            requestedUser: '',
            windowSize: {
                x: 0,
                y: 0,
            },
        }
    },
    computed:{
        isMainUser():boolean {
            const visitor = this.mainUser;
            const user = this.requestedUser;
            return visitor.id === user.id
        }
    },
    created(): void{
        const requestId = this.requestId;
        axios.get('/api/users/'+requestId, {
            params: {id: requestId},
        })
        .then((response) => {
            this.requestedUser = response.data;
            this.$store.commit('requestedUser', response.data)//vuex
        })
        .catch(error => {
            console.log('fail')
        })
        const visitor = this.mainUser;
        this.$store.commit('visitor', visitor);//vuex
        axios.get('/api/likedPostUsers', {
            params: {id: visitor.id},
        })
        .then((response) => {
            this.mainUserPostLikes = response.data.userLikes;
            this.postLikes = response.data.likes;
            this.postLikeUsers = response.data.likeUsers;
            this.postComments = response.data.comments;
            this.postCommentUsers = response.data.commentUsers;
            this.parsePosts(response.data.posts);
        })
        .catch(error => {
            console.log('fail')
        })
    },
    methods: {
        parsePosts(posts: any): void{
            var likedPosts = this.likedPosts;
            if(posts.length == 0){
                likedPosts = '';
                } else {
                    for(var i=0; i<posts.length; i++){
                    posts[i].image = JSON.parse(posts[i].image);
                }
                this.likedPosts = posts;
            }
        },
        scrollTop(){
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        },
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
            // this.$store.dispatch('windowSize', this.windowSize)
            this.$store.commit('windowSize', this.windowSize)
        },
    }
})
</script>
<style>
.v-dialog {
    max-height: 750px!important;
}
</style>