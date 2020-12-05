<template>
    <div>
        <div v-if="parsedLikePosts" v-resize="onResize">
            <v-layout row wrap class="justify-end" style="margin: auto;">
                <v-hover v-for="(parsedLikePost, index) in parsedLikePosts" :key="index" v-slot="{ hover }">
                    <v-card class="mx-auto my-4" color="grey lighten-4" :max-width="card.size3" style="width: 100%">
                        <v-img :aspect-ratio="14/12" :src="'storage/image/' + parsedLikePost.image[0].src" @click="openDialog(parsedLikePost, index)">
                            <v-expand-transition>
                            <div v-if="hover" class="d-flex transition-fast-in-fast-out black darken-2 v-card--reveal display-3 white--text" style="height: 100%;">
                                <p style="font-size: 25px">{{parsedLikePost.image.length}}枚</p>
                            </div>
                            </v-expand-transition>
                        </v-img>
                    </v-card>
                </v-hover>
                <!-- <v-dialog v-model="dialog" :max-width="card.size2">
                    <v-card color="grey lighten-4" :max-width="card.size2" style="overflow-y: hidden; max-height: 750px;">
                        <v-carousel :height="dialogSize" v-model="carousel[dialogPostIndex]">
                            <v-carousel-item v-for="(dialogPostImage,index) in dialogPost.image" :key="index" :src="'storage/image/' + dialogPostImage.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                        </v-carousel>
                        <v-window v-model="window" class="elevation-0">
                            <v-window-item :value="0">
                                <v-card flat>
                                    <v-card-text class="py-1" style="position: relative; max-width: 340px;">
                                        <p class="text-h6 font-weight-light orange--text mb-2" style="margin: 0!important;">
                                        {{ dialogPost.title }}
                                        </p>
                                        <div class="text-subtitle-1 font-weight-light grey--text title mb-2">
                                        <div class="content">{{ dialogPost.text }}</div>
                                        </div>
                                    </v-card-text>
                                    <v-menu v-model="menu[dialogPostIndex]" :close-on-content-click="true" :nudge-width="200" offset-y top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn class="pl-3" icon v-bind="attrs" v-on="on" :color="mainUserLikeBool ? 'pink' : ''">
                                            <v-icon>mdi-heart</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-card v-if="mainUserLikeBool">
                                            <v-card-actions>
                                                <v-btn v-for="(btn, index) in btns" :key="index" icon @click="like(index)" :color="(mainUserLike.reaction === index) ? btn.color : ''">
                                                    <v-icon>{{ btn.icon }}</v-icon>
                                                </v-btn>
                                                <v-btn icon @click="deleteLike(userPosts[dialogPostIndex].id, dialogPostIndex)">
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
                                    {{ likeNumber }}
                                    <v-btn icon>
                                    <v-icon :color="isMainUserComment(dialogPost.id) ? 'orange' : ''">mdi-comment</v-icon>
                                    </v-btn>
                                    {{ totalCommentNumber(dialogPost.id) }}人
                                    <v-btn icon>
                                    <v-icon>mdi-bookmark</v-icon>
                                    </v-btn>
                                    <v-btn absolute icon right @click="deleteDialog = true" v-if="btnclick3">
                                    <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <div class="d-flex align-center justify-space-around px-2">
                                        <v-text-field
                                        class="px-2 pt-2"
                                        color="purple darken-2"
                                        label="コメント"
                                        required
                                        v-model="comment"
                                        ></v-text-field>
                                    <v-btn @click="sendComment(dialogPost.id, dialogPostIndex)">投稿</v-btn>
                                    </div>
                                </v-card>
                            </v-window-item>

                            <v-window-item :value="1">
                                <v-list subheader style="max-height: 180px; overflow-y: scroll;">
                                <v-subheader>Recent chat</v-subheader>
                                    <div>
                                        <v-list-item v-for="(likedUser, index) in likedUsers" :key="index" :href="'/profile?id=' + likedUser.id">
                                            <v-list-item-avatar>
                                            <v-img :src="'storage/image/avatar/' + likedUser.profile_image"></v-img>
                                            </v-list-item-avatar>

                                            <v-list-item-content>
                                            <v-list-item-title v-text="likedUser.screen_name"></v-list-item-title>
                                            </v-list-item-content>

                                            <v-list-item-icon>
                                                <div v-if="iconType(likedUser.id) == 0"><v-icon color="yellow">mdi-emoticon</v-icon></div>
                                                <div v-else-if="iconType(likedUser.id) == 1"><v-icon color="blue">mdi-emoticon-cry</v-icon></div>
                                                <div v-else-if="iconType(likedUser.id) == 2"><v-icon color="orange">mdi-emoticon-lol</v-icon></div>
                                                <div v-else-if="iconType(likedUser.id) == 3"><v-icon color="red">mdi-emoticon-angry</v-icon></div>
                                                <div v-else><v-icon color="pink">mdi-emoticon-kiss</v-icon></div>
                                            </v-list-item-icon>
                                        </v-list-item>
                                    </div>
                                </v-list>
                            </v-window-item>
                            <v-window-item :value="2">
                                <v-list subheader style="max-height: 180px; overflow-y: scroll;">
                                <v-subheader>Recent chat</v-subheader>
                                <div v-for="(postComment, index) in postComments" :key="index">
                                    <v-list-item v-if="commentUser(postComment.user_id)">
                                        <v-list-item-avatar :href="'/profile?id=' + postComment.user_id">
                                        <v-img :src="'storage/image/avatar/' + commentUser(postComment.user_id).profile_image"></v-img>
                                        </v-list-item-avatar>
                                        <v-list-item-content>
                                        <v-list-item-title v-text="commentUser(postComment.user_id).screen_name"></v-list-item-title>
                                        </v-list-item-content>
                                        <div>{{ postComment.text }}
                                            <template v-if="commentUser(postComment.user_id).id==visitor.id">
                                                <v-btn color="success" @click="deleteComment(postComment)">削除</v-btn>
                                            </template>
                                            <template v-else>
                                                <v-btn color="success">a</v-btn>
                                            </template>
                                        </div>
                                    </v-list-item>
                                </div>
                                </v-list>
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
                    </v-card>
                </v-dialog>
                <v-dialog v-model="deleteDialog" max-width="290">
                    <v-card>
                        <v-card-title class="headline">
                        Make sure!!
                        </v-card-title>

                        <v-card-text>
                        Are you sure you are gonna delete this post??
                        </v-card-text>

                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" text @click="deleteDialog = false">
                            Disagree
                        </v-btn>

                        <v-btn color="green darken-1" text @click="deletePost(dialogPost)">
                            Agree
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-snackbar v-model="snackbar" :timeout="timeout">
                    {{ text }}
                    <template v-slot:action="{ attrs }">
                        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
                        Close
                        </v-btn>
                    </template>
                </v-snackbar>
                <v-dialog v-model="changeBgDialog" max-width="290">
                    <v-card>
                        <v-img :src="changingBgData"></v-img>
                        <v-card-text>
                        Do you wanna change to this image??
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" text @click="changeBgDialog = false">
                            No
                        </v-btn>
                        <v-btn color="green darken-1" text @click="changeBg()">
                            Yes
                        </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog> -->
            </v-layout>
        </div>
        <div v-else class="text-center" style="margin: auto;">
            まだ投稿はありません<br>
            <v-btn href="/post">投稿する</v-btn>
        </div>
    </div>
</template>

<script>
export default {
    props: ['likedPosts'],
    data() {
        return {
            likePosts: this.likedPosts,
            windowSize: {
                x: 0,
                y: 0,
            },
        }
    },
    computed: {
        parsedLikePosts(){
            var likePosts = this.likePosts;
            if(likePosts.length == 0){
                return false;
                } else {
                for(var i=0; i<likePosts.length; i++){
                    likePosts[i].image = JSON.parse(likePosts[i].image);
                }
                return likePosts;
            }
        },
        card(){
            if(this.windowSize.x < 480){
                return {size1: 350, size2: 300, size3: 110};
            } else {
                return {size1: 600, size2: 500, size3: 350};
            }
        },
    },
    methods: {
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
        },
    }
}
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

/* .theme--dark {
    background-image: linear-gradient(25deg, rgba(255, 0, 0, 0.7), rgba(0, 255, 0, 0.7));
    transition-duration: .7s;
}

.overlay{
    opacity: 0;
} */
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

</style>
<style>
.v-dialog {
    max-height: 700px!important;
}
</style>

