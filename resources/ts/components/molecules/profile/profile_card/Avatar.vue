<template>
    <div>
        <v-avatar color="indigo" size="80" @click="btnclick2">
            <v-img :src="'storage/image/avatar/' +  requestedUserInfo.profile_image"></v-img>
        </v-avatar>
        <input style="display: none" ref="avatar" type="file" accept="image/jpeg, image/jpg, image/png" @input="changeAvatar">
    </div>
</template>

<script>
export default {
    props: ['requestedUser', 'mainUser'],
    data() {
        return {
            user: this.requestedUser,
            visitor: this.mainUser,
            windowSize: this.$store.getters.windowSize
        }
    },
    watch: {
        user: {
            immediate: true,
            handler(){
                this.user = this.requestedUser;
            }
        },
        visitor: {
            immediate: true,
            handler(){
                this.visitor = this.mainUser;
            }
        }
    },
    computed: {
        requestedUserInfo(){
            return this.user;
        }
    },
    methods: {
        btnclick2() {
            if(this.visitor.id == this.user.id){
                let element = this.$refs.avatar;
                element.click()
            }
        },
        async changeAvatar(event){
            var file = event.target.files[0];
            if (typeof FileReader === 'function') {
                if(this.windowSize.x<480){
                    if(file.size<3100000){
                        this.progress = true;
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        var avatarData;
                        var that = this;
                        reader.onload = function(e) {
                            avatarData = e.target.result;
                            let fd= new FormData();
                            fd.append("avatarData", avatarData);
                            fd.append("userId", that.user.id);
                            axios.post('/api/upload2', fd)
                            .then(
                                response => {
                                    that.user.profile_image = response.data;
                                    that.progress = false;
                                }
                            )
                            .catch(function (error) {
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
                    reader.onload = function(e) {
                        avatarData = e.target.result;
                        let fd= new FormData();
                        fd.append("avatarData", avatarData);
                        fd.append("userId", that.user.id);
                        axios.post('/api/upload2', fd)
                        .then(
                            response => {
                                that.user.profile_image = response.data;
                            }
                        )
                        .catch(function (error) {
                            console.log(error);
                        });
                    }
                }
            }
        },
    }
}
</script>