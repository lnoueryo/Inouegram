<template>
    <div>
        <post-message @input="textValue($event)" @keyup.enter="post()"></post-message>
    </div>
</template>

<script>
import PostMessage from '../molecules/PostMessage.vue'
export default {
    components: {
        'post-message': PostMessage,
    },
    methods: {
        textValue(e){
            this.$store.dispatch('message', e)
        }
    }
}
</script>
