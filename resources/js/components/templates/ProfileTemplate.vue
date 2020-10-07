<template>
    <v-content>
      <v-container>
            <!-- <profile-upper-body :thisUser="thisUser" :myId="myInfo.id" :userPostsNumber="thisUserPosts.length" :followed="followed" :followedCount="followedCount" :followingCount="followingCount"></profile-upper-body> -->
            <!-- <profile-lower-body :thisUser="thisUser" :thisUserPosts="thisUserPosts" :thisUserLikes="thisUserLikes" :thisUserComments="thisUserComments" :myInfo="myInfo" :commentUsers="commentUsers"></profile-lower-body> -->
      </v-container>
    </v-content>
</template>

<script>
// import ProfileLowerBody from "../organisms/profile/ProfileLowerBody"
export default {
    // props: ['thisUser', 'thisUserPosts', 'thisUserLikes', 'thisUserComments', 'myInfo', 'commentUsers', 'followed', 'followedCount', 'followingCount'],
    components: {
        // 'profile-lower-body': ProfileLowerBody,
    },

}
</script>

    <style>
        .theme--light.v-application {
            background-color: rgba(var(--b3f, 250, 250, 250), 1);
            color: rgba(0,0,0,.87);
        }
    </style>
