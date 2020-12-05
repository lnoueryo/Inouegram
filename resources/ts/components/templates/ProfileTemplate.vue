<template>
    <div>
        <v-card class="mx-auto" max-width="800" tile v-resize="onResize">
            <div v-if="requestedUserInfo.bg_image">
                <v-img style="border-radius: 5px" :aspect-ratio="16/9" :src="'storage/image/background/' + requestedUserInfo.bg_image" @click="btnclick"></v-img>
            </div>
            <div v-else>
                <v-img src="storage/image/background/bg-1.jpg" @click="btnclick"></v-img>
            </div>
            <v-list-item three-line>
                <v-list-item-avatar size="80" @click="btnclick2">
                    <v-avatar color="indigo" size="80">
                        <v-img :src="'storage/image/avatar/' +  requestedUserInfo.profile_image"></v-img>
                    </v-avatar>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title class="title" style="margin-top:20px;">{{requestedUserInfo.screen_name}}</v-list-item-title>
                    <v-list-item-subtitle>{{requestedUserInfo.name}}</v-list-item-subtitle>
                    <v-list-item-subtitle @click="openFollowerDialog()">フォロワー{{ userFollowedNumer }}人</v-list-item-subtitle>
                </v-list-item-content>
                <div class="font-weight-light grey--text title mb-2" v-if="visitor.id == user.id">
                    <v-btn @click="logout">ログアウト</v-btn>
                </div>
                <div v-else>
                    <v-btn elevation="1" :color="isFollowed ? 'error' : 'primary'" @click="follow(isFollowed);">
                        <span>{{ isFollowed ? 'フォロー済み' : 'フォローする' }}</span>
                    </v-btn>
                </div>
            </v-list-item>
        </v-card>
        <input style="display: none" ref="bg" type="file" accept="image/jpeg, image/jpg, image/png" @input="upload">
        <input style="display: none" ref="avatar" type="file" accept="image/jpeg, image/jpg, image/png" @input="changeAvatar">
        <div v-if="parsedUserPosts">
            <v-layout row wrap class="justify-end" style="margin: auto;">
                <v-hover v-for="(parsedUserPost, index) in parsedUserPosts" :key="index" v-slot="{ hover }">
                    <v-card class="mx-auto my-4" color="grey lighten-4" :max-width="card.size3" style="width: 100%">
                        <v-img :aspect-ratio="14/12" :src="'storage/image/' + parsedUserPost.image[0].src" @click="openDialog(parsedUserPost, index)">
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
                            <!-- TODO:comment -->
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
                <v-dialog v-model="followerDialog" max-width="290" @click:outside="outside">
                    <v-card>
                        <v-list subheader>
                        <v-subheader>{{ followingUsers }}</v-subheader>
                        <div style="max-height: 450px; overflow-y: scroll;">
                            <v-list-item v-for="(followingUser, index) in followingUsers" :key="index">
                                <v-list-item-avatar :href="'/profile?id=' + followingUser.following_id">
                                <v-img :src="'storage/image/avatar/' + commentUser(followingUser.following_id).profile_image"></v-img>
                                </v-list-item-avatar>

                                <v-list-item-content>
                                <v-list-item-title v-text="commentUser(followingUser.following_id).screen_name"></v-list-item-title>
                                </v-list-item-content>
                                <!-- <div>{{ postComment.text }}
                                    <template v-if="commentUser(followingUser.following_id).id==visitor.id">
                                        <v-btn color="success" @click="deleteComment(postComment)">削除</v-btn>
                                    </template>
                                    <template v-else>
                                        <v-btn color="success">a</v-btn>
                                    </template>
                                </div> -->
                            </v-list-item>
                        </div>
                        </v-list>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="deleteDialog" max-width="290" @click:outside="outside">
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
                <v-dialog v-model="changeBgDialog" max-width="290" @click:outside="outside">
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
                </v-dialog>
                <v-bottom-navigation :value="value" color="teal" grow fixed>
                    <v-btn>
                    <span>Recents</span>

                    <v-icon>mdi-history</v-icon>
                    </v-btn>

                    <v-btn>
                    <span>Favorites</span>

                    <v-icon>mdi-heart</v-icon>
                    </v-btn>

                    <v-btn>
                    <span>Nearby</span>

                    <v-icon>mdi-map-marker</v-icon>
                    </v-btn>
                </v-bottom-navigation>
            </v-layout>
        </div>
        <div v-else class="text-center" style="margin: auto;">
            まだ投稿はありません<br>
            <v-btn href="/post">投稿する</v-btn>
        </div>
        <v-snackbar v-model="snackbar">
            {{ text }}
            <template v-slot:action="{ attrs }">
                <v-btn color="pink" text v-bind="attrs" @click="deleteLike(lastPostId, lastIndex)">
                Close
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>

  export default {
    props: ['mainUser', 'requestedUser', 'requestedUserPosts', 'mainUserLikes', 'requestedUserLikes', 'requestedUserFollowed', 'requestedUserComments'],
    data () {
        return {
            visitor: this.mainUser,
            user: this.requestedUser,
            userPosts: this.requestedUserPosts,
            userFollowed: this.requestedUserFollowed,
            userComments: this.requestedUserComments,
            dialog: false,
            dialogPost: '',
            dialogPostIndex: '',
            followerDialog: false,
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
            text: 'deleted',
            timeout: 2000,
            changeBgDialog: false,
            changingBgData: '',
            value: '',
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
                return userserLike.post_id === this.dialogPost.id;
            })
        },
        mainUserLike(){
            return this.dialogPost ? this.userLikes.find((userserLike) => {
                return userserLike.post_id === this.dialogPost.id;
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
                return userPostLike.post_id === this.dialogPost.id;
            })
            return userPostLike.length;
        },
        isFollowed(){
            var userFolloweds = this.userFollowed;
            var visitor = this.visitor;
            return userFolloweds.some((userFollowed) => {
                return userFollowed.following_id === this.visitor.id;
            })
        },
        userFollowedNumer(){
            var userFollowedNumer = this.userFollowed.length;
            console.log(this.userFollowed)
            return userFollowedNumer;
        },
        postComments(){
            if(this.dialogPost.id){
                var postComments = this.userComments.filter((userComment) => {
                    return userComment.post_id === this.dialogPost.id;
                })
                return postComments.sort((a, b) => {
                    return b.id - a.id;
                    console.log(postComments)
                });
            } else {
                return false
            }
        },
        followingUsers(){
            return this.userFollowed;
        },
        btnclick3() {
            return this.visitor.id === this.user.id
        }
    },
    methods: {
        iconType(userId){
            var userPostLikes = this.userPostLikes.filter((userPostLike) => {
                return userPostLike.post_id === this.dialogPost.id;
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
        async changeBg(){
            var bgData = this.changingBgData;
            let fd= new FormData();
            fd.append("bgData", bgData);
            fd.append("userId", this.user.id);
            axios.post('/api/upload', fd)
            .then(
                response => {
                    this.changeBgDialog = false;
                    this.user = response.data;
                }
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        async changeAvatar(event){
            var file = event.target.files[0];
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
                    that.user = response.data;
                }
            )
            .catch(function (error) {
                console.log(error);
            });
            }
        },
        upload(event){
            if(event.target.value == ''){
                this.changeBgDialog = false;
            } else {
            var file = event.target.files[0];
            var reader = new FileReader();
            var that = this;
                reader.onload = function(e) {
                    that.changingBgData = e.target.result;
                    that.changeBgDialog = true;
                }
                reader.readAsDataURL(file);
            }
        },
        btnclick() {
            if(this.visitor.id == this.user.id){
                this.$refs.bg.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
            }
        },
        btnclick2() {
            if(this.visitor.id == this.user.id){
                this.$refs.avatar.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
            }
        },
        openDialog(userPost, index){
            this.dialogPost = userPost;
            this.dialogPostIndex = index;
            this.findLikeUsers(userPost.id);
            this.findCommentUsers(userPost.id)
        },
        openFollowerDialog(){
            this.followerDialog = true;
            this.findFollowingUsers()
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
        followedUser(id){
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
                this.commentDialog = true;
                this.commentUsers = response.data;
                this.dialogPostId = id;
            })
            .catch(error => {
                console.log('fail')
            })
        },
        findFollowingUsers(){
            var followingIds = this.userFollowed.map((user) => {
                return user.following_id
            })
            console.log(followingIds)
            axios.post('/api/followingUsers', {
                usersId: followingIds,
            })
            .then(response => {
                // this.commentDialog = true;
                // this.commentUsers = response.data;
                // this.dialogPostId = id;
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
            // this.$refs.carouselPost.remove();
            this.comments ='';
            this.comment='';
            this.showUp = false;
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
        deletePost(dialogPost){
            let fd= new FormData();
            fd.append("post", JSON.stringify(dialogPost));
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
        destroy(){
            setTimeout(
                function() {
                this.comment='';
                this.showUp = false;
                this.dialog = false;
                }.bind(this),
            1000,
            );
        },
        imageData(event){
            this.dialog = true;
            this.images = event;
            this.thisImageComments = this.thisUserComments.filter((v) => v.post_id === event.id);
        },
        logout() {
          axios.post('/logout')
          .then(() => location.href = '/')
          .catch(function (error) {
              location.href = '/';
          });
        },
        like(index){
            axios.post('/api/like', {
                postId: this.dialogPost.id,
                userId: this.visitor.id,
                requestedUserId: this.user.id,
                reaction: index,
            })
            .then(response => {
                this.snackbar = true;
                this.lastPostId = this.dialogPost.id;
                this.lastIndex = index;
                this.checkLikeObj(response.data);
                this.findLikeUsers(this.dialogPost);
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
                this.findLikeUsers(this.dialogPost);
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
        follow(isFollowed){
                axios.get('/api/follow', {
                params: {'id': this.user.id, 'myId': this.visitor.id, 'isFollowed': isFollowed},
            })
            .then(
                response => (this.userFollowed = response.data)
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        sendComment(postId, index){
            axios.post('/api/comment', {
                postId: postId,
                userId: this.visitor.id,
                text: this.comment,
            })
            .then(response => {
                this.commentSnackbar = true;
                this.lastPostId = postId;
                this.lastIndex = index;
                this.userComments.push(response.data)
            })
            .catch(error => {
                console.log('fail')
            })
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

</style>
<style>
.v-dialog {
    max-height: 700px!important;
}
</style>