<template functional>
    <v-btn height="48px" :color="submitBtn.color" :text="submitBtn.text" :ripple="false" large @click="listeners.click">{{ submitBtn.value }}</v-btn>
</template>

<script>
export default {
    computed: {
        submitBtn(){
            return this.$store.state.post.submitBtn;
        }
    }
}
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

