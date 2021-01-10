<template>
    <div>
        <v-list subheader style="max-height: 180px; overflow-y: scroll;">
        <v-subheader>コメント</v-subheader>
            <div v-for="(postComment, index) in comments" :key="index" @touchstart="toggleDeleteBtn(postComment.user.id, index)" @mouseover="showDeleteBtn(postComment.user.id, index)" @mouseleave="hideDeleteBtn(postComment.user.id, index)">
                <v-list-item v-if="postComment">
                    <a :href="'/profile?id=' + postComment.user.id">
                    <v-list-item-avatar>
                    <v-img :src="'storage/image/avatar/' + postComment.user.profile_image"></v-img>
                    </v-list-item-avatar>
                    </a>
                    <v-list-item-content>
                    <v-list-item-title v-text="postComment.user.screen_name"></v-list-item-title>
                    {{ postComment.text }}
                    </v-list-item-content>
                    <v-list-item-action v-if="postComment.user.id==visitor.id">
                    <transition name="slide-fade">
                            <v-btn color="success" @click="deleteComment(postComment)" v-if="propDeleteBtn[index]">削除</v-btn>
                    </transition>
                    </v-list-item-action>
                </v-list-item>
            </div>
        </v-list>
    </div>
</template>

<script lang="ts">
import Vue from "vue";
import axios from "axios";
export type DataType = {
    propDeleteBtn: boolean[],
}
export default Vue.extend({
    props: ['comments', 'deleteBtn'],
    data(): DataType{
        return {
            propDeleteBtn: this.deleteBtn
        }
    },
    computed: {
        visitor(): string[]{
            return this.$store.getters.visitor;
        },
    },
    methods: {
        showDeleteBtn(id: number, index: number){
            if(id==this.$store.getters.visitor.id){
                this.propDeleteBtn[index] = true;
                this.propDeleteBtn = this.propDeleteBtn.map((el: boolean, key: number) => {
                    return el;
                })
            }
        },
        hideDeleteBtn(id: number, index: number){
            if(id==this.$store.getters.visitor.id){
                this.propDeleteBtn[index] = false;
                this.propDeleteBtn = this.propDeleteBtn.map((el: boolean, key: number) => {
                    return el;
                })
            }
        },
        toggleDeleteBtn(id: number, index: number){
            if(id==this.$store.getters.visitor.id){
                this.propDeleteBtn[index] = !this.propDeleteBtn[index];
                this.propDeleteBtn = this.propDeleteBtn.map((el: boolean, key: number) => {
                    return el;
                })
            }
        },
        deleteComment(postComment: {id: number}){
            axios.post('/api/delete_comment', {
                id: postComment.id,
            })
            .then(response => {
                this.findDeleteComment(response.data);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        findDeleteComment(res: {id: number}){
            const postDialogIndex = this.$store.getters.postDialog.comments.findIndex((comment: {id: number}) => {
                return comment.id === res.id;
            })
            this.$store.getters.postDialog.comments.splice(postDialogIndex, 1);
            const visitorComments = this.$store.getters.visitor.comments.findIndex((comment: {id: number}) => {
                return comment.id === res.id;
            })
            this.$store.getters.visitor.comments.splice(visitorComments, 1);
        },
    }
})
</script>