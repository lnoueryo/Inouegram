<template>
  <div>
  <div v-if="screen">
      <post-template :user="user"></post-template>
  </div>
  <div v-else>
    <post-template-sp :user="user"></post-template-sp>
  </div>
  </div>
</template>

<script>
import PostTemplate from '../templates/PostTemplate.vue'
import PostTemplateSp from '../templates/PostTemplateSp.vue'
export default {
    props:['user'],
    components: {
        PostTemplate,
        PostTemplateSp
    },
    computed:{
      screen(){
          var mediaSize = window.matchMedia('(max-width: 480px)');
          if(mediaSize.matches){
              return false;
          } else {
              return true;
          }
      }
    }
}
</script>


</style>
