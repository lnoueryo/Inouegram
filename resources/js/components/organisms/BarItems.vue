<template>
    <v-container class="d-flex align-center">
        <v-app-bar-nav-icon @click="onClick()"></v-app-bar-nav-icon>
        <v-toolbar-title>Shiftkey</v-toolbar-title>
        <v-spacer></v-spacer>
        <search-bar></search-bar>
        <v-spacer></v-spacer>
        <icon-buttons></icon-buttons>
    </v-container>
</template>

<script>
import SearchBar from '../molecules/SearchBar.vue'
import IconButton from '../atoms/IconButton.vue'
export default {
    components: {
        'search-bar': SearchBar,
        'icon-buttons': IconButton,
    },
    methods: {
        onClick(){
            this.$emit('drawer');
        }
    }
}
</script>
