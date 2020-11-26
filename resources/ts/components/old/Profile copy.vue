<template>
    <div>
        <!-- <v-img :src="newPosts"></v-img> -->
        <profile-templates></profile-templates>
        <!-- <profile-templates :thisUser="thisUser" :thisUserPosts="thisUserPosts" :thisUserLikes="thisUserLikes" :thisUserComments="thisUserComments" :myInfo="myInfo" :commentUsers="commentUsers" :followed="followed" :followedCount="followedCount" :followingCount="followingCount"></profile-templates> -->
    </div>
</template>

<script>
// import ProfileTemplates from "../templates/ProfileTemplates.vue"
export default {
    props: {
        thisUserPosts: {
            type: Array,
        },
        // thisUserLikes: {
        //     type: Array,
        // },
        // thisUserComments: {
        //     type: Array,
        // },
        // myInfo: {
        //     type: Object,
        // },
        // commentUsers: {
        //     type: Array,
        // },
        // thisUser: {
        //     type: Object,
        // },
        // followed: {
        //     type: Number,
        // },
        // followedCount: {
        //     type: Number,
        // },
        // followingCount: {
        //     type: Number,
        // }
    },
    computed: {
            newPosts(){
                let thisUserPosts = this.thisUserPosts;
                for(var i=0; i<thisUserPosts.length; i++){
                thisUserPosts[i].image = JSON.parse(thisUserPosts[i].image);
                for(var j=0; j<thisUserPosts[i].image.length; j++){
                    thisUserPosts[i].image[j].src = 'storage/image/' + thisUserPosts[i].image[j].src;
                }
            }
            return thisUserPosts[3].image[0].src;
        },
    },
    // components: {
    //     'profile-templates': ProfileTemplates,
    // },
        mounted(){
        this.profileInfo();
    },
    methods: {
        profileInfo(){
            this.$store.dispatch('profileInfo', [
                this.thisUserPosts,
                // this.thisUserLikes,
                // this.thisUserComments,
                // this.myInfo,
                // this.commentUsers,
                // this.thisUser,
                // this.followed,
                // this.followedCount,
                // this.followingCount,
            ]);
        }
    }
}
</script>
