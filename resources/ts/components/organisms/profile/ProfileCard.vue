<template>
    <div>
        <v-card class="mx-auto" max-width="800" tile flat elevation="1" v-if="requestedUserInfo">
            <background-image></background-image>
            <v-list-item three-line class="px-3">
                <v-list-item-avatar size="80">
                    <avatar @progress="progress = $event" @sizeDialog="sizeDialog = $event"></avatar><!-- コンポーネント-->
                </v-list-item-avatar>
                <v-list-item-content>
                    <follower></follower><!-- コンポーネント-->
                </v-list-item-content>
                <div class="font-weight-light grey--text title mb-2">
                    <buttons></buttons><!-- コンポーネント-->
                </div>
            </v-list-item>
        </v-card>
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
import Follower from "../../molecules/profile/profile_card/Follower.vue";
import BackgroundImage from "../../molecules/profile/profile_card/BackgroundImage";
export default {
    components:{
        Avatar,
        Buttons,
        Follower,
        BackgroundImage
    },
    props: ['mainUser', 'requestedUser'],
    data () {
        return {
            user: this.$store.getters.requestedUser,
            visitor: this.$store.getters.visitor,
            followerDialog: false,
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
    },
    methods: {

    }
}
</script>
