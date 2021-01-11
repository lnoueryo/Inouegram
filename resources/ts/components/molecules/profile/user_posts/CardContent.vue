<template>
    <div>
        <v-window v-model="window" class="elevation-0">
            <v-window-item :value="0">
                <v-card flat>
                    <postTitle :postDialog="postDialog"></postTitle>
                    <buttons :isDeleteBtn="isDeleteBtn" @bar="bar($event)"></buttons>
                    <div class="d-flex align-center justify-space-around px-2">
                        <v-text-field
                        class="px-2 pt-2"
                        color="purple darken-2"
                        label="コメント"
                        required
                        v-model="comment"
                        ></v-text-field>
                    <v-btn @click="sendComment(postDialog.id, postDialogIndex)">投稿</v-btn>
                    </div>
                </v-card>
            </v-window-item>

            <v-window-item :value="1">
                <like-list :likes="postDialog.likes"></like-list>
            </v-window-item>
            <v-window-item :value="2">
                <comment-list :comments="postDialog.comments" :deleteBtn="isDeleteBtn"></comment-list>
            </v-window-item>
        </v-window>
        <v-card-actions class="justify-space-between">
            <v-btn text @click="prev">
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-item-group v-model="window" class="text-center" mandatory>
                <v-item v-for="n in length" :key="`btn-${n}`" v-slot:default="{ active, toggle }">
                    <v-btn :input-value="active" icon @click="toggle">
                        <v-icon>mdi-record</v-icon>
                    </v-btn>
                </v-item>
            </v-item-group>
            <v-btn text @click="next">
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
        </v-card-actions>
    </div>
</template>

<script lang="ts">
import Vue from "vue"
import axios from "axios"
import postTitle from "../../../atoms/profile/user_posts/card_content/postTitle.vue";
import Buttons from "../../../atoms/profile/user_posts/card_content/Buttons.vue";
import LikeList from "../../../atoms/profile/user_posts/card_content/LikeList.vue";
import CommentList from "../../../atoms/profile/user_posts/card_content/CommentList.vue";
import { mapGetters } from 'vuex'
export type DataType = {
    window: number,
    menu: boolean[],
    comment: string,
    length: number,
    lastPostId: number | string,
    lastIndex: number | string,
    deleteDialog: boolean
}
export default Vue.extend({
    props: ['isDeleteBtn'],
    components: {
        postTitle,
        Buttons,
        LikeList,
        CommentList
    },
    data(): DataType{
        return {
            window: 0,
            menu: [],
            comment: '',
            length: 3,
            lastPostId: '',
            lastIndex: '',
            deleteDialog: false,
        }
    },
    computed:{
        ...mapGetters([
        'visitor',
        'postDialog',
        'postDialogIndex',
        ]),
        parsedUserPosts(): boolean | string[]{
            let userPosts = this.$store.getters.user.posts;
            if(!userPosts){
                return false;
                } else {
                for(let i=0; i<userPosts.length; i++){
                    userPosts[i].image = JSON.parse(userPosts[i].image);
                }
                return userPosts;
            }
        },
    },
    methods: {
        bar(event: {dialog: boolean, snackbar: boolean}): void{
            this.$emit("bar", event)
        },
        next(): void{
            this.window = this.window + 1 === this.length
            ? 0
            : this.window + 1
        },
        prev():void {
            this.window = this.window - 1 < 0
            ? this.length - 1
            : this.window - 1
        },
        sendComment(postId: number, index: number){
            if (this.comment) {
                axios.post('/api/comment', {
                    postId: postId,
                    userId: this.visitor.id,
                    text: this.comment,
                })
                .then(response => {
                    this.comment = '',
                    this.lastPostId = postId;
                    this.lastIndex = index;
                    this.window = 2;
                    this.$store.getters.postDialog.comments.unshift(response.data);
                })
                .catch(error => {
                    console.log('fail')
                })
            }
        },
    }
})
</script>
<style scoped>


.slide-fade-enter-active,
.slide-fade-leave-active,
.slide-fade-move {
  transition: 500ms cubic-bezier(0.59, 0.12, 0.34, 0.95);
  transition-property: opacity, transform;
    transition: all 0.8s ease;
}

.slide-fade-enter {
  opacity: 0;
  transform: translateX(50px) scaleY(0.5);
    transition: all 0.8s ease;
}

.slide-fade-enter-to {
  opacity: 1;
  transform: translateX(0) scaleY(1);
    transition: all 0.8s ease;
}

.slide-fade-leave-active {
  position: absolute;
    transition: all 0.8s ease;
}

.slide-fade-leave-to {
  opacity: 0;
  transform: scaleY(0);
  transform-origin: center top;
    transition: all 0.8s ease;
}

.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: .5;
  position: absolute;
  width: 100%;
}
.content{
    overflow: hidden;
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.pointer {
    cursor: pointer;
}
.slide-fade-enter-active {
    transition: all .6s ease;
}
.slide-fade-leave-active {
    transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to {
    transform: translateY(10px);
    opacity: 0;
}
.v-list-item__action{
    display: inline-flex;
    min-width: 70px!important;
}
</style>