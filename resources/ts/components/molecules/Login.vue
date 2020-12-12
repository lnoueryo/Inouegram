<template>
    <div id="login" class="example">
        <div>
        <img  class="px-5 py-5" id="logo" src="/image/mymemories.png" style="position: absolute; z-index: 1;"/>
        </div>
        <img id="main" :src="'/image/login'+randomNumber+'.png'" style="width: 100%; position: relative">
        <div class="hello" style="opacity: 0.95;">
            <v-card class="mx-auto" max-width="500">
                <v-card-title class="title font-weight-regular justify-space-between">
                <h4>{{ currentTitle }}</h4>
                <v-avatar color="primary lighten-2" class="subheading white--text" size="24" v-text="step"></v-avatar>
                </v-card-title>

                <v-window class="px-5 py-5" v-model="step">
                <v-window-item :value="1">
                <v-form ref="form" lazy-validation>
                <!-- <h4 class="py-2">ログイン</h4> -->
                <v-text-field class="py-2" v-model="email" placeholder="アドレス" label="Email" required clearable @keyup.enter.exact="login"></v-text-field>
                <v-text-field ref="focusThis" v-model="password" placeholder="パスワード" :counter="10" label="Password" required @click:append="show1 = !show1" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :type="show1 ? 'text' : 'password'" @keyup.enter.exact="login"></v-text-field>
                <div v-if="Object.keys(errors).length !== 0">
                  <div class="mb-3 mt-1">
                    <div class="error px-4 py-2" style="font-size: smaller;font-weight: 500;">
                      <div class="alert alert-danger" v-text="errors.email" v-if="errors.email"></div>
                      <div class="alert alert-danger" v-text="errors.password" v-if="errors.password"></div>
                    </div>
                  </div>
                </div>
                <div class="d-flex mb-5 justify-content-start">
                <v-btn color="success" class="mr-4" @click="login">
                ログイン
                </v-btn>
                <v-btn color="indigo white--text" @click="step++">
                会員登録
                </v-btn>
                </div>
                <div>
                    <v-btn class="mb-3 btn-google" href="/auth/google" block style="max-width: 280px">
                    <span class="g">g</span>
                    <span class="o">o</span>
                    <span class="o2">o</span>
                    <span class="g2">g</span>
                    <span class="l">l</span>
                    <span class="e">e</span>
                    </v-btn>
                </div>
                <div @mouseover="showq = true" @mouseout="showq = false">
                    <v-btn id="gitbtn" color="grey darken-4" class="grey--text lighten-1 black" href="/auth/github" block style="max-width: 280px">github
                        <transition name="bounce">
                            <img v-if="showq" style="position: absolute;right: 0;" src="/image/github.png">
                        </transition>
                    </v-btn>
                        <!-- <div v-if="showq"><img src="/image/github.png"></div> -->
                </div>
            </v-form>
                </v-window-item>

                <v-window-item :value="2">
                  <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field ref="name" v-model="registration.name" :rules="nameRules" label="名前"
                      placeholder="井上領"
                      required
                      class="py-2"
                      @input="blurInput"
                    ></v-text-field>
                    <v-text-field
                    @input="blurInput"
                      ref="screen_name"
                      v-model="registration.screen_name"
                      :rules="nicknameRules"
                      label="ニックネーム"
                      placeholder="Rio"
                      required
                      class="py-2"
                    ></v-text-field>
                    <v-text-field
                    @input="blurInput"
                      autocomplete="new-password"
                      v-model="registration.email"
                      :rules="emailRules"
                      label="メールアドレス"
                      placeholder="mymemories@hello.com"
                      counter="40"
                      required
                      class="py-2"
                    ></v-text-field>
                    <v-text-field
                    @input="blurInput"
                      :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                      :rules="[passwordRules.required, passwordRules.min]"
                      :type="show2 ? 'text' : 'password'"
                      name="input-10-2"
                      label="パスワード"
                      hint="At least 8 characters"
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
                      hint="At least 8 characters"
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
                <v-btn color="white" class="mr-4" @click="step--">
                戻る
                </v-btn>
                <template v-if="initial">
                <v-btn disabled class="mr-4">
                登録
                </v-btn>
                </template>
                <template v-else>
                <v-btn color="indigo white--text" class="mr-4" @click="register" :disabled="!valid">
                登録
                </v-btn>
                </template>
                </div>
                </v-window-item>

                <v-window-item :value="3">
                    <div class="pa-4 text-center">
                    <v-img
                        class="mb-4"
                        contain
                        height="128"
                        src="https://cdn.vuetifyjs.com/images/logos/v.svg"
                    ></v-img>
                    <h3 class="title font-weight-light mb-2">
                        Welcome to Vuetify
                    </h3>
                    <span class="caption grey--text">Thanks for signing up!</span>
                    </div>
                </v-window-item>
                </v-window>
<!--
                <v-divider></v-divider>

                <v-card-actions>
                <v-btn
                    :disabled="step === 1"
                    text
                    @click="step--"
                >
                    Back
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                    :disabled="step === 3"
                    color="primary"
                    depressed
                    @click="step++"
                >
                    Next
                </v-btn>
                </v-card-actions> -->
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['google-user', 'github-user'],
        data(){
            return{
                initial: true,
                showq: false,
                gUser: this.googleUser,
                hubUser: this.githubUser,
                avatar: '',
                show1: false,
                show2: false,
                show3: false,
                email: '',
                password: null,
                remember: '',
                errors: {},
                step: 1,
                randomNumber: '',
                registration: {name: '', screen_name: '', email: '', password: '', confirmationPassword: ''},
                show4: false,
                nameRules: [
                  v => !!v || '入力が必要です',
                ],
                nicknameRules: [
                  v => !!v || '入力が必要です',
                ],
                emailRules: [
                  v => !!v || '入力が必要です',
                  v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                passwordRules: {
                required: value => !!value || 'Required.',
                min: v => v.length >= 8 || 'Min 8 characters',
                },
                confirmationPasswordRules: {
                match: v => v == this.registration.password || 'no match',
                },
                valid: false,
                registrationErrors: {},
                // registrationErrors: '',
            }
        },
        computed: {
            currentTitle () {
                switch (this.step) {
                case 1: return 'ログイン'
                case 2: return '会員登録'
                default: return 'Account created'
                }
            }
        },
        created() {
            this.randomNumber =  Math.floor( Math.random () * 5)+1;
        },
        mounted(){
            this.focusInput();
            if(this.gUser){
                this.step = 2;
                this.registration.name = this.gUser.name;
                this.gUser.nickname ? this.registration.screen_name = this.gUser.nickname : this.registration.screen_name = this.gUser.name;
                this.registration.email = this.gUser.email;
                var that = this;
                that.toBase64Url(that.gUser.avatar, function(base64Url){
                    that.avatar = base64Url;
                });
            } else if(this.hubUser){
                this.step = 2;
                this.registration.name = this.hubUser.name;
                this.hubUser.name ? this.registration.name = this.hubUser.name : this.registration.name = this.hubUser.nickname;
                this.hubUser.nickname ? this.registration.screen_name = this.hubUser.nickname : this.registration.screen_name = this.hubUser.name;
                this.registration.email = this.hubUser.email;
                var that = this;
                that.toBase64Url(that.hubUser.avatar, function(base64Url){
                    that.avatar = base64Url;
                });
            }
            console.log(this.hubUser)
        },
        methods: {
            toBase64Url(url, callback){
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var reader = new FileReader();
                reader.onloadend = function() {
                callback(reader.result);
                }
                reader.readAsDataURL(xhr.response);
            };
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
            },
            focusInput() {
                this.$refs.focusThis.focus();
            },
            blurInput() {
              var registrationArray = Object.values(this.registration).map(registration => {
                return registration;
              })
              this.initial = registrationArray.some((registration) => {
                return registration === '';
              })
            },
            login(){
            this.errors = {};
            var self = this;
            var url = '/login';
            var params = {
                email: this.email,
                password: this.password,
                remember: this.remember
            };
            axios.post(url, params)
                .then(function(response){
                    location.href = '/home';
                })
                .catch(function(error){
                    var responseErrors = error.response.data.errors;
                    var errors = {};
                    console.log(responseErrors)
                    for(var key in responseErrors) {
                        errors[key] = responseErrors[key][0];
                    }
                    self.errors = errors;
                });
            },
            register() {
                var url = '/register';
                var params = {
                    name: this.registration.name,
                    screen_name: this.registration.screen_name,
                    email: this.registration.email,
                    password: this.registration.password,
                    password_confirmation: this.registration.confirmationPassword,
                    profile_image: this.avatar
                };
                axios.post(url, params)
                .then(() => location.href = '/home')
                .catch((error) => {
                    // this.registrationErrors = error.response.data.errors;
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

<style>
.example {
  position: relative;
  overflow: hidden;
  }

.example .hello {
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%,-50%);
  -webkit-transform: translate(-50%,-50%);
  transform: translate(-50%,-50%);
  padding:0;
  width: 100%;
  max-width: 400px;
  background-color: white;
  /*文字の装飾は省略*/
  }

.example #main {
  width: 100%;
  min-width: 1185px;
  }
.example #logo {
  width: 100%;
  max-width: 757px;
  }
  #login input {
      margin: 0;
  }

  #login input:-webkit-autofill {
    background-color: white!important;
    color: white!important;
        animation-name: onAutoFillStart;
    transition: background-color 50000s ease-in-out 0s;
}

.btn-google:hover .g {
  color: rgba(66, 134, 244, 1);
  transition: color 0.5s!important;
}
.btn-google .g {

  transition: color 3s!important;
}

.btn-google:hover .o {
  color: rgba(219, 69, 55, 1);
  transition: color 1s!important;
}

.btn-google .o {
  transition: color 2.5s!important;
}

.btn-google:hover .o2 {
  color: rgba(244, 179, 0, 1);
  transition: color 1.5s!important;
}

.btn-google .o2 {
  transition: color 2s!important;
}

.btn-google:hover .g2 {
  color: rgba(66, 134, 244, 1);
    transition: color 2s!important;
}

.btn-google .g2 {
    transition: color 1.5s!important;
}

.btn-google:hover .l {
  color: rgba(15, 157, 88, 1);
    transition: color 2.5s!important;
}

.btn-google .l {
    transition: color 1s!important;
}

.btn-google:hover .e {
  color: rgba(219, 69, 55, 1);
    transition: color 3s!important;
}

.btn-google .e {
    transition: color 0.5s!important;
}


.bounce-enter-active {
  animation: bounce-in .5s;
}
.bounce-leave-active {
  animation: bounce-in .5s reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.5);
  }
  100% {
    transform: scale(1);
  }
}
  #gitbtn {
      /* background-color: black!important; */
      transition: background-color 1s!important;
  }
  #gitbtn:hover {
      background-color: transparent!important;
      transition: background-color 1s!important;
  }
  </style>
