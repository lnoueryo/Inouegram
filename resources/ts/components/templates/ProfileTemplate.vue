<template>
    <div>
        <profile-card :mainUser="mainUser" :requestedUser="requestedUser" :requestedUserFollowed="requestedUserFollowed"></profile-card>
        <keep-alive>
        <template v-if="value == 0">
            <user-posts
                :mainUser="mainUser"
                :requestedUser="requestedUser"
                :requestedUserPosts="requestedUserPosts"
                :mainUserLikes="mainUserLikes"
                :requestedUserLikes="requestedUserLikes"
                :requestedUserComments="requestedUserComments"
                :likedPosts="likedPosts"
                :isMainUser="isMainUser"
            ></user-posts>
        </template>
        <template v-else-if="value == 1">
            <like-posts :likedPosts="likedPosts"></like-posts>
        </template>
        <template v-else>
            a
        </template>
        </keep-alive>
        <v-bottom-navigation :value="value" color="teal" grow fixed v-if="isMainUser">
            <v-btn @click="value = 0">
            <span>My Post</span>

            <v-icon>mdi-post</v-icon>
            </v-btn>

            <v-btn @click="value = 1">
            <span>Like Post</span>

            <v-icon>mdi-heart</v-icon>
            </v-btn>

            <v-btn  @click="value = 2">
            <span>Nearby</span>

            <v-icon>mdi-map-marker</v-icon>
            </v-btn>
        </v-bottom-navigation>
    </div>
</template>

<script lang="ts">
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
    value: number
}
export default Vue.extend({
        components: {
            UserPosts,
            LikePosts,
            ProfileCard,
        },
    props: {
        mainUser: Object as PropType<PropUserObjType>,
        requestedUser: Object as PropType<PropUserObjType>,
        requestedUserPosts: Array,
        mainUserLikes: Array,
        requestedUserLikes: Array,
        requestedUserFollowed: Array,
        requestedUserComments: Array,
        likedPosts: Array,
    },
    data (): DataType {
        return {
            value: 0,
        }
    },
    computed:{
        isMainUser():boolean {
            const visitor = this.mainUser;
            const user = this.requestedUser;
            return visitor.id === user.id
        }
    },
})
</script>
<style>
.v-dialog {
    max-height: 700px!important;
}
</style>