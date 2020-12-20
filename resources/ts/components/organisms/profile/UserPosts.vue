<template>
    <div>
        <div v-if="parsedUserPosts" v-resize="onResize">
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
                        <v-window v-model="window" class="elevation-0">
                            <v-window-item :value="0">
                                <v-card flat>
                                    <v-card-text class="py-1" style="position: relative; max-width: 340px;">
                                        <p class="text-h6 font-weight-light orange--text mb-2" style="margin: 0!important;">
                                        {{ postDialog.title }}
                                        </p>
                                        <div class="text-subtitle-1 font-weight-light grey--text title mb-2">
                                        <div class="content">{{ postDialog.text }}</div>
                                        </div>
                                    </v-card-text>
                                    <v-menu v-model="menu[postDialogIndex]" :close-on-content-click="true" :nudge-width="200" offset-y top>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn class="ml-3" icon v-bind="attrs" v-on="on" :color="mainUserLikeBool ? 'pink' : ''">
                                            <v-icon>mdi-heart</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-card v-if="mainUserLikeBool">
                                            <v-card-actions>
                                                <v-btn v-for="(btn, index) in btns" :key="index" icon @click="like(index)" :color="(mainUserLike.reaction === index) ? btn.color : ''">
                                                    <v-icon>{{ btn.icon }}</v-icon>
                                                </v-btn>
                                                <v-btn icon @click="deleteLike(userPosts[postDialogIndex].id, postDialogIndex)">
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
                                    <span class="pointer" @click="window = 1">{{ likeNumber }}人</span>
                                    <v-btn icon>
                                    <v-icon :color="isMainUserComment(postDialog.id) ? 'orange' : ''">mdi-comment</v-icon>
                                    </v-btn>
                                    <span class="pointer" @click="window = 2">{{ totalCommentNumber(postDialog.id) }}人</span>
                                    <!-- <v-btn icon>
                                    <v-icon>mdi-bookmark</v-icon>
                                    </v-btn> -->
                                    <v-btn absolute icon right @click="deleteDialog = true" v-if="isMainUser">
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
                                    <v-btn @click="sendComment(postDialog.id, postDialogIndex)">投稿</v-btn>
                                    </div>
                                </v-card>
                            </v-window-item>

                            <v-window-item :value="1">
                                <v-list subheader style="max-height: 180px; overflow-y: scroll;">
                                <v-subheader>いいね</v-subheader>
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
                                <v-subheader>コメント</v-subheader>
                                    <div v-for="(postComment, index) in postComments" :key="index" @touchstart="toggleDeleteBtn(postComment.user_id, index)" @mouseover="showDeleteBtn(postComment.user_id, index)" @mouseleave="hideDeleteBtn(postComment.user_id, index)">
                                        <v-list-item v-if="commentUser(postComment.user_id)">
                                            <a :href="'/profile?id=' + postComment.user_id">
                                            <v-list-item-avatar>
                                            <v-img :src="'storage/image/avatar/' + commentUser(postComment.user_id).profile_image"></v-img>
                                            </v-list-item-avatar>
                                            </a>
                                            <v-list-item-content>
                                            <v-list-item-title v-text="commentUser(postComment.user_id).screen_name"></v-list-item-title>
                                            {{ postComment.text }}
                                            </v-list-item-content>
                                            <v-list-item-action v-if="commentUser(postComment.user_id).id==visitor.id">
                                            <transition name="slide-fade">
                                                    <v-btn color="success" @click="deleteComment(postComment)" v-if="isDeleteBtn[index]">削除</v-btn>
                                            </transition>
                                            </v-list-item-action>
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

                        <v-btn color="green darken-1" text @click="deletePost(postDialog)">
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
            </v-layout>
        </div>
        <!-- <div v-else-if="!parsedUserPosts && visitor.id !== user.id" class="text-center" style="margin: auto;">
            まだ投稿はありません
        </div> -->
        <div v-else class="text-center" style="margin: auto;">
            まだ投稿はありません<br>
            <v-btn href="/post" v-if="visitor.id == user.id">投稿する</v-btn>
        </div>
    </div>
</template>

<script>
    export default {
    props: ['mainUser', 'requestedUser', 'requestedUserPosts', 'mainUserLikes', 'requestedUserLikes', 'requestedUserComments', 'likedPosts', 'isMainUser'],
    data () {
        return {
            visitor: this.mainUser,
            user: this.requestedUser,
            userPosts: this.requestedUserPosts,
            userComments: this.requestedUserComments,
            dialog: false,
            postDialog: '',
            postDialogIndex: '',
            carousel: [],
            deleteDialog: false,
            length: 3,
            window: 0,
            userLikes: this.mainUserLikes,
            userPostLikes: this.requestedUserLikes,
            postLikes: this.requestedUserLikes,
            likeUsers: '',
            commentUsers: '',
            comment: '',
            menu: [],
            btns: [
                {color: 'yellow', icon: 'mdi-emoticon'},
                {color: 'blue', icon: 'mdi-emoticon-cry'},
                {color: 'orange', icon: 'mdi-emoticon-lol'},
                {color: 'red', icon: 'mdi-emoticon-angry'},
                {color: 'pink', icon: 'mdi-emoticon-kiss'},
            ],
            windowSize: {
                x: 0,
                y: 0,
            },
            lastPostId: '',
            lastIndex: '',
            snackbar: false,
            timeout: 2000,
            text: 'deleted',
            likePosts: this.likedPosts,
            isDeleteBtn: '',
        }
    },
    computed:{
        parsedUserPosts(){
            var userPosts = this.userPosts;
            if(userPosts.length == 0){
                return false;
                } else {
                for(var i=0; i<userPosts.length; i++){
                    userPosts[i].image = JSON.parse(userPosts[i].image);
                }
                return userPosts;
            }
        },
        requestedUserInfo(){
            var user = this.user;
            return user;
        },
        mainUserLikeBool(){
            // findは情報取得。someは歩かないかの判定
            return this.userLikes.some((userserLike) => {
                return userserLike.post_id === this.postDialog.id;
            })
        },
        mainUserLike(){
            return this.postDialog ? this.userLikes.find((userserLike) => {
                return userserLike.post_id === this.postDialog.id;
            }) : '';
        },
        likedUsers(){
            var likeUsers = this.likeUsers;
            return likeUsers;
        },
        card(){
            if(this.windowSize.x < 480){
                return {size1: 350, size2: 300, size3: 110};
            } else {
                return {size1: 600, size2: 500, size3: 350};
            }
        },
        dialogSize(){
            if(this.windowSize.x < 480){
                return 300;
            } else {
                return 500;
            }
        },
        likeNumber(){
            var userPostLike = this.userPostLikes.filter((userPostLike) => {
                return userPostLike.post_id === this.postDialog.id;
            })
            return userPostLike.length;
        },
        postComments(){
            if(this.postDialog.id){
                var postComments = this.userComments.filter((userComment) => {
                    return userComment.post_id === this.postDialog.id;
                })
                if(postComments.length!==0 && postComments.length>1){
                    return postComments.sort((a, b) => {
                        return b.id - a.id;
                    });
                } else if(postComments.length==1){
                    console.log(postComments)
                    return postComments
                } else {
                    return false;
                }
            } else {
                return false
            }
        },
    },
    methods: {
        showDeleteBtn(id, index){
            if(this.commentUser(id).id==this.visitor.id){
                this.isDeleteBtn[index] = true;
                this.isDeleteBtn = this.isDeleteBtn.map((el, key) => {
                    return el;
                })
            }
        },
        hideDeleteBtn(id, index){
            if(this.commentUser(id).id==this.visitor.id){
                this.isDeleteBtn[index] = false;
                this.isDeleteBtn = this.isDeleteBtn.map((el, key) => {
                    return el;
                })
            }
        },
        toggleDeleteBtn(id, index){
            if(this.commentUser(id).id==this.visitor.id){
                // if(this.isDeleteBtn[index] === true){
                //     this.isDeleteBtn[index] = false;
                // } else {
                //     this.isDeleteBtn[index] = true;
                // }
                this.isDeleteBtn[index] = !this.isDeleteBtn[index];
                this.isDeleteBtn = this.isDeleteBtn.map((el, key) => {
                    return el;
                })
            }
        },
        iconType(userId){
            var userPostLikes = this.userPostLikes.filter((userPostLike) => {
                return userPostLike.post_id === this.postDialog.id;
            });
            if (userPostLikes) {
                var userPostLike = userPostLikes.find((like) => {
                    return like.user_id === userId;
                })
                if(userPostLike){
                    return userPostLike.reaction;
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        next () {
            this.window = this.window + 1 === this.length
            ? 0
            : this.window + 1
        },
        prev () {
            this.window = this.window - 1 < 0
            ? this.length - 1
            : this.window - 1
        },
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
        },
        openDialog(userPost, index){
            this.postDialog = userPost;
            this.postDialogIndex = index;
            this.findLikeUsers(userPost.id);
            this.findCommentUsers(userPost.id)
        },
        findLikeUsers(id){
            axios.post('/api/likeUsers', {
                postId: id,
            })
            .then(response => {
                this.likeUsers = response.data;
                this.dialog = true;
            })
            .catch(error => {
                console.log('fail')
            })
        },
        commentUser(id){
            if(this.commentUsers){
                var user = this.commentUsers.find((commentUser) => {
                    return commentUser.id ===id;
                })
                return user;
            } else {
                return false;
            }
        },
        isMainUserComment(id){
            var postComments = this.userComments.filter((userComment) => {
                return userComment.post_id === id;
            })
            return postComments.some((postComment) => {
                return postComment.user_id === this.visitor.id;
            })
        },
        findCommentUsers(id){
            axios.post('/api/commentUsers', {
                postId: id,
            })
            .then(response => {
                this.commentUsers = response.data;
                this.postDialogId = id;
                this.isDeleteBtn = response.data.filter((commentUser) => {
                    return false;
                })
            })
            .catch(error => {
                console.log('fail')
            })
        },
        totalCommentNumber(id){
            var userComments = this.userComments;
            var comments = userComments.filter((userComment) => {
                return userComment.post_id === id;
            });
            return comments.length;
        },
        outside(){
            this.comment = '';
        },
        submit(){
            axios.get('/api/comment', {
            params: {'userId': this.thisUser.id, 'postId': this.images.id, 'text': this.comment},
            })
            .then(
                response => (this.thisUserComments = response.data),
                this.$refs.carouselPost.remove(),
                this.snackbar = true,
                this.destroy(),
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        deletePost(postDialog){
            let fd= new FormData();
            fd.append("post", JSON.stringify(postDialog));
            axios.post('/api/delete_post', fd)
            .then(
                response => {
                    this.userPosts = response.data;
                    this.dialog = false;
                    this.deleteDialog = false;
                    this.snackbar = true;
                }
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        like(index){
            axios.post('/api/like', {
                postId: this.postDialog.id,
                userId: this.visitor.id,
                requestedUserId: this.user.id,
                reaction: index,
            })
            .then(response => {
                this.lastPostId = this.postDialog.id;
                this.lastIndex = index;
                this.checkLikeObj(response.data);
                this.findLikeUsers(this.postDialog);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        deleteLike(thisPostId, index){
            axios.post('/api/delete_like', {
                postId: thisPostId,
                userId: this.visitor.id,
                requestedUserId: this.user.id,
                reaction: index,
            })
            .then(response => {
                this.findDeleteLike(response.data);
                this.findLikeUsers(this.postDialog);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        checkLikeObj(res){
            var userPostLike = this.userPostLikes.find((userPostLike) => {
                return userPostLike.post_id === res.post_id && userPostLike.user_id === res.user_id;
            })
            var isLike = this.userLikes.find((userLike) => {
                return userLike.post_id === res.post_id;
            })
            if(isLike){
                isLike.reaction = res.reaction;
                userPostLike.reaction = res.reaction;
            } else {
                this.userLikes.push(res);
                this.userPostLikes.push(res);
            }
        },
        findDeleteLike(res){
            var newUserLikes = this.userLikes.filter((userLike) => {
                return userLike.id !== res.id;
            })
            this.userLikes = newUserLikes;

            var newUserPostLikes = this.userPostLikes.filter((userPostLike) => {
                return userPostLike.id !== res.id;
            })
            this.userPostLikes = newUserPostLikes;
        },
        sendComment(postId, index){
            if (this.comment) {
                axios.post('/api/comment', {
                    postId: postId,
                    userId: this.visitor.id,
                    text: this.comment,
                })
                .then(response => {
                    this.findCommentUsers(postId)
                    this.comment = '',
                    this.commentSnackbar = true;
                    this.lastPostId = postId;
                    this.lastIndex = index;
                    this.window = 2;
                    this.userComments.push(response.data);
                })
                .catch(error => {
                    console.log('fail')
                })
            }
        },
        deleteComment(postComment){
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
        findDeleteComment(res){
            var newUserComments = this.userComments.filter((userComment) => {
                return userComment.id !== res.id;
            })
            this.userComments = newUserComments;
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


