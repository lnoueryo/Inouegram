<template>
    <div>
        <!-- <v-img :src="newPosts"></v-img> -->
        <profile-templates :thisUserPosts="thisUserPosts"></profile-templates>
        <!-- <profile-templates :thisUser="thisUser" :thisUserPosts="thisUserPosts" :thisUserLikes="thisUserLikes" :thisUserComments="thisUserComments" :myInfo="myInfo" :commentUsers="commentUsers" :followed="followed" :followedCount="followedCount" :followingCount="followingCount"></profile-templates> -->
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
export default {
    props: ['this-user-posts'],
    components: {
        ProfileTemplates
    }
}
</script>
