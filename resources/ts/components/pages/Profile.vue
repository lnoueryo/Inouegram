<template>
    <div>
        <div v-if="myInfo.id == thisUser.id">
            <profile-templates :thisUserPosts="thisUserPosts" :thisUser="myInfo"></profile-templates>
        </div>
        <div v-else>
            <user-profile-templates :thisUserPosts="thisUserPosts" :thisUser="thisUser"></user-profile-templates>
        </div>
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
import UserProfileTemplates from '../templates/UserProfileTemplate'
export default {
    props: ['this-user-posts', 'my-info', 'this-user'],
    components: {
        ProfileTemplates,
        UserProfileTemplates
    }
}
</script>
