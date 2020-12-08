<template>
    <div id="login" class="example">
        <div>
        <img  class="px-5 py-5" id="logo" src="/image/mymemories.png" style="position: absolute; z-index: 1;"/>
        </div>
        <img id="main" :src="'/image/login'+randomNumber+'.png'" style="width: 100%; position: relative">
        <div class="hello" style="opacity: 0.95;">
            <!-- <v-card class="mx-1 my-1 px-5 py-5" max-width="400" tile>
              <v-form ref="form" lazy-validation>
                <h4 class="py-2">ログイン</h4>
                <v-text-field class="py-2" v-model="email" placeholder="アドレス" label="Email" required clearable></v-text-field>
                <div class="alert alert-danger" v-text="errors.email" v-if="errors.email"></div>
                <v-text-field v-model="password" placeholder="パスワード" :counter="10" label="Password" required @click:append="show = !show" :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'" :type="show ? 'text' : 'password'"></v-text-field>
                <div class="alert alert-danger" v-text="errors.password" v-if="errors.password"></div>
                <div class="d-flex" style="justify-content: space-between;">
                <v-btn color="success" class="mr-4" @click="login">
                ログイン
                </v-btn>
                <v-btn color="indigo white--text" class="mr-4" @click="login">
                会員登録
                </v-btn>
                </div>
            </v-form>
            </v-card> -->
            <v-card
                class="mx-auto"
                max-width="500"
            >
                <v-card-title class="title font-weight-regular justify-space-between">
                <h4>{{ currentTitle }}</h4>
                <v-avatar
                    color="primary lighten-2"
                    class="subheading white--text"
                    size="24"
                    v-text="step"
                ></v-avatar>
                </v-card-title>

                <v-window class="px-5 py-5" v-model="step">
                <v-window-item :value="1">
                <v-form ref="form" lazy-validation>
                <!-- <h4 class="py-2">ログイン</h4> -->
                <v-text-field class="py-2" v-model="email" placeholder="アドレス" label="Email" required clearable @keyup.enter.exact="login"></v-text-field>
                <div class="alert alert-danger" v-text="errors.email" v-if="errors.email"></div>
                <v-text-field ref="focusThis" v-model="password" placeholder="パスワード" :counter="10" label="Password" required @click:append="show = !show" :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'" :type="show ? 'text' : 'password'" @keyup.enter.exact="login"></v-text-field>
                <div class="alert alert-danger" v-text="errors.password" v-if="errors.password"></div>
                <div class="d-flex mb-5 justify-content-start">
                <v-btn color="success" class="mr-4" @click="login">
                ログイン
                </v-btn>
                <v-btn color="indigo white--text" @click="step++">
                会員登録
                </v-btn>
                </div>
                <div>
                    <v-btn href="/auth/google" block style="max-width: 280px">google</v-btn>
                </div>
            </v-form>
                </v-window-item>

                <v-window-item :value="2">
        <v-card-text>
          <v-text-field
            ref="name"
            v-model="registration.name"
            :rules="[() => !!name || 'This field is required']"
            :error-messages="errorMessages"
            label="名前"
            placeholder="井上領"
            required
            class="py-2"
          ></v-text-field>
          <v-text-field
            ref="screen_name"
            v-model="registration.screen_name"
            :rules="[() => !!registration.screen_name || 'This field is required']"
            :error-messages="errorMessages"
            label="ニックネーム"
            placeholder="Rio"
            required
            class="py-2"
          ></v-text-field>
          <v-text-field
            autocomplete="new-password"
            v-model="registration.email"
            :rules="[
              () => !!registration.email || 'This field is required',
              () => !!registration.email && registration.email.length <= 25 || 'Address must be less than 25 characters',
              addressCheck
            ]"
            label="メールアドレス"
            placeholder="mymemories@hello.com"
            counter="25"
            required
            class="py-2"
          ></v-text-field>
          <!-- <v-text-field
            ref="city"
            v-model="city"
            :rules="[() => !!city || 'This field is required', addressCheck]"
            label="City"
            placeholder="El Paso"
            required
            class="py-2"
          ></v-text-field>
          <v-text-field
            ref="state"
            v-model="state"
            :rules="[() => !!state || 'This field is required']"
            label="State/Province/Region"
            required
            placeholder="TX"
            class="py-2"
            autocomplete="new-password"
          ></v-text-field> -->
          <v-text-field
            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show ? 'text' : 'password'"
            name="input-10-2"
            label="パスワード"
            hint="At least 8 characters"
            autocomplete="new-password"
            class="input-group--focused py-2"
            @click:append="show = !show"
            v-model="registration.password"
          ></v-text-field>
          <v-text-field
            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show ? 'text' : 'password'"
            name="input-10-2"
            label="パスワード確認"
            hint="At least 8 characters"
            autocomplete="new-password"
            class="input-group--focused py-2"
            @click:append="show = !show"
            @keyup.enter.exact="register"
            v-model="registration.confirmationPassword"
          ></v-text-field>
        </v-card-text>
                <div class="d-flex" style="justify-content: space-between;">
                <v-btn color="white" class="mr-4" @click="step--">
                戻る
                </v-btn>
                <v-btn color="indigo white--text" class="mr-4" @click="register">
                登録
                </v-btn>
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
        props: ['google-user'],
        data(){
            return{
                gUser: this.googleUser,
                avatar: '',
                show: false,
                email: '',
                password: null,
                remember: '',
                errors: {},
                step: 1,
                randomNumber: '',
                registration: {name: '', screen_name: '', email: '', password: '', confirmationPassword: ''},
                show4: false,
                rules: {
                required: value => !!value || 'Required.',
                min: v => v.length >= 8 || 'Min 8 characters',
                emailMatch: () => (`The email and password you entered don't match`),
                },
            }
        },
        computed: {
            currentTitle () {
                switch (this.step) {
                case 1: return 'ログイン'
                case 2: return '会員登録'
                default: return 'Account created'
                }
            },
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
            }
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
                .catch(function (error) {
                    console.log(error)
                    .then(() => location.href = '/login')
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
  </style>
