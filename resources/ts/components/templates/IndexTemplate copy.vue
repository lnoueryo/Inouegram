<template>
    <div class="px-1">
        <v-card class="mx-auto my-5 elevation-1" :max-width="dialogSize" v-for="(parsedUserPost,index) in parsedUserPosts" :key="index" v-resize="onResize" flat>
            <v-list>
                <v-list-item :href="'/profile?id=' + parsedUserPost.user_id">
                    <v-list-item-avatar>
                        <v-img :src="'storage/image/avatar/' + parsedUserPost.profile_image"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{ parsedUserPost.screen_name }}</v-list-item-title>{{ parsedUserPost.isLike }}{{ color(parsedUserPost.id) }}
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-carousel :height="dialogSize">
                <v-carousel-item v-for="(image,i) in parsedUserPost.image" :key="i" :src="'storage/image/' + image.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
            </v-carousel>
            <div class="px-2">
            <v-menu v-model="menu[index]" :close-on-content-click="true" :nudge-width="200" offset-y top>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" :color="parsedUserPost.isLike ? 'pink' : ''">
                    <v-icon>mdi-heart</v-icon>
                    </v-btn>
                </template>
                <v-card v-if="parsedUserPost.isLike">
                    <v-card-actions>
                        <v-btn v-for="(btn, key) in btns" :key="key" icon @click="like(key, parsedUserPost.id, parsedUserPost.user_id)" :color="color(parsedUserPost.id) === key ? btn.color : ''">
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
            <!-- <div class="px-1" style="display: inline-block" @click="likeDialogOpen(index)">{{ totalLikeNumber(newPost.id) }}人</div> -->
            <v-btn icon>
            <v-icon>mdi-comment</v-icon>
            </v-btn>
            <div class="px-1" style="display: inline-block" @click="likeDialog = true">100件</div>
            <v-btn icon>
            <v-icon>mdi-bookmark</v-icon>
            </v-btn>
                <!-- <p class="text-h6 font-weight-light orange--text mb-2">{{ newPost.title }}</p>
                    <div style="overflow-y: scroll;max-height: 110px;">
                    {{ newPost.text }}
                    </div> -->
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

                        <!-- <v-list-item-icon>
                            <div v-if="iconType(postKey, likeUser.id) == 0"><v-icon :color="(iconType(postKey, likeUser.id) === 0) ? 'yellow' : ''">mdi-emoticon</v-icon></div>
                            <div v-else-if="iconType(postKey, likeUser.id) == 1"><v-icon :color="(iconType(postKey, likeUser.id) === 1) ? 'blue' : ''">mdi-emoticon-cry</v-icon></div>
                            <div v-else-if="iconType(postKey, likeUser.id) == 2"><v-icon :color="(iconType(postKey, likeUser.id) === 2) ? 'orange' : ''">mdi-emoticon-lol</v-icon></div>
                            <div v-else-if="iconType(postKey, likeUser.id) == 3"><v-icon :color="(iconType(postKey, likeUser.id) === 3) ? 'red' : ''">mdi-emoticon-angry</v-icon></div>
                            <div v-else><v-icon :color="(iconType(postKey, likeUser.id) === 4) ? 'pink' : ''">mdi-emoticon-kiss</v-icon></div>
                        </v-list-item-icon> -->
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
            // thisUser: this.myInfo,
            // thisPosts: this.posts,
            // thisLikes: this.myLikes,
            // allLikes: this.likes,
            // users: this.myUsers,
            likeArray: [],
            followingUser: [
                {following_id: 2, followed_id: 1}
            ],
            menu: [],
            lastPostId: '',
            lastIndex: '',
            likeDialog: false,
            likeUsers: '',
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
                var newUserPosts = userPosts.map((userPost) => {
                    return Object.assign(userPost, this.postUser(userPost.user_id));
                })
            //     newUserPosts.map((userPost) => {
            //         return Object.assign(userPost, this.userLikes.find((userLike) => {
            //     if(userLike.post_id == userPost.id){
            //     var newUserLike = Object.assign(userLike, {'isLike': true})
            //     return newUserLike;
            // }
            // }));
            //     })
                var abc =newUserPosts.map((userPost) => {
                    return {...userPost, isLike: this.userLikes.some(userLike => userLike.post_id === userPost.id) ? true : false}
                })
                // newUserPosts.map((userPost) => {
                //     return Object.assign(userPost, this.isLikeArray(userPost.id));
                // })
                console.log(abc)
                return abc;
            }
        },
        dialogSize(){
            if(this.windowSize.x < 480){
                return 350;
            } else {
                return 500;
            }
        },
        userPostsWithLikes(){
            var userPosts = this.userPosts;
            var userLikes = this.userLikes;
            var userPostsWithLikes = userPosts.map(userPost => {
                return { ...userPost, like: userLikes.some(userLike => userLike.post_id === userPost.id) ? userLikes.find(userLike => userLike.post_id === userPost.id) : ''}
            })
            return userPostsWithLikes;
        }
    },
    methods:{
        color(id){
            var userLikes = this.userLikes;
            var isUserLike = userLikes.some((userLike) => {
                return userLike.post_id === id
            })
            if(isUserLike){
                return userLikes.find((userLike) => {
                return userLike.post_id === id
            }).reaction;
            }
        },
        postUser(id){
            var usersInfo = this.usersInfo;
            var userInfo = usersInfo.find((userInfo) => {
                return userInfo.id === id;
            })
            return {'screen_name': userInfo.screen_name, 'profile_image': userInfo.profile_image};
        },
        likeDialogOpen(key){
            axios.post('/api/likeUsers', {
                postId: this.thisPosts[key].id,
            })
            .then(response => {
                this.likeDialog = true;
                this.likeUsers = response.data;
            })
            .catch(error => {
                console.log('fail')
            })
        },
        totalLikeNumber(id){
            var likes = this.allLikes;
            var a = likes.filter((object) => {
                return object.post_id === id;
            });
            console.log(likes);
            return a.length;
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
                // this.userLikes = response.data[0];
                console.log(this.userLikes)
                // this.usersLikes = response.data[1];
                // this.likeUsers = response.data[2];
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
                this.snackbar = true;
                this.lastPostId = id;
                this.lastIndex = index;
                this.userLikes = response.data[0];
                // this.usersLikes = response.data[1];
                // this.likeUsers = response.data[2];
            })
            .catch(error => {
                console.log('fail')
            })
        },
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