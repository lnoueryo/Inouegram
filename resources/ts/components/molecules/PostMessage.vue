<template functional>
    <input @input="listeners.input && listeners.input($event.target.value)" autocomplete="off" label="投稿してね" placeholder="今の気持ちを書こう"></input>
</template>

<script>
import SubmitButton from '../atoms/SubmitButton.vue'
export default {
    components: {
        'submit-button': SubmitButton,
    },
}
</script>
<style>

#comment::placeholder {

  text-align: center;

}

</style>
