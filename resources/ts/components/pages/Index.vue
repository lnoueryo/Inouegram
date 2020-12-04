<template>
    <index-template :mainUser="mainUser" :posts="posts" :mainUserLikes="mainUserLikes" :likes="likes" :followingUsersInfo="followingUsersInfo" :likedUsers="likedUsers" :comments="comments"></index-template>
</template>

<script>
import IndexTemplate from '../templates/IndexTemplate'
export default {
    props: ['main-user', 'posts', 'main-user-likes', 'likes', 'following-users-info', 'liked-users', 'comments'],
    components: {
        IndexTemplate,
    },
}
</script>
