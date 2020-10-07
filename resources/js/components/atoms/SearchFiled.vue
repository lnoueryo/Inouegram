<template>
    <div class="text-center">
    <v-menu offset-y>
      <template v-slot:activator="{ on }">
        <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            v-on="on"
            autocomplete="off"
            v-on:keyup="keyUp()"
            v-on:focus="keyUp()"
        ></v-text-field>
      </template>
      <two-line-list :users="users"></two-line-list>
    </v-menu>
  </div>
</template>

<script>
import TwoLineList from "../molecules/TwoLineList.vue"
export default {
    props: ['users'],
    components: {
        'two-line-list': TwoLineList,
    },
    data(){
        return{
            search: '',
        }
    },
    methods: {
        keyUp(){
            this.$emit('search', this.search);
        },
    }
}
</script>

<style>
    .v-text-field{
        padding: 0;
        margin: 0;
    }
</style>
