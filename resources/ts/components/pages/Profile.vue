<template>
    <div>
        <profile-templates
            :mainUser="mainUser"
            :requestedUser="requestedUser"
            :requestedUserPosts="requestedUserPosts"
            :mainUserLikes="mainUserLikes"
            :requestedUserLikes="requestedUserLikes"
            :requestedUserFollowed="requestedUserFollowed"
            :requestedUserComments="requestedUserComments"
            :likedPosts="likedPosts"
        ></profile-templates>
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
import UserProfileTemplates from '../templates/UserProfileTemplate'
export default {
    props: ['main-user', 'requested-user', 'requested-user-posts', 'main-user-likes', 'requested-user-likes', 'requested-user-followed', 'requested-user-comments', 'liked-posts'],
    components: {
        ProfileTemplates,
        UserProfileTemplates
    },
}
</script>
