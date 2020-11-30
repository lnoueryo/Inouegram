<template>
    <div>
        <div v-if="mainUser.id == requestedUser.id">
            <profile-templates :requestedUser="mainUser" :requestedUserPosts="requestedUserPosts" :mainUserLikes="mainUserLikes" :requestedUserLikes="requestedUserLikes"></profile-templates>
        </div>
        <div v-else>
            <user-profile-templates :mainUser="mainUser" :requestedUser="requestedUser" :requestedUserPosts="requestedUserPosts" :mainUserLikes="mainUserLikes" :requestedUserLikes="requestedUserLikes" :followed="followed"></user-profile-templates>
        </div>
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
import UserProfileTemplates from '../templates/UserProfileTemplate'
export default {
    props: ['main-user', 'requested-user', 'requested-user-posts', 'main-user-likes', 'requested-user-likes', 'followed'],
    components: {
        ProfileTemplates,
        UserProfileTemplates
    },
}
</script>
