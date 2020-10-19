<template functional>
    <v-btn height="48px" :color="submitBtn.color" :text="submitBtn.text" :ripple="false" large @click="listeners.click">{{ submitBtn.value }}</v-btn>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    computed: {
        submitBtn(){
            return this.$store.state.post.submitBtn;
        }
    }
})
</script>
<style scoped>

button {
    outline: none;
}
button:active {
outline: none;
opacity: 0.5;
}

button::before {
    background-color: white!important;
}
</style>

<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/UserList.vue#5 --><!-- project://resources/js/components/organisms/PostCard.vue#3 -->

