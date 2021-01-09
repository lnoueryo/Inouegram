<template>
    <div>
        <v-card class="mx-auto" max-width="800" tile flat elevation="1" v-if="requestedUserInfo">
            <div v-if="requestedUserInfo.bg_image">
                <v-img style="border-radius: 5px" :aspect-ratio="16/9" :src="'storage/image/background/' + requestedUserInfo.bg_image" @click="btnclick"></v-img>
            </div>
            <div v-else>
                <v-img src="storage/image/background/bg-1.jpg" @click="btnclick"></v-img>
            </div>
            <v-list-item three-line class="px-3">
                <v-list-item-avatar size="80">
                    <avatar :requestedUser="requestedUserInfo" :mainUser="mainUser"></avatar><!-- コンポーネント-->
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title class="title" style="margin-top:10px;">{{requestedUserInfo.screen_name}}</v-list-item-title>
                    <v-list-item-subtitle>{{requestedUserInfo.name}}</v-list-item-subtitle>
                    <v-list-item-subtitle style="cursor: pointer;"><span @click="followerDialog = true">フォロワー{{ requestedUserInfo.followees.length }}人</span></v-list-item-subtitle>
                </v-list-item-content>
                <div class="font-weight-light grey--text title mb-2">
                    <buttons></buttons><!-- コンポーネント-->
                </div>
            </v-list-item>
        </v-card>
        <input style="display: none" ref="bg" type="file" accept="image/jpeg, image/jpg, image/png" @input="upload">
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
        <v-dialog v-model="changeBgDialog" max-width="290">
            <v-card>
                <v-img :src="changingBgData"></v-img>
                <v-card-text>
                背景をこちらに変更しますか？
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="changeBgDialog = false">
                    いいえ
                </v-btn>
                <v-btn color="green darken-1" text @click="changeBg()">
                    はい
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
    </div>
</template>

<script>
import Avatar from "../../molecules/profile/profile_card/Avatar.vue";
import Buttons from "../../molecules/profile/profile_card/Buttons.vue";
export default {
    components:{
        Avatar,
        Buttons
    },
    props: ['mainUser', 'requestedUser'],
    data () {
        return {
            visitor: '',
            user: '',
            followerDialog: false,
            changeBgDialog: false,
            changingBgData: '',
            sizeDialog: false,
            progress: false,
        }
    },
    watch:  {
        requestedUser: {
            immediate: true,
            handler: function () {
                this.user = this.$store.getters.requestedUser;
            },
        },
        mainUser: {
            immediate: true,
            handler: function () {
                this.visitor = this.$store.getters.visitor;
            }
        },
    },
    computed:{
        requestedUserInfo(){
            var user = this.user;
            return user;
        },
        followingUsers(){
            if (this.requestedUser.followees) {
                return this.requestedUser.followees
            } else {
                return false
            }
        },
        userFollowedNumer(){
            var userFollowedNumer = this.userFollowed.length;
            console.log(this.userFollowed)
            return userFollowedNumer;
        },
    },
    methods: {
        async changeBg(){
            var bgData = this.changingBgData;
            let userId = this.user.id;
            let fd= new FormData();
            fd.append("bgData", bgData);
            fd.append("userId", userId);
            axios.post('/api/upload', fd)
            .then(
                response => {
                    this.changeBgDialog = false;
                    this.user.bg_image = response.data;
                }
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        upload(event){
            if(event.target){
            var file = event.target.files[0];
                if(this.windowSize.x<480){
                    if(file.size<3100000){
                        this.progress = true;
                        var reader = new FileReader();
                        var that = this;
                            reader.onload = function() {
                                that.changingBgData = e.target.result;
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
                        reader.onload = function(e) {
                            that.changingBgData = e.target.result;
                            that.changeBgDialog = true;
                            that.progress = false;
                        }
                    reader.readAsDataURL(file);
                }
            } else {
                this.changeBgDialog = false;
            }
        },
        btnclick() {
            if(this.visitor.id == this.user.id){
                let element  = this.$refs.bg;
                element.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
            }
        },
    }
}
</script>
