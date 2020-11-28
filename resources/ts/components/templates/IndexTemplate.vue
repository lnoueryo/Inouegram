<template>
    <div class="px-1">
        <v-card class="mx-auto my-5" :max-width="dialogSize" v-for="(newPost,index) in newPosts" :key="index" v-resize="onResize">
            <v-list>
            <v-list-item :href="'/profile?id=' + newPost.user_id">
                <v-list-item-avatar>
                <v-img :src="'storage/image/avatar/' + userAvatar(newPost.user_id)"></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                <v-list-item-title>{{ userName(newPost.user_id) }}</v-list-item-title>
            </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-carousel :height="dialogSize">
                <v-carousel-item v-for="(image,i) in newPost.image" :key="i" :src="'storage/image/' + image.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
            </v-carousel>
            <div class="px-2">
            <v-menu v-model="menu[index]" :close-on-content-click="true" :nudge-width="200" offset-y top>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on" :color="(likeArray[index].like === true) ? 'pink' : ''">
                <v-icon>mdi-heart</v-icon>
                </v-btn>
            </template>
            <v-card>
                <v-card-actions>
                <v-btn icon @click="like(newPost.id, 0, index)" :color="(likeArray[index].reaction === 0) ? 'yellow' : ''">
                <v-icon>mdi-emoticon</v-icon>
                </v-btn>
                <v-btn icon @click="like(newPost.id, 1, index)"  :color="(likeArray[index].reaction === 1) ? 'blue' : ''">
                <v-icon>mdi-emoticon-cry</v-icon>
                </v-btn>
                <v-btn icon @click="like(newPost.id, 2, index)"  :color="(likeArray[index].reaction === 2) ? 'orange' : ''">
                <v-icon>mdi-emoticon-lol</v-icon>
                </v-btn>
                <v-btn icon @click="like(newPost.id, 3, index)" :color="(likeArray[index].reaction === 3) ? 'red' : ''">
                <v-icon>mdi-emoticon-angry</v-icon>
                </v-btn>
                <v-btn icon @click="like(newPost.id, 4, index)" :color="(likeArray[index].reaction === 4) ? 'pink' : ''">
                <v-icon>mdi-emoticon-kiss</v-icon>
                </v-btn>
                <v-btn icon @click="deleteLike(newPost.id, index)" v-if="likeArray[index].like">
                <v-icon>mdi-minus-circle</v-icon>
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-menu>
            <div class="px-1" style="display: inline-block" @click="likeDialogOpen(index)">{{ totalLikeNumber(newPost.id) }}人</div>
            <v-btn icon>
            <v-icon>mdi-comment</v-icon>
            </v-btn>
            <div class="px-1" style="display: inline-block" @click="likeDialog = true">100件</div>
            <v-btn icon>
            <v-icon>mdi-bookmark</v-icon>
            </v-btn>
                <p class="text-h6 font-weight-light orange--text mb-2">{{ newPost.title }}</p>
                    <div style="overflow-y: scroll;max-height: 110px;">
                    {{ newPost.text }}
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
                <!-- <v-text-field style="padding-left: 10px;max-width: 100px"></v-text-field> -->
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
                        <v-icon>
                            mdi-emoticon-angry
                        </v-icon>
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
    </div>
</template>

<script>
export default {
    props: ['myInfo', 'posts', 'myLikes','comments', 'myUsers', 'likes'],
    data() {
        return {
            windowSize: {
                x: 0,
                y: 0,
            },
            show: [],
            snackbar: false,
            text: `Hello, I'm a snackbar`,
            thisUser: this.myInfo,
            thisPosts: this.posts,
            thisLikes: this.myLikes,
            allLikes: this.likes,
            users: this.myUsers,
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
        newPosts(){
            var posts = this.posts;
            for(let i=0; i<posts.length; i++){
                posts[i].image = JSON.parse(posts[i].image);
            }
            return posts;
        },
        dialogSize(){
            if(this.windowSize.x < 480){
                return 350;
            } else {
                return 500;
            }
        },
    },
    created(){
        var thisPosts = this.thisPosts;
        var thisLikes = this.thisLikes;
        for(var i=0; i<thisPosts.length; i++){
            this.likeArray.push({like: false, reaction: ''})
            for(var j=0; j<thisLikes.length; j++){
                if(thisPosts[i].id == thisLikes[j].post_id){
                    this.likeArray[i].like = true;
                    this.likeArray[i].reaction = thisLikes[j].reaction;
                }
            }
        }
    },
    methods:{
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
            return a.length;
        },
        userAvatar(id){
            var users = this.myUsers;
            for (let i = 0; i < users.length; i++) {
                if(id == users[i].id){
                    return users[i].profile_image;
                }
            }
        },
        userName(id){
            var users = this.myUsers;
            for (let i = 0; i < users.length; i++) {
                if(id == users[i].id){
                    return users[i].screen_name;
                }
            }
        },
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
        },
        like(thisPostId, num, index){
            this.menu[index] = false;
            this.likeArray[index].like = true;
            this.likeArray[index].reaction = num;
            axios.post('/api/like', {
                postId: thisPostId,
                postingUserId: this.myInfo.id,
                reaction: num,
                userPosts: JSON.stringify(this.thisPosts),
            })
            .then(response => {
                this.snackbar = true;
                this.lastPostId = thisPostId;
                this.lastIndex = index;
                this.allLikes = response.data;
            })
            .catch(error => {
                console.log('fail')
            })
        },
        deleteLike(thisPostId, index){
            this.likeArray[index].like = false;
            this.likeArray[index].reaction = '';
            axios.post('/api/delete_like', {
                postId: thisPostId,
                postingUserId: this.myInfo.id,
                userPosts: JSON.stringify(this.thisPosts),
            })
            .then(response => {
                this.allLikes = response.data;
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