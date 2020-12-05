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
        ></profile-templates>
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
import UserProfileTemplates from '../templates/UserProfileTemplate'
export default {
    props: ['main-user', 'requested-user', 'requested-user-posts', 'main-user-likes', 'requested-user-likes', 'requested-user-followed', 'requested-user-comments'],
    components: {
        ProfileTemplates,
        UserProfileTemplates
    },
}
</script>
