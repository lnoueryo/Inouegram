<template>
    <div><br><br><br><br>
        {{ latestPosts }}<br><br>
        {{ latestPostedUsers }}
    </div>
</template>

<script>
export default {
    props: {
        latestPosts: {
            type: Object,
        },
        latestPostedUsers: {
            type: Object,
        }
    }
}
</script>
