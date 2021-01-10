<template>
    <div>
        <v-menu v-model="menu[postDialogIndex]" :close-on-content-click="true" :nudge-width="200" offset-y top>
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="ml-3" icon v-bind="attrs" v-on="on" :color="mainUserLikeBool ? 'pink' : ''">
                <v-icon>mdi-heart</v-icon>
                </v-btn>
            </template>
            <v-card v-if="mainUserLikeBool">
                <v-card-actions>
                    <v-btn v-for="(btn, index) in btns" :key="index" icon @click="like(index)" :color="(visitorReaction.reaction === index) ? btn.color : ''">
                        <v-icon>{{ btn.icon }}</v-icon>
                    </v-btn>
                    <v-btn icon @click="deleteLike(postDialog.id, postDialogIndex)">
                        <v-icon>mdi-minus-circle</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
            <v-card v-else>
                <v-card-actions>
                    <v-btn v-for="(btn, index) in btns" :key="index" icon @click="like(index)">
                        <v-icon>{{ btn.icon }}</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
        <span class="pointer" @click="window = 1" v-if="postDialog">{{ postDialog.likes.length }}人</span>
        <v-btn icon>
        <v-icon :color="mainUserCommentBool ? 'orange' : ''">mdi-comment</v-icon>
        </v-btn>
        <span class="pointer" @click="window = 2" v-if="postDialog">{{ postDialog.comments.length }}人</span>
        <!-- <v-btn icon>
        <v-icon>mdi-bookmark</v-icon>
        </v-btn> -->
        <v-btn absolute icon right @click="deleteDialog = true" v-if="isMainUser">
        <v-icon>mdi-delete</v-icon>
        </v-btn>
        <v-dialog v-model="deleteDialog" max-width="290">
            <v-card>
                <v-card-title class="headline">
                削除
                </v-card-title>

                <v-card-text>
                こちらの投稿を削除してもよろしいですか？
                </v-card-text>

                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="deleteDialog = false">
                    いいえ
                </v-btn>

                <v-btn color="green darken-1" text @click="deletePost(postDialog)">
                    はい
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts">
import { mapGetters } from 'vuex'
import Vue from 'vue'
import axios from 'axios'
export type DataType = {
    menu: string[],
    btns: btns[],
    lastPostId: number | string,
    lastIndex: number | string,
    deleteDialog: boolean
}
export type btns = {color: string, icon: string}
export type like = {id: number, post_id: number, reaction: number, user_id: number}
export type comment = {id: number, post_id: number, text: string, user_id: number}
export type post = {id: number, user_id: number}
export default Vue.extend({
    props: ['isDeleteBtn'],
    data(): DataType{
        return {
            menu: [],
            btns: [
                {color: 'yellow', icon: 'mdi-emoticon'},
                {color: 'blue', icon: 'mdi-emoticon-cry'},
                {color: 'orange', icon: 'mdi-emoticon-lol'},
                {color: 'red', icon: 'mdi-emoticon-angry'},
                {color: 'pink', icon: 'mdi-emoticon-kiss'},
            ],
            lastPostId: '',
            lastIndex: '',
            deleteDialog: false,
        }
    },
    computed:{
        ...mapGetters([
        'postDialog',
        'postDialogIndex',
        'isMainUser',
        ]),
        deleteBtn(): boolean[]{
            return this.isDeleteBtn;
        },
        mainUserLikeBool(): boolean{
            return this.$store.getters.visitor.likes.some((like: like) => {
                return like.post_id === this.$store.getters.postDialog.id;
            })
        },
        visitorReaction(): like{
            return this.$store.getters.postDialog ? this.$store.getters.visitor.likes.find((like: like) => {
                return like.post_id === this.postDialog.id;
            }) : '';
        },
        mainUserCommentBool(): boolean{
            return this.$store.getters.visitor.comments.some((comment: comment) => {
                return comment.post_id === this.$store.getters.postDialog.id;
            })
        },
    },
    methods: {
        like(index: number): void{
            axios.post('/api/like', {
                postId: this.$store.getters.postDialog.id,
                userId: this.$store.getters.visitor.id,
                requestedUserId: this.$store.getters.requestedUser.id,
                reaction: index,
            })
            .then(response => {
                this.lastPostId = this.$store.getters.postDialog.id;
                this.lastIndex = index;
                this.checkLikeObj(response.data);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        deleteLike(thisPostId: number, index: number){
            axios.post('/api/delete_like', {
                postId: thisPostId,
                userId: this.$store.getters.visitor.id,
                requestedUserId: this.$store.getters.requestedUser.id,
                reaction: index,
            })
            .then(response => {
                this.findDeleteLike(response.data);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        checkLikeObj(res: like){
            const isPostDialogLike = this.$store.getters.postDialog.likes.find((like: like) => {
                return like.user_id === this.$store.getters.visitor.id;
            })
            const isUserLike = this.$store.getters.visitor.likes.find((like: like) => {
                return like.post_id === this.$store.getters.postDialog.id;
            })
            if(isPostDialogLike){
                isPostDialogLike.reaction = res.reaction;
            } else {
                this.$store.getters.postDialog.likes.push(res);
            }
            if(isUserLike){
                isUserLike.reaction = res.reaction;
            } else {
                this.$store.getters.visitor.likes.push(res);
            }
        },
        findDeleteLike(res: like){
            const postDialogIndex = this.$store.getters.postDialog.likes.findIndex((like: like) => {
                return like.id === res.id;
            })
            this.$store.getters.postDialog.likes.splice(postDialogIndex, 1);
            const visitorLikes = this.$store.getters.visitor.likes.findIndex((like: like) => {
                return like.id === res.id;
            })
            this.$store.getters.visitor.likes.splice(visitorLikes, 1);
        },
        deletePost(postDialog: post){
            let fd= new FormData();
            fd.append("post", JSON.stringify(postDialog));
            axios.post('/api/delete_post', fd)
            .then(
                response => {
                    this.$store.commit('posts', response.data);
                    this.$emit('bar', {dialog: false, snackbar: true});
                    this.deleteDialog = false;
                }
            )
            .catch(function (error) {
                console.log(error);
            });
        },
    }
})
</script>