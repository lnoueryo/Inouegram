<template>
    <div id="login" class="example">
        <img class="px-5 py-5" id="logo" src="/image/mymemories.png" style="position: absolute; z-index: 2;" v-if="loaded"/>
        <img id="main" :src="'/image/login'+randomNumber+'.png'" style="width: 100%; position: relative;" @load="isLoad">
        <div class="hello" style="opacity: 0.95;" v-if="loaded">
            <v-card class="mx-auto" max-width="500">
                <v-card-title class="title font-weight-regular justify-space-between">
                <h4>リセットパスワード</h4>
                </v-card-title>

                <v-window class="px-5 py-5">
                <v-window-item :value="1">
                  <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field
                      autocomplete="new-password"
                      v-model="registration.email"
                      label="メールアドレス"
                      placeholder="mymemories@hello.com"
                      counter="40"
                      disabled
                      class="py-2"
                    ></v-text-field>
                    <v-text-field
                    @input="blurInput"
                      :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                      :rules="[passwordRules.required, passwordRules.min]"
                      :type="show2 ? 'text' : 'password'"
                      name="input-10-2"
                      label="パスワード"
                      hint="8文字以上です"
                      autocomplete="new-password"
                      class="input-group--focused py-2"
                      @click:append="show2 = !show2"
                      v-model="registration.password"
                    ></v-text-field>
                    <v-text-field
                    @input="blurInput"
                      :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                      :rules="[confirmationPasswordRules.required, confirmationPasswordRules.match]"
                      :type="show3 ? 'text' : 'password'"
                      name="input-10-2"
                      label="パスワード確認"
                      hint="8文字以上です"
                      autocomplete="new-password"
                      class="input-group--focused py-2"
                      @click:append="show3 = !show"
                      @keyup.enter.exact="register"
                      v-model="registration.confirmationPassword"
                    ></v-text-field>
                    </v-form>
                  </v-card-text>
                    <div v-if="Object.keys(registrationErrors).length !== 0">
                      <div class="mb-2 px-4">
                        <div class="error px-4 py-2" style="font-size: smaller;font-weight: 500;">
                          <div class="alert alert-danger" v-text="registrationErrors.email" v-if="registrationErrors.email"></div>
                          <div class="alert alert-danger" v-text="registrationErrors.password" v-if="registrationErrors.password"></div>
                        </div>
                      </div>
                    </div>
                <div class="d-flex" style="justify-content: space-between;">
                <template v-if="initial">
                <v-btn disabled class="mr-4">
                送信
                </v-btn>
                </template>
                <template v-else>
                <v-btn color="indigo white--text" class="mr-4" @click="resetPassword" :disabled="!valid">
                送信
                </v-btn>
                </template>
                </div>
                </v-window-item>

                <v-window-item :value="3">
                </v-window-item>
                </v-window>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['email','token'],
        data(){
            return{
                loaded: false,
                initial: true,
                password: null,
                remember: '',
                errors: {},
                randomNumber: '',
                registration: {email: this.email, token: this.token, password: '', confirmationPassword: ''},
                show2: false,
                show3: false,
                show4: false,
                passwordRules: {
                required: value => !!value || '記入してください',
                min: v => v.length >= 8 || '8文字以上です',
                },
                confirmationPasswordRules: {
                match: v => v == this.registration.password || 'パスワードと一致してません',
                },
                valid: false,
                registrationErrors: {},',
            }
        },
        created() {
            this.randomNumber =  Math.floor( Math.random () * 5)+1;
        },
        mounted(){
            console.log(this.email);
        },
        methods: {
            isLoad(){
                this.loaded = true;
            },
            blurInput() {
              var registrationArray = Object.values(this.registration).map(registration => {
                return registration;
              })
              this.initial = registrationArray.some((registration) => {
                return registration === '';
              })
            },
            resetPassword() {
                var url = '/password/reset';
                var params = {
                    email: this.registration.email,
                    password: this.registration.password,
                    password_confirmation: this.registration.confirmationPassword,
                    token: this.registration.token,
                };
                axios.post(url, params)
                .then(() => location.href = '/home')
                .catch((error) => {
                    let that = this;
                    var responseErrors = error.response.data.errors;
                    var errors = {};
                    for(var key in responseErrors) {
                        errors[key] = responseErrors[key][0];
                    }
                    that.registrationErrors = errors;
                });
            },
        }
    }
</script>

