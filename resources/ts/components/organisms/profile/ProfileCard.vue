<template>
    <div>
        <v-card class="mx-auto" max-width="800" tile flat elevation="1">
            <div v-if="requestedUserInfo.bg_image">
                <v-img style="border-radius: 5px" :aspect-ratio="16/9" :src="'storage/image/background/' + requestedUserInfo.bg_image" @click="btnclick"></v-img>
            </div>
            <div v-else>
                <v-img src="storage/image/background/bg-1.jpg" @click="btnclick"></v-img>
            </div>
            <v-list-item three-line class="px-3">
                <v-list-item-avatar size="80" @click="btnclick2">
                    <v-avatar color="indigo" size="80">
                        <v-img :src="'storage/image/avatar/' +  requestedUserInfo.profile_image"></v-img>
                    </v-avatar>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title class="title" style="margin-top:10px;">{{requestedUserInfo.screen_name}}</v-list-item-title>
                    <v-list-item-subtitle>{{requestedUserInfo.name}}</v-list-item-subtitle>
                    <v-list-item-subtitle @click="findFollowingUsers()" style="cursor: pointer;">フォロワー{{ userFollowedNumer }}人</v-list-item-subtitle>
                </v-list-item-content>
                <div class="font-weight-light grey--text title mb-2" v-if="visitor.id == user.id">
                    <v-btn @click="logout" small elevation="2">ログアウト</v-btn>
                </div>
                <div v-else>
                    <v-btn elevation="1" :color="isFollowed ? 'error' : 'primary'" @click="follow(isFollowed);">
                        <span>{{ isFollowed ? 'フォロー済み' : 'フォローする' }}</span>
                    </v-btn>
                </div>
            </v-list-item>
        </v-card>
        <input style="display: none" ref="bg" type="file" accept="image/jpeg, image/jpg, image/png" @input="upload">
        <input style="display: none" ref="avatar" type="file" accept="image/jpeg, image/jpg, image/png" @input="changeAvatar">
        <v-dialog v-model="followerDialog" max-width="290">
            <v-card>
                <v-list subheader>
                <v-subheader></v-subheader>
                <div style="max-height: 450px; overflow-y: scroll;">
                    <v-list-item v-for="(followingUser, index) in followingUsers" :key="index" :href="'/profile?id=' + followingUser.id">
                        <v-list-item-avatar>
                        <v-img :src="'storage/image/avatar/' + followingUser.profile_image"></v-img>
                        </v-list-item-avatar>

                        <v-list-item-content>
                        <v-list-item-title v-text="followingUser.screen_name"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </div>
                </v-list>
            </v-card>
        </v-dialog>
        <v-dialog v-model="changeBgDialog" max-width="290">
            <v-card>
                <v-img :src="changingBgData"></v-img>
                <v-card-text>
                Do you wanna change to this image??
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="changeBgDialog = false">
                    No
                </v-btn>
                <v-btn color="green darken-1" text @click="changeBg()">
                    Yes
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from "vue"
import axios from 'axios'
export type DataType = {
    visitor: User,
    user: User,
    userFollowed: Follower[],
    followerDialog: boolean,
    followingUsers: User[] | string,
    windowSize: windowSize,
    changeBgDialog: boolean,
    changingBgData: string | null
}

interface User {
    id: number;
}
interface Follower {
    following_id: number;
}
interface windowSize {
    x: number
    y: number
}
    export default Vue.extend({
    props: {
        mainUser: Object as () => User,
        requestedUser: Object as () => User,
        requestedUserFollowed: Array as () => Follower[],
    },
    // props: ['mainUser', 'requestedUser', 'requestedUserFollowed'],
    data (): DataType {
        return {
            visitor: this.mainUser,
            user: this.requestedUser,
            userFollowed: this.requestedUserFollowed,
            followerDialog: false,
            followingUsers: '',
            windowSize: {
                x: 0,
                y: 0,
            },
            changeBgDialog: false,
            changingBgData: '',
        }
    },
    computed:{
        requestedUserInfo(): User{
            var user = this.user as User;
            return user;
        },
        isFollowed(): boolean{
            var userFolloweds = this.userFollowed;
            var visitor = this.visitor as User;
            return userFolloweds.some((userFollowed: Follower) => {
                return userFollowed.following_id === visitor.id;
            })
        },
        userFollowedNumer(){
            var userFollowedNumer: number = this.userFollowed.length;
            console.log(this.userFollowed)
            return userFollowedNumer;
        },
    },
    methods: {
        async changeBg(){
            var bgData = this.changingBgData as string;
            let userId: any = this.user.id;
            let fd= new FormData();
            fd.append("bgData", bgData);
            fd.append("userId", userId);
            axios.post('/api/upload', fd)
            .then(
                response => {
                    this.changeBgDialog = false;
                    this.user = response.data;
                }
            )
            .catch(function (error: string) {
                console.log(error);
            });
        },
        async changeAvatar(event: any){
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            var avatarData;
            var that = this;
            reader.onload = function(e: any) {
                avatarData = e.target.result;
            let fd= new FormData();
            fd.append("avatarData", avatarData);
            fd.append("userId", that.user.id as any);
            axios.post('/api/upload2', fd)
            .then(
                response => {
                    that.user = response.data;
                }
            )
            .catch(function (error: string) {
                console.log(error);
            });
            }
        },
        upload(event: any): void{
            if(event.target instanceof HTMLInputElement){
            var file = event.target.files[0];
            var reader = new FileReader();
            var that = this;
                reader.onload = function(e: any) {
                    that.changingBgData = e.target.result as string | null;
                    that.changeBgDialog = true;
                }
                reader.readAsDataURL(file);
            } else {
                this.changeBgDialog = false;
            }
        },
        btnclick(): void {
            if(this.visitor.id == this.user.id){
                let element: HTMLElement | any = this.$refs.bg;
                element.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
            }
        },
        btnclick2(): void {
            if(this.visitor.id == this.user.id){
                let element: HTMLElement | any = this.$refs.avatar;
                element.click()
            }
        },
        findFollowingUsers(): void{
            this.followerDialog = true;
            console.log(this.followerDialog)
            var followingIds = this.userFollowed.map((user) => {
                return user.following_id
            })
            axios.post('/api/followingUsers', {
                usersId: followingIds,
            })
            .then((response) => {
                this.followingUsers = response.data;
            })
            .catch((error) => {
                console.log('fail')
            })
        },
        logout(): void {
          axios.post('/logout')
          .then(() => location.href = '/')
          .catch(function (error) {
              location.href = '/';
          });
        },
        follow(isFollowed: boolean): void{
                axios.get('/api/follow', {
                params: {'id': this.user.id, 'myId': this.visitor.id, 'isFollowed': isFollowed},
            })
            .then(
                response => (this.userFollowed = response.data)
            )
            .catch(function (error) {
                console.log(error);
            });
        },
    }
})
</script>
<style>
.v-dialog {
    max-height: 700px!important;
}
</style>