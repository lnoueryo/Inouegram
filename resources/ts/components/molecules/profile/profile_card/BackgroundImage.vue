<template>
    <div>
        <div v-if="requestedUserInfo.bg_image">
            <v-img style="border-radius: 5px" :aspect-ratio="16/9" :src="'storage/image/background/' + requestedUserInfo.bg_image" @click="btnclick"></v-img>
        </div>
        <div v-else>
            <v-img src="storage/image/background/bg-1.jpg" @click="btnclick"></v-img>
        </div>
        <input style="display: none" ref="bg" type="file" accept="image/jpeg, image/jpg, image/png" @input="upload">
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
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import axios from 'axios';
export type DataType = {
    user: any,
    visitor: any,
    windowSize: { x: number, y: number},
    changeBgDialog: boolean,
    changingBgData: string
}
export default Vue.extend({
    data(): DataType{
        return {
            user: '',
            visitor: '',
            windowSize: this.$store.getters.windowSize,
            changeBgDialog: false,
            changingBgData: '',
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
        async changeBg(){
            const bgData = this.changingBgData;
            const userId = this.user.id;
            const fd= new FormData();
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
        upload(event: any){
            if(event.target){
            const file = event.target.files[0];
                if(this.windowSize.x<480){
                    if(file.size<3100000){
                        this.$emit('progress', true);
                        const reader = new FileReader();
                        const that = this;
                            reader.onload = function(e: any) {
                                that.changingBgData = e.target.result;
                                that.changeBgDialog = true;
                                that.$emit('progress', false);
                            }
                            reader.readAsDataURL(file);
                    } else {
                        this.$emit('sizeDialog', true);
                    }
                } else {
                    this.$emit('progress', true);
                    const reader = new FileReader();
                    const that = this;
                        reader.onload = function(e: any) {
                            that.changingBgData = e.target.result;
                            that.changeBgDialog = true;
                            that.$emit('progress', false);
                        }
                    reader.readAsDataURL(file);
                }
            } else {
                this.changeBgDialog = false;
            }
        },
        btnclick() {
            if(this.visitor.id == this.user.id){
                const element: HTMLElement | any = this.$refs.bg;
                element.click()
            }
        },
    }
})
</script>