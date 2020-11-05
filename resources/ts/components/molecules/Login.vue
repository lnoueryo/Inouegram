<template>
    <div id="login" class="example">
        <div>
        <img  class="px-5 py-5" id="logo" src="/image/mymemories.png" style="position: absolute; z-index: 1;">
        </div>
        <img id="main" src="/image/login2.png" style="width: 100%; position: relative">
        <div class="hello">
            <v-card class="mx-1 my-1 px-5 py-5" max-width="400" tile>
              <v-form ref="form" lazy-validation>
                <h4 class="py-2">ログイン</h4>
                <v-text-field class="py-2" v-model="email" placeholder="アドレス" label="Email" required clearable></v-text-field>
                <div class="alert alert-danger" v-text="errors.email" v-if="errors.email"></div>
                <v-text-field v-model="password" placeholder="パスワード" :counter="10" label="Password" required @click:append="show = !show" :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'" :type="show ? 'text' : 'password'"></v-text-field>
                <div class="alert alert-danger" v-text="errors.password" v-if="errors.password"></div>
                <v-btn color="success" class="mr-4" @click="login">
                ログイン
                </v-btn>
            </v-form>
            </v-card>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                show: false,
                email: '',
                password: '',
                remember: '',
                errors: {},
            }
        },
        mounted() {
            console.log('Component mounted.');
        },
        methods: {
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
            logout() {
            axios.post('/logout')
            .then(() => location.href = '/home')
            .catch(function (error) {
                location.href = '/home';
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
