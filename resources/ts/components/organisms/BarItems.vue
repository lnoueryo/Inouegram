<template>
    <v-container class="d-flex align-center">
        <v-app-bar-nav-icon @click="onClick()"></v-app-bar-nav-icon>
        <v-toolbar-title>Shiftkey</v-toolbar-title>
        <v-spacer></v-spacer>
        <search-field></search-field>
        <v-spacer></v-spacer>
        <header-icons></header-icons>
    </v-container>
</template>

<script>
import SearchField from '../molecules/SearchField.vue'
import HeaderIcons from '../molecules/HeaderIcons.vue'
export default {
    components: {
        'search-field': SearchField,
        'header-icons': HeaderIcons,
    },
    methods: {
        onClick(){
            this.$emit('drawer');
        }
    }
}
</script>
