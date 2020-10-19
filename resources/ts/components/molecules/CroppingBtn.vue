<template>
    <div>
        <block-icon-btn :blockIconButton="croppingBtn" :btnValidation="btnValidationNext1" @click="clickCroppingBtn"></block-icon-btn>
    </div>
</template>
<script>
import BlockIconBtn from '../atoms/BlockIconBtn'
import { mapGetters } from 'vuex'
export default {
    components: {
        'block-icon-btn': BlockIconBtn,
    },
    computed: {
        ...mapGetters([
        'croppingBtn',
        'btnValidationNext1'
        ]),
    },
    methods: {
        clickCroppingBtn(){
            this.$emit('clickCroppingBtn')
        },
    }
}
</script>

