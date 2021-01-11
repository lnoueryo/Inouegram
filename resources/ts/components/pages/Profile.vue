<template>
    <div>
        <profile-templates :mainUser="mainUser" :requestId="requestId"></profile-templates>
    </div>
</template>

<script>
import ProfileTemplates from '../templates/ProfileTemplate'
export default {
    props: ['main-user', 'request-id'],
    components: {
        ProfileTemplates,
    },
}
</script>
