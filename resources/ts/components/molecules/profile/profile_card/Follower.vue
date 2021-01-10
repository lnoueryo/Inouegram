<template>
    <div v-if="user">
        <v-list-item-title class="title" style="margin-top:10px;">{{user.screen_name}}</v-list-item-title>
        <v-list-item-subtitle>{{user.name}}</v-list-item-subtitle>
        <v-list-item-subtitle style="cursor: pointer;"><span @click="followerDialog = true">フォロワー{{ user.followees.length }}人</span></v-list-item-subtitle>
        <v-dialog v-model="followerDialog" max-width="290">
            <v-card>
                <v-list subheader>
                <v-subheader>フォロワー</v-subheader>
                <div style="max-height: 450px; overflow-y: scroll;">
                    <v-list-item v-for="(followingUser, index) in followingUsers" :key="index" :href="'/profile?id=' + followingUser.followee.id">
                        <v-list-item-avatar>
                        <v-img :src="'storage/image/avatar/' + followingUser.followee.profile_image"></v-img>
                        </v-list-item-avatar>
                        <v-list-item-content>
                        <v-list-item-title v-text="followingUser.followee.screen_name"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </div>
                </v-list>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue"
export type DataType = {
    followerDialog: boolean
}
export default Vue.extend({
    data(): DataType {
        return {
            followerDialog: false
        }
    },
    computed: {
        user(): object{
            return this.$store.getters.requestedUser;
        },
        followingUsers(): object | boolean{
            const followingUsers = this.$store.getters.requestedUser.followees;
            if (followingUsers) {
                return followingUsers
            } else {
                return false
            }
        },
    },
})
</script>