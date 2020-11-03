<template>
    <div class="row">
        <div class="position: absolute;right: 0;" v-if="user!==null">
            <br>
            <h4>ログイン</h4>
            <br>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" v-model="email">
                <div class="alert alert-danger" v-text="errors.email" v-if="errors.email"></div>
            </div>
            <div class="form-group">
                <label>パスワード</label>
                <input type="password" class="form-control" v-model="password">
                <div class="alert alert-danger" v-text="errors.password" v-if="errors.password"></div>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" v-model="remember">
                </label>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-dark btn-block" @click="login">ログイン</button>
            </div>
        </div>
        <div v-else>
            <v-btn @click="logout"></v-btn>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                email: '',
                password: '',
                remember: '',
                errors: {},
            }
        },
        mounted() {
            console.log('Component mounted.')
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
