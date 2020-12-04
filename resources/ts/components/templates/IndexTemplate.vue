<template>
    <div class="px-1">
        <v-card class="mx-auto my-5 elevation-1" :max-width="dialogSize" v-for="(parsedUserPost,index) in parsedUserPosts" :key="index" v-resize="onResize" flat>
            <v-list>
                <v-list-item :href="'/profile?id=' + postUser(parsedUserPost.user_id).id">
                    <v-list-item-avatar>
                        <v-img :src="'storage/image/avatar/' + postUser(parsedUserPost.user_id).profile_image"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{ postUser(parsedUserPost.user_id).screen_name }}</v-list-item-title>{{ isLike(parsedUserPost.id).reaction }}
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-carousel :height="dialogSize">
                <v-carousel-item v-for="(image,i) in parsedUserPost.image" :key="i" :src="'storage/image/' + image.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
            </v-carousel>
            <div class="px-2">
                <v-menu v-model="menu[index]" :close-on-content-click="true" :nudge-width="200" offset-y top>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on" :color="isLike(parsedUserPost.id) ? 'pink' : ''">
                        <v-icon>mdi-heart</v-icon>
                        </v-btn>
                    </template>
                    <v-card v-if="isLike(parsedUserPost.id)">
                        <v-card-actions>
                            <v-btn v-for="(btn, key) in btns" :key="key" icon @click="like(key, parsedUserPost.id, parsedUserPost.user_id)" :color="isLike(parsedUserPost.id).reaction === key ? btn.color : ''">
                                <v-icon>{{ btn.icon }}</v-icon>
                            </v-btn>
                            <v-btn icon @click="deleteLike(parsedUserPost.id, parsedUserPost.user_id)">
                                <v-icon>mdi-minus-circle</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                    <v-card v-else>
                        <v-card-actions>
                            <v-btn v-for="(btn, key) in btns" :key="key" icon @click="like(key, parsedUserPost.id, parsedUserPost.user_id)">
                                <v-icon>{{ btn.icon }}</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-menu>
            <div class="px-1" style="display: inline-block" @click="likeDialogOpen(parsedUserPost.id)">{{ totalLikeNumber(parsedUserPost.id) }}人</div>
            <v-btn icon>
            <v-icon>mdi-comment</v-icon>
            </v-btn>
            <div class="px-1" style="display: inline-block" @click="likeDialog = true">100件</div>
            <v-btn icon>
            <v-icon>mdi-bookmark</v-icon>
            </v-btn>
                <p class="text-h6 font-weight-light orange--text mb-2">{{ parsedUserPost.title }}</p>
                    <div style="overflow-y: scroll;max-height: 110px;">
                    {{ parsedUserPost.text }}
                    </div>
            <v-divider></v-divider>
            </div>
            <div class="d-flex align-center justify-space-around">
            <v-col cols="8" md="9">
                <v-text-field
                color="purple darken-2"
                label="コメント"
                required
                ></v-text-field>
            </v-col>
            <v-btn>投稿する</v-btn>
            </div>
        </v-card>
        <v-snackbar v-model="snackbar">
            {{ text }}
            <template v-slot:action="{ attrs }">
                <v-btn color="pink" text v-bind="attrs" @click="deleteLike(lastPostId, lastIndex)">
                Close
                </v-btn>
            </template>
        </v-snackbar>
        <v-dialog v-model="likeDialog" max-width="400">
            <v-card>
                <v-list subheader>
                <v-subheader>Recent chat</v-subheader>
                <div style="max-height: 450px; overflow-y: scroll;">
                    <v-list-item v-for="(likeUser, index) in likeUsers" :key="index" :href="'/profile?id=' + likeUser.id">
                        <v-list-item-avatar>
                        <v-img :src="'storage/image/avatar/' + likeUser.profile_image"></v-img>
                        </v-list-item-avatar>

                        <v-list-item-content>
                        <v-list-item-title v-text="likeUser.screen_name"></v-list-item-title>
                        </v-list-item-content>
                            <v-list-item-icon>
                                <div v-if="iconType(likeUser.id) == 0"><v-icon color="yellow">mdi-emoticon</v-icon></div>
                                <div v-else-if="iconType(likeUser.id) == 1"><v-icon color="blue">mdi-emoticon-cry</v-icon></div>
                                <div v-else-if="iconType(likeUser.id) == 2"><v-icon color="orange">mdi-emoticon-lol</v-icon></div>
                                <div v-else-if="iconType(likeUser.id) == 3"><v-icon color="red">mdi-emoticon-angry</v-icon></div>
                                <div v-else><v-icon color="pink">mdi-emoticon-kiss</v-icon></div>
                            </v-list-item-icon>
                    </v-list-item>
                </div>
                </v-list>
                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="green darken-1" text @click="likeDialog = false">
                    Disagree
                </v-btn>

                <v-btn color="green darken-1" text @click="likeDialog = false">
                    Agree
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <a href="#app">aaaa</a>
    </div>
</template>

<script>
export default {
    props: ['mainUser', 'posts', 'mainUserLikes','comments', 'likes', 'followingUsersInfo', 'likedUsers'],
    data() {
        return {
            abc: '',
            visitor: this.mainUser,
            userPosts: this.posts,
            usersInfo: this.followingUsersInfo,
            userLikes: this.mainUserLikes,
            usersLikes: this.likes,
            likedUsersInfo: this.likedUsers,
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
            show: [],
            snackbar: false,
            text: `Hello, I'm a snackbar`,
            menu: [],
            lastPostId: '',
            lastIndex: '',
            likeDialog: false,
            likeUsers: '',
            dialogPostId: '',
        }
    },
    computed: {
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
        dialogSize(){
            if(this.windowSize.x < 480){
                return 350;
            } else {
                return 500;
            }
        },
    },
    methods:{
        iconType(userId){
            var usersLikes = this.usersLikes.filter((usersLike) => {
                return usersLike.post_id === this.dialogPostId;
            });
            if (usersLikes) {
                var userPostLike = usersLikes.find((like) => {
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
        postUser(id){
            var usersInfo = this.usersInfo;
            var userInfo = usersInfo.find((userInfo) => {
                return userInfo.id === id;
            })
            return userInfo;
        },
        isLike(id){
            if(this.userLikes.some((userLike) => {
                return userLike.post_id === id;
            })){
                return this.userLikes.find((userLike) => {
                    return userLike.post_id === id;
                })
            } else {
                return false;
            }
        },
        likeDialogOpen(id){
            axios.post('/api/likeUsers', {
                postId: id,
            })
            .then(response => {
                this.likeDialog = true;
                this.likeUsers = response.data;
                this.dialogPostId =id;
            })
            .catch(error => {
                console.log('fail')
            })
        },
        totalLikeNumber(id){
            var usersLikes = this.usersLikes;
            var likes = usersLikes.filter((usersLike) => {
                return usersLike.post_id === id;
            });
            return likes.length;
        },
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
        },
        like(index, id, user_id){
            axios.post('/api/like', {
                postId: id,
                userId: this.visitor.id,
                requestedUserId: user_id,
                reaction: index,
            })
            .then(response => {
                this.snackbar = true;
                this.lastPostId = id;
                this.lastIndex = index;
                this.checkLikeObj(response.data);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        deleteLike(id, user_id){
            axios.post('/api/delete_like', {
                postId: id,
                userId: this.visitor.id,
                requestedUserId: user_id,
            })
            .then(response => {
                this.findDeleteLike(response.data);
            })
            .catch(error => {
                console.log('fail')
            })
        },
        checkLikeObj(res){
            var usersLike = this.usersLikes.find((usersLike) => {
                return usersLike.post_id === res.post_id && usersLike.user_id === res.user_id;
            })
            var isLike = this.userLikes.find((userLike) => {
                return userLike.post_id === res.post_id;
            })
            if(isLike){
                isLike.reaction = res.reaction;
                usersLike.reaction = res.reaction;
            } else {
                this.userLikes.push(res);
                this.usersLikes.push(res);
            }
        },
        findDeleteLike(res){
            var newUserLikes = this.userLikes.filter((userLike) => {
                return userLike.id !== res.id;
            })
            this.userLikes = newUserLikes;
            var newUsersLikes = this.usersLikes.filter((usersLike) => {
                return usersLike.id !== res.id;
            })
            this.usersLikes = newUsersLikes;
        }
    },
}
</script>
<style>
    input{
        margin: 0!important;
    }
    .scroll{
        height: 200px;
        overflow-y: scroll;
    }
    /* ::-webkit-scrollbar {
        display: none;
    } */
</style>