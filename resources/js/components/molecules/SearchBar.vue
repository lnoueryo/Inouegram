<template>
    <div style="max-width: 520px;" :class="`d-flex justify-space-between align-center`" color="grey lighten-2">
        <search-field @search="search = $event; searchUser();" :users="users"></search-field>
        <!-- <search-button :buttonData="button"></search-button> -->
        <input type="hidden" name="name" :value="search">
    </div>
</template>

<script>
import SearchField from '../atoms/SearchFiled.vue'
// import Button from '../atoms/Button.vue'
export default {
    components: {
        'search-field': SearchField,
        // 'search-button': Button,
    },
    data(){
        return{
            button: {
                button: '検索',
                style: {
                    color: 'pink',
                }
            },
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
    }
}
</script>

<style>
    .v-application--wrap {
        min-height: transparent!important;
    }
</style>
