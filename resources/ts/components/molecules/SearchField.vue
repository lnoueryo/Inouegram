<template>
    <div class="text-center" style="max-width: 520px;" :class="`d-flex justify-space-between align-center`" color="grey lighten-2">
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
            v-on:keyup="searchUser()"
            v-on:focus="searchUser()"
        ></v-text-field>
      </template>
      <two-line-list :users="users"></two-line-list>
    </v-menu>
  </div>
</template>

<script>
import TwoLineList from "../molecules/TwoLineList.vue"
export default {
    components: {
        'two-line-list': TwoLineList,
    },
    data(){
        return{
            search: '',
            users: '',
        }
    },
    methods: {
        searchUser(){
            axios.get('/api/user', {
                params: {'name': this.search},
            })
            .then(
                response => ([
                    this.users = response.data,
                ]),
            )
            .catch(function (error) {
                console.log(error);
            });
        }
    },

}
</script>

<style>
    .v-text-field{
        padding: 0;
        margin: 0;
    }
</style>
