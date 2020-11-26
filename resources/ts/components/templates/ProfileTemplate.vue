<template>
    <div>
        <v-card class="mx-auto" max-width="800" tile v-resize="onResize">
            <div v-if="userInfo.bg_image">
                <v-img style="border-radius: 5px" :aspect-ratio="16/9" :src="'storage/image/background/' + userInfo.bg_image" @click="btnclick"></v-img>
            </div>
            <div v-else>
                <v-img src="storage/image/background/bg-1.jpg"></v-img>
            </div>
            <v-list-item three-line>
                <v-list-item-avatar size="80" @click="btnclick2">
                    <v-avatar color="indigo" size="80">
                        <v-img :src="'storage/image/avatar/' +  userInfo.profile_image"></v-img>
                    </v-avatar>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title class="title" style="margin-top:20px;">{{userInfo.screen_name}}</v-list-item-title>
                    <v-list-item-subtitle>{{userInfo.name}}</v-list-item-subtitle>
                    <v-list-item-subtitle>フォロワー</v-list-item-subtitle>
                </v-list-item-content>
                <div class="font-weight-light grey--text title mb-2">
                    <v-btn @click="logout">ログアウト</v-btn>
                </div>
            </v-list-item>
        </v-card>
        <input style="display: none" ref="bg" type="file" accept="image/jpeg, image/jpg, image/png" @input="upload">
        <input style="display: none" ref="avatar" type="file" accept="image/jpeg, image/jpg, image/png" @input="changeAvatar">
        <div v-if="newPosts">
        <v-layout row wrap class="justify-end" style="margin: auto;">
            <v-hover v-for="(thisUserPost, index) in newPosts" :key="index" v-slot="{ hover }">
                <v-card class="mx-auto my-4" color="grey lighten-4" :max-width="card.size3" style="width: 100%">
                    <v-img :aspect-ratio="14/12" :src="'storage/image/' + thisUserPost.image[0].src" @click="openDialog(index)">
                        <v-expand-transition>
                        <div v-if="hover" class="d-flex transition-fast-in-fast-out black darken-2 v-card--reveal display-3 white--text" style="height: 100%;">
                            <p style="font-size: 25px">{{thisUserPost.image.length}}枚</p>
                        </div>
                        </v-expand-transition>
                    </v-img>
                    <!-- <v-card-text class="pt-6" style="position: relative; max-width: 340px;">
                        <v-btn absolute color="black" class="white--text" fab large right top @click="openDeleteDialog(index)">
                        <v-icon>mdi-delete</v-icon>
                        </v-btn>
                        <p class="text-h6 font-weight-light orange--text mb-2">
                        {{ thisUserPost.title }}
                        </p>
                        <div class="text-subtitle-1 font-weight-light grey--text title mb-2">
                        {{ thisUserPost.text }}
                        </div>
                    </v-card-text> -->
                </v-card>
            </v-hover>
            <v-dialog v-model="dialog" :max-width="card.size2" @click:outside="outside">
                <v-card color="grey lighten-4" :max-width="card.size2">
                <v-carousel :height="dialogSize">
                    <v-carousel-item v-for="(image,i) in newPosts[postKey].image" :key="i" :src="'storage/image/' + image.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                </v-carousel>
                    <v-card-text class="pt-6" style="position: relative; max-width: 340px;">
                        <v-btn absolute color="black" class="white--text" fab large right top @click="openDeleteDialog(index)">
                        <v-icon>mdi-delete</v-icon>
                        </v-btn>
                        <p class="text-h6 font-weight-light orange--text mb-2">
                        {{ newPosts[postKey].title }}
                        </p>
                        <div class="text-subtitle-1 font-weight-light grey--text title mb-2">
                        <div class="content">{{ newPosts[postKey].text }}</div>
                        </div>
                    </v-card-text>
            <v-menu v-model="menu[postKey]" :close-on-content-click="true" :nudge-width="200" offset-y top>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on" :color="(likeArray[postKey].like === true) ? 'pink' : ''">
                    <v-icon>mdi-heart</v-icon>
                    </v-btn>
                </template>
                <v-card>
                    <v-card-actions>
                    <v-btn icon @click="like(newPosts[postKey].id, 0, postKey)" :color="(likeArray[postKey].reaction === 0) ? 'yellow' : ''">
                        <v-icon>mdi-emoticon</v-icon>
                    </v-btn>
                    <v-btn icon @click="like(newPosts[postKey].id, 1, postKey)"  :color="(likeArray[postKey].reaction === 1) ? 'blue' : ''">
                        <v-icon>mdi-emoticon-cry</v-icon>
                    </v-btn>
                    <v-btn icon @click="like(newPosts[postKey].id, 2, postKey)"  :color="(likeArray[postKey].reaction === 2) ? 'orange' : ''">
                        <v-icon>mdi-emoticon-lol</v-icon>
                    </v-btn>
                    <v-btn icon @click="like(newPosts[postKey].id, 3, postKey)" :color="(likeArray[postKey].reaction === 3) ? 'red' : ''">
                        <v-icon>mdi-emoticon-angry</v-icon>
                    </v-btn>
                    <v-btn icon @click="like(newPosts[postKey].id, 4, postKey)" :color="(likeArray[postKey].reaction === 4) ? 'pink' : ''">
                        <v-icon>mdi-emoticon-kiss</v-icon>
                    </v-btn>
                    <v-btn icon @click="deleteLike(newPosts[postKey].id, postKey)" v-if="likeArray[postKey].like">
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

                    <v-btn color="green darken-1" text @click="deletePost()">
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
    props: ['thisUserPosts', 'thisUser', 'myLikes', 'thisUserComments', 'commentUsers', 'thisUserLikes'],
    data () {
      return {
      windowSize: {
        x: 0,
        y: 0,
      },
    likeArray: [],
    menu: [],
            lastPostId: '',
            lastIndex: '',
        userPosts: this.thisUserPosts,
        userData: this.thisUser,
        thisLikes: this.myLikes,
        dialog: false,
        deleteDialog: false,
        postKey: 0,
        snackbar: false,
        text: 'deleted',
        timeout: 2000,
        changeBgDialog: false,
        changingBgData: '',
        value: '',
      }
    },
    computed:{
        newPosts(){
            var userPosts = this.userPosts;
            if(userPosts.length == 0){
                return false;
                } else {
                for(var i=0; i<userPosts.length; i++){
                    userPosts[i].image = JSON.parse(userPosts[i].image);
                }
                return this.userPosts;
            }
        },
        userInfo(){
            return this.userData;
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
            var postId = userPosts[this.postKey].id;
            console.log()
            return likeNumber;
        }
    },
    created(){
        var thisPosts = this.userPosts;
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
    methods: {
      onResize () {
        this.windowSize = { x: window.innerWidth, y: window.innerHeight }
      },
      async changeBg(){
            var bgData = this.changingBgData;
            let fd= new FormData();
            fd.append("bgData", bgData);
            fd.append("userId", this.thisUser.id);
            axios.post('/api/upload', fd)
            .then(
                response => {
                    this.changeBgDialog = false;
                    this.userData = response.data;
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
            fd.append("userId", that.thisUser.id);
            axios.post('/api/upload2', fd)
            .then(
                response => {
                    that.userData = response.data;
                    console.log(response.data)
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
            this.$refs.bg.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
        },
        btnclick2() {
            this.$refs.avatar.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
        },
      openDialog(key){
        this.dialog = true;
        this.postKey = key;
      },
      openDeleteDialog(key){
        this.deleteDialog = true;
        this.postKey = key;
        console.log(key)
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
        deletePost(){
            var thisPost = this.userPosts[this.postKey];
            let fd= new FormData();
            fd.append("post", JSON.stringify(thisPost));
            console.log(fd.get('post'))
            axios.post('/api/delete_post', fd)
            .then(
                response => {
                    (this.userPosts = response.data);
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
        like(thisPostId, num, index){
            console.log(thisPostId)
            this.menu[index] = false;
            this.likeArray[index].like = true;
            this.likeArray[index].reaction = num;
            axios.get('/api/like', {
                params: {
                    postId: thisPostId,
                    postingUserId: this.userData.id,
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
                postingUserId: this.userData.id,
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
    max-height: 100%!important;
}
</style>