<template>
    <div>
        <div v-if="myInfo.id == thisUser.id">
            <profile-templates :thisUserPosts="thisUserPosts" :thisUser="myInfo" :myLikes="myLikes" :thisUserLikes="thisUserLikes"></profile-templates>
        </div>
        <div v-else>
            <user-profile-templates :thisUserPosts="thisUserPosts" :thisUser="thisUser" :myInfo="myInfo" :myLikes="myLikes" :thisUserLikes="thisUserLikes" :followed="followed"></user-profile-templates>
        </div>
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
import UserProfileTemplates from '../templates/UserProfileTemplate'
export default {
    props: ['this-user-posts', 'my-info', 'this-user', 'my-likes', 'this-user-likes', 'followed'],
    components: {
        ProfileTemplates,
        UserProfileTemplates
    },
}
</script>
