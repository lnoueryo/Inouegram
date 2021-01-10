<template>
    <div>
        <div v-if="parsedUserPosts">
            <v-layout row wrap class="justify-end" style="margin: auto;">
                <v-hover v-for="(parsedUserPost, index) in parsedUserPosts" :key="index" v-slot="{ hover }">
                    <v-card class="mx-auto my-4" color="grey lighten-4" :max-width="card.size3" style="width: 100%">
                        <v-img :aspect-ratio="14/12" :src="'storage/image/' + parsedUserPost.image[0]" @click="openDialog(parsedUserPost, index)">
                            <v-expand-transition>
                            <div v-if="hover" class="d-flex transition-fast-in-fast-out black darken-2 v-card--reveal display-3 white--text" style="height: 100%;">
                                <p style="font-size: 25px">{{parsedUserPost.image.length}}枚</p>
                            </div>
                            </v-expand-transition>
                        </v-img>
                    </v-card>
                </v-hover>
                <v-dialog v-model="dialog" :max-width="card.size2" @click:outside="outside">
                    <v-card color="grey lighten-4" :max-width="card.size2" style="overflow-y: hidden; max-height: 750px;">
                        <v-carousel :height="dialogSize" v-model="carousel[postDialogIndex]">
                            <v-carousel-item v-for="(postDialogImage,index) in postDialog.image" :key="index" :src="'storage/image/' + postDialogImage" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                        </v-carousel>
                        <card-content ref="cardContent" :isDeleteBtn="isDeleteBtn" @bar="bar($event)"></card-content><!-- コンポーネント-->
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>
        <div v-else class="text-center" style="margin: auto;">
            まだ投稿はありません<br>
            <v-btn href="/post" v-if="isMainUser">投稿する</v-btn>
        </div>
        <v-snackbar v-model="snackbar" :timeout="timeout">
            削除しました
            <template v-slot:action="{ attrs }">
                <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
                閉じる
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script lang="ts">
    import Vue from "vue";
    import CardContent from "../../molecules/profile/user_posts/CardContent.vue";
    export type DataType = {
        dialog: boolean,
        carousel: string[],
        deleteDialog: boolean,
        lastPostId: number | string,
        lastIndex: number | string,
        isDeleteBtn: boolean[] | string,
        snackbar: boolean,
        timeout: number,
    }
    export type card = {size1: number, size2: number, size3: number}
    export type cardContent = {comment: string} | any
    export default Vue.extend({
    components:{
        CardContent
    },
    data (): DataType {
        return {
            dialog: false,
            carousel: [],
            deleteDialog: false,
            lastPostId: '',
            lastIndex: '',
            isDeleteBtn: '',
            snackbar: false,
            timeout: 2000,
        }
    },
    computed:{
        isMainUser(): boolean{
            return this.$store.getters.isMainUser;
        },
        parsedUserPosts(): boolean | string[]{
            const userPosts = this.$store.getters.requestedUser.posts;
            if(!userPosts){
                return false;
                } else {
                for(let i=0; i<userPosts.length; i++){
                    userPosts[i].image = JSON.parse(userPosts[i].image);
                }
                return userPosts;
            }
        },
        card(): card{
            const windowSize = this.$store.getters.windowSize;
            if(windowSize.x < 480){
                return {size1: 350, size2: 300, size3: 110};
            } else {
                return {size1: 600, size2: 500, size3: 350};
            }
        },
        dialogSize(): number{
            const windowSize = this.$store.getters.windowSize;
            if(windowSize.x < 480){
                return 300;
            } else {
                return 500;
            }
        },
        postDialog(){
            return this.$store.getters.postDialog;
        },
        postDialogIndex(){
            return this.$store.getters.postDialogIndex;
        },
    },
    methods: {
        openDialog(postDialog: {comments: string[]}, postDialogIndex: number): void{
            this.$store.commit('postDialog',postDialog)
            this.$store.commit('postDialogIndex',postDialogIndex)
            this.dialog = true;
            const isDeleteBtn = postDialog.comments.map((commentUser) => {
                return false;
            })
            this.isDeleteBtn = isDeleteBtn;
        },
        outside(): void{
            let element: cardContent = this.$refs.cardContent as InstanceType<typeof CardContent>;
            element.comment = '';
        },
        bar(event: {dialog: boolean, snackbar: boolean}){
            this.dialog = event.dialog;
            this.snackbar = event.snackbar;
        }
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

.pointer {
    cursor: pointer;
}

.v-list-item__action{
    display: inline-flex;
    min-width: 70px!important;
}
</style>


