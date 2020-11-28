<template>
    <index-template :myInfo="myInfo" :posts="posts" :myLikes="myLikes" :myUsers="myUsers" :likes="likes"></index-template>
</template>

<script>
import IndexTemplate from '../templates/IndexTemplate'
export default {
    props: ['my-info', 'posts', 'my-likes', 'my-users', 'likes'],
    components: {
        IndexTemplate,
    },
}
</script>
