<template>
    <div class="text-center" style="max-width: 520px;" :class="`d-flex justify-space-between align-center`" color="grey lighten-2">
    <v-menu offset-y max-width="230">
        <template v-slot:activator="{ on }">
        <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            v-on="on"
            autocomplete="off"
            @keyup="searchUser()"
            @focus="searchUser()"
        ></v-text-field>
      </template>
      <!-- <two-line-list :users="sendUserInfo"></two-line-list> -->
        <v-list two-line v-if="sendUserInfo.length > 0">
            <template v-for="(user, index) in sendUserInfo">
            <v-list-item :key="index" :href="'/profile?id=' + user.id">
                <v-list-item-avatar>
                    <v-avatar>
                        <v-img :src="'/storage/image/avatar/' + user.profile_image"></v-img>
                    </v-avatar>
                </v-list-item-avatar>
                <v-list-item-content style="align-items: start;">
                    <v-list-item-title style="-webkit-line-clamp: initial;" >{{ user.screen_name }}</v-list-item-title>
                    <v-list-item-subtitle style="-webkit-line-clamp: initial;">{{ user.comment }}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            </template>
        </v-list>
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
    computed: {
        sendUserInfo(){
            return this.users;
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
