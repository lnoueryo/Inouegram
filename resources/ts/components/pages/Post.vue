<template>
  <div>
    <post-template :user="user"></post-template>
  </div>
</template>

<script>
import PostTemplate from '../templates/PostTemplate.vue'
export default {
    props:['user'],
    components: {
        'post-template': PostTemplate,
    },
}
</script>


</style>
