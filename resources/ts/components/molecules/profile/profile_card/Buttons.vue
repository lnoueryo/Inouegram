<template>
    <div>
        <div v-if="visitor.id == user.id">
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
                    <template v-if="profile.password">
                        <v-btn color="blue darken-1" text @click="changeProfile" :disabled="!valid">
                            Save
                        </v-btn>
                    </template>
                    <template v-else>
                        <v-btn color="blue darken-1" text @click="changeProfile" disabled>
                            Save
                        </v-btn>
                    </template>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
        <v-snackbar v-model="saveSnackbar" :timeout="timeout">
            プロフィールが変更されました。
            <template v-slot:action="{ attrs }">
                <v-btn color="blue" text v-bind="attrs" @click="saveSnackbar = false">
                閉じる
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import axios from "axios"
export type DataType = {
    user: any,
    visitor: any,
    profileDialog: boolean,
    saveSnackbar: boolean,
    timeout: number,
    profile: {name: string, screen_name: string, email: string, password: string},
    changeProfileErrors: string,
    nameRules: any,
    nicknameRules: any,
    emailRules: any,
    passwordRules: any,
    valid: boolean,
    showPassword: boolean
}
export default Vue.extend({
    data(): DataType{
        return {
            user: this.$store.getters.requestedUser,
            visitor: this.$store.getters.visitor,
            profileDialog: false,
            saveSnackbar: false,
            timeout: 2000,
            profile: {name: this.$store.getters.visitor.name, screen_name: this.$store.getters.visitor.screen_name, email: this.$store.getters.visitor.email, password: ''},
            changeProfileErrors: '',
            nameRules: [
                (v: string) => !!v || '入力が必要です',
            ],
            nicknameRules: [
                (v: string) => !!v || '入力が必要です',
            ],
            emailRules: [
                (v: string) => !!v || '入力が必要です',
                (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            valid: false,
            showPassword: false,
            passwordRules: {
                required: (value: string) => !!value || '入力が必要です.',
                min: (v: string) => v.length >= 8 || '8文字以上です',
            },
        }
    },
    computed: {
        isFollowed(): boolean{
            const followees = this.user.followees;
            const visitor = this.visitor;
            if(followees){
                return followees.some((followee: {following_id: number}): boolean => {
                    return followee.following_id === visitor.id;
                })
            } else {
                return false;
            }
        },
    },
    watch:  {
        user: {
            immediate: true,
            handler(): void {
                this.user = this.$store.getters.requestedUser;
            },
        },
        visitor: {
            immediate: true,
            handler(): void {
                this.visitor = this.$store.getters.visitor;
            }
        },
    },
    methods:{
        changeProfile(): void{
            axios.post('/api/changeProfile', {
                'id': this.visitor.id,'name': this.profile.name, 'screen_name': this.profile.screen_name, 'email': this.profile.email, password: this.profile.password
            })
            .then(
                response => {
                    this.saveSnackbar = true;
                    this.$store.commit('requestedUser', response.data)
                    this.profileDialog = false;
                    this.profile.password = '';
                    this.resetValidation();
                }
            )
            .catch((error) => {
                const responseErrors = error.response.data.errors;
                let errors: any = {};
                for(const key in responseErrors) {
                    errors[key] = responseErrors[key][0];
                }
                console.log(errors)
                this.changeProfileErrors = errors;
            });
        },
        resetValidation (): void {
            const element: HTMLElement | any = this.$refs.form;
            element.resetValidation();
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
            .then((response) =>{
                this.user.followees = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
    }
})
</script>