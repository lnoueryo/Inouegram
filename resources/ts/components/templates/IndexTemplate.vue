<template>
    <div class="px-1">
    <v-card class="mx-auto my-5" :max-width="dialogSize" v-for="(newPost,index) in newPosts" :key="index" v-resize="onResize">
        <v-list>
        <v-list-item>
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

        <v-menu v-model="menu[index]" :close-on-content-click="true" :nudge-width="200" offset-y top>
        <template v-slot:activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" v-on="on" :color="(likeArray[index].like === true) ? 'pink' : ''">
            <v-icon>mdi-heart</v-icon>
            </v-btn>
            <!-- <v-btn icon @click="like(newPost.id)" v-bind="attrs" v-on="on">
            <v-icon>mdi-heart</v-icon>
            </v-btn> -->
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
        <v-btn icon>
        <v-icon>mdi-comment</v-icon>
        </v-btn>
        <v-btn icon>
        <v-icon>mdi-bookmark</v-icon>
        </v-btn>
            <v-card-title>
        Top western road trips
        </v-card-title>
        <v-card-subtitle>
        {{ newPost.text }}
        </v-card-subtitle>
        <v-card-actions>

        <v-spacer></v-spacer>
        <v-btn icon @click="show = !show">
            <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
        </v-btn>
        </v-card-actions>

        <v-expand-transition>
            <div class="scroll" v-show="show">
                <!-- <v-divider></v-divider>
                <v-card-text>
                I'm a thing. But, like most politicians, he promised more than he could deliver. You won't have time for sleeping, soldier, not with all the bed making you'll be doing. Then we'll go with that data file! Hey, you add a one and two zeros to that or we walk! You're going to do his laundry? I've got to find a way to escape.
                </v-card-text> -->
                <v-list two-line>
                <template v-for="(item) in items">
                    <v-list-item :key="item.title">
                    <v-list-item-avatar>
                        <v-img :src="item.avatar"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title v-html="item.title"></v-list-item-title>
                        <v-list-item-subtitle v-html="item.subtitle"></v-list-item-subtitle>
                    </v-list-item-content>
                    </v-list-item>
                </template>
                </v-list>
            </div>
        </v-expand-transition>
        <v-divider></v-divider>
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
    </div>
</template>

<script>
export default {
    props: ['myInfo', 'posts', 'myLikes','comments', 'myUsers'],
    data() {
        return {
            items: [
            {
                avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
                name: 'Brunch this weekend?',
                subtitle: `<span class="text--primary">Ali Connors</span> &mdash; I'll be in your neighborhood doing errands this weekend. Do you want to hang out?`,
            },
            {
                avatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
                title: 'Summer BBQ <span class="grey--text text--lighten-1">4</span>',
                subtitle: `<span class="text--primary">to Alex, Scott, Jennifer</span> &mdash; Wish I could come, but I'm out of town this weekend.`,
            },
            ],
            windowSize: {
                x: 0,
                y: 0,
            },
            show: false,
            snackbar: false,
            text: `Hello, I'm a snackbar`,
            thisUser: this.myInfo,
            thisPosts: this.posts,
            thisLikes: this.myLikes,
            users: this.myUsers,
            likeArray: [],
            followingUser: [
                {following_id: 2, followed_id: 1}
            ],
            menu: [],
            lastPostId: '',
            lastIndex: '',
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
                console.log(this.windowSize.x)
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
        userAvatar(id){
            var users = this.myUsers;
            for (let i = 0; i < users.length; i++) {
                if(id == users[i].id){
                    console.log(users[i])
                    return users[i].profile_image;
                }
            }
        },
        userName(id){
            var users = this.myUsers;
            for (let i = 0; i < users.length; i++) {
                if(id == users[i].id){
                    console.log(users[i])
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
            axios.get('/api/like', {
                params: {
                    postId: thisPostId,
                    postingUserId: this.myInfo.id,
                    reaction: num,
                }
            })
            .then(response => {
                this.snackbar = true;
                this.lastPostId = thisPostId;
                this.lastIndex = index;
            })
            .catch(error => {
                console.log('fail')
            })
        },
        deleteLike(thisPostId, index){
            this.likeArray[index].like = false;
            this.likeArray[index].reaction = '';
            axios.get('/api/delete_like', {
            params: {
                postId: thisPostId,
                postingUserId: this.myInfo.id,
            }
            })
            .then(response => {

            })
            .catch(error => {
                console.log('fail')
            })
        },
    }
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
</style>