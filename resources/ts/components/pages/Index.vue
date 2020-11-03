<template>
    <index-template :myInfo="myInfo" :myPosts="myPosts"></index-template>
</template>

<script>
import IndexTemplate from '../templates/IndexTemplate'
export default {
    props: ['my-info', 'my-posts'],
    components: {
        IndexTemplate,
    },
    mounted(){
        console.log(this.myInfo);
        console.log(this.myPosts);
    }
}
</script>
