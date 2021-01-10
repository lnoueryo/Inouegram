<template>
    <div>
        <v-avatar color="indigo" size="80" @click="btnclick2">
            <v-img :src="'storage/image/avatar/' +  requestedUserInfo.profile_image"></v-img>
        </v-avatar>
        <input style="display: none" ref="avatar" type="file" accept="image/jpeg, image/jpg, image/png" @input="changeAvatar">
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import axios from 'axios';
export type DataType = {
    user: any,
    visitor: any,
    windowSize: { x: number, y: number},
}
export default Vue.extend({
    data(): DataType{
        return {
            user: '',
            visitor: '',
            windowSize: this.$store.getters.windowSize
        }
    },
    watch: {
        user: {
            immediate: true,
            handler(){
                this.user = this.$store.getters.requestedUser;
            }
        },
        visitor: {
            immediate: true,
            handler(){
                this.visitor = this.$store.getters.visitor;
            }
        }
    },
    computed: {
        requestedUserInfo(): object{
            return this.user;
        }
    },
    methods: {
        btnclick2(): void {
            if(this.visitor.id == this.user.id){
                const element: HTMLElement | any = this.$refs.avatar;
                element.click()
            }
        },
        async changeAvatar(event: any): Promise<any>{
            const file = event.target.files[0];
            if (typeof FileReader === 'function') {
                if(this.windowSize.x<480){
                    if(file.size<3100000){
                        this.$emit('progress', true);
                        const reader = new FileReader();
                        reader.readAsDataURL(file);
                        const that = this;
                        reader.onload = function(e: any) {
                            const avatarData = e.target.result;
                            const fd= new FormData();
                            fd.append("avatarData", avatarData);
                            fd.append("userId", that.user.id);
                            axios.post('/api/upload2', fd)
                            .then(
                                response => {
                                    that.user.profile_image = response.data;
                                    that.$emit('progress', false);
                                }
                            )
                            .catch(function (error) {
                                console.log(error);
                                that.$emit('progress', false);
                            });
                        }
                    } else {
                        this.$emit('sizeDialog', true);
                    }
                } else {
                    const reader = new FileReader();
                    const that = this;
                    reader.onload = function(e: any) {
                        const avatarData = e.target.result;
                        const fd= new FormData();
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
                    reader.readAsDataURL(file);
                }
            }
        },
    }
})
</script>