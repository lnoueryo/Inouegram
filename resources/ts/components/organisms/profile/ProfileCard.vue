<template>
    <div v-resize="onResize">
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
                    <v-list-item-subtitle style="cursor: pointer;"><span @click="findFollowingUsers()">フォロワー{{ userFollowedNumer }}人</span></v-list-item-subtitle>
                </v-list-item-content>
                <div class="font-weight-light grey--text title mb-2" v-if="visitor.id == user.id">
                    <div>
                        <v-btn @click="logout" small block elevation="2">ログアウト</v-btn>
                    </div>
                    <div>
                        <v-btn small elevation="2" dark @click="profileDialog = true">プロフィール</v-btn>
                    </div>
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
        <v-dialog v-model="sizeDialog" width="500">
            <v-card>
                <v-card-title class="headline grey lighten-2">
                Error
                </v-card-title>

                <v-card-text>
                ファイルサイズが上限を超えています。3M以下のイメージを選択してください
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="sizeDialog = false">
                    I accept
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-overlay :value="progress">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-row justify="center">
            <v-dialog v-model="profileDialog" max-width="600px">
            <v-card>
                <v-card-title>
                <span class="headline">プロフィール</span>
                </v-card-title>
                <v-card-text>
                <v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-row>
                            <v-col cols="12" sm="6" md="6">
                                <v-text-field v-model="profile.name" label="名前" required :rules="nameRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6">
                                <v-text-field v-model="profile.screen_name" label="ニックネーム" :rules="nicknameRules"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="profile.email" label="メールアドレス" required :rules="emailRules"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :rules="[passwordRules.required, passwordRules.min]" :type="showPassword ? 'text' : 'password'" name="input-10-2"
                                    label="パスワード"
                                    hint="8文字以上です"
                                    autocomplete="new-password"
                                    class="input-group--focused py-2"
                                    @click:append="showPassword = !showPassword"
                                    v-model="profile.password"
                                    ></v-text-field>
                            </v-col>
                            <div v-if="Object.keys(changeProfileErrors).length !== 0">
                            <div class="mb-2 px-4">
                                <div class="error px-4 py-2" style="font-size: smaller;font-weight: 500;">
                                <div class="alert alert-danger" v-text="changeProfileErrors.password" v-if="changeProfileErrors.password"></div>
                                </div>
                            </div>
                            </div>
                        </v-row>
                    </v-form>
                </v-container>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="profileDialog = false">
                    Close
                </v-btn>
                <v-btn color="blue darken-1" text @click="changeProfile" :disabled="!valid">
                    Save
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-row>
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
    changingBgData: string | null,
    sizeDialog: boolean,
    progress: boolean,
    profileDialog: boolean,
    nameRules: any,
    nicknameRules: any,
    emailRules: any,
    valid: boolean,
    showPassword: boolean,
    passwordRules: any,
    profile: any,
    changeProfileErrors: any
}

interface User {
    id: number;
    name: string;
    screen_name: string;
    email: string;
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
            sizeDialog: false,
            progress: false,
            profileDialog: false,
            nameRules: [
                (v: any) => !!v || '入力が必要です',
            ],
            nicknameRules: [
                (v: any) => !!v || '入力が必要です',
            ],
            emailRules: [
                (v: any) => !!v || '入力が必要です',
                (v: any) => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            valid: false,
            showPassword: false,
            passwordRules: {
                required: (value: any) => !!value || '入力が必要です.',
                min: (v: any) => v.length >= 8 || '8文字以上です',
            },
            profile: {name: this.mainUser.name, screen_name: this.mainUser.screen_name, email: this.mainUser.email, password: ''},
            changeProfileErrors: '',
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
            if (typeof FileReader === 'function') {
                if(this.windowSize.x<480){
                    if(file.size<3100000){
                        this.progress = true;
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
                                    that.progress = false;
                                }
                            )
                            .catch(function (error: string) {
                                console.log(error);
                                that.progress = false;
                            });
                        }
                    } else {
                        this.sizeDialog = true;
                    }
                } else {
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
                }
            }
        },
        upload(event: any): void{
            if(event.target instanceof HTMLInputElement){
            var file = event.target.files[0];
                if(this.windowSize.x<480){
                    if(file.size<3100000){
                        this.progress = true;
                        var reader = new FileReader();
                        var that = this;
                            reader.onload = function(e: any) {
                                that.changingBgData = e.target.result as string | null;
                                that.changeBgDialog = true;
                                that.progress = false;
                            }
                            reader.readAsDataURL(file);
                    } else {
                        this.sizeDialog = true;
                    }
                } else {
                    this.progress = true;
                    var reader = new FileReader();
                    var that = this;
                        reader.onload = function(e: any) {
                            that.changingBgData = e.target.result as string | null;
                            that.changeBgDialog = true;
                        }
                    reader.readAsDataURL(file);
                }
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
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
        },
        changeProfile(){
            axios.post('/api/changeProfile', {
                'id': this.mainUser.id,'name': this.profile.name, 'screen_name': this.profile.screen_name, 'email': this.profile.email, password: this.profile.password
            })
            .then(
                response => {
                    this.user = response.data;
                    this.profileDialog = false;
                    this.profile.password = '';
                    this.resetValidation();
                }
            )
            .catch((error) => {
                var responseErrors = error.response.data.errors;
                var errors: any = {};
                // let that = this;
                for(var key in responseErrors) {
                    errors[key] = responseErrors[key][0];
                }
                console.log(errors)
                this.changeProfileErrors = errors;
            });
        },
        resetValidation () {
        let element: HTMLElement | any = this.$refs.form;
        element.resetValidation();
      },
    }
})
</script>
