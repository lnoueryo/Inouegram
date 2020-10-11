<template>
    <div>
        <icon-button :iconButtonIcons="iconButtonIcons" :iconButtonUrl="iconButtonUrl"></icon-button>
    </div>
</template>
<script>
import IconButton from '../atoms/IconButton.vue'
import { mapGetters } from 'vuex'
export default {
    components: {
        'icon-button': IconButton,
    },
    computed: {
        ...mapGetters([
        'iconButtonIcons',
        'iconButtonUrl'
        ]),
        otherComputed() {

        }
    },
}
</script>
