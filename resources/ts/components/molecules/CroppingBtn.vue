<template>
    <div>
        <block-icon-btn :blockIconButton="croppingBtn" :btnValidation="btnValidationNext1" @click="clickCroppingBtn"></block-icon-btn>
    </div>
</template>
<script lang="ts">
import Vue from 'vue'
import BlockIconBtn from '../atoms/BlockIconBtn.vue'
import { mapGetters } from 'vuex'
export default Vue.extend({
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
})
</script>

