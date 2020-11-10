<template>
    <index-template :myInfo="myInfo" :myPosts="myPosts" :myLikes="myLikes"></index-template>
</template>

<script>
import IndexTemplate from '../templates/IndexTemplate'
export default {
    props: ['my-info', 'my-posts', 'my-likes'],
    components: {
        IndexTemplate,
    },
}
</script>
