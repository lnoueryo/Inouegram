<template>
  <div><!-- FIXME:投稿したことない人の処理 -->
        <v-card class="mx-auto" color="grey lighten-4" style="width: 100%" max-width="700">
            <div v-if="userInfo.bg_image">
                <v-img :aspect-ratio="16/9" :src="'storage/image/' + userInfo.bg_image" @click="btnclick"></v-img>
            </div>
            <div v-else>
                <v-img :aspect-ratio="16/9" :src="'storage/image/' + myMainImage" @click="btnclick"></v-img>
            </div>
            <input style="display: none" ref="input" type="file" accept="image/jpeg, image/jpg, image/png" @input="upload">
          <v-col>
        <div v-if="userInfo.profile_image">
            <v-avatar size="120" style="position:absolute; top: 250px">
                <v-img :src="'/storage/image/' + userInfo.profile_image"></v-img>
            </v-avatar>
        </div>
        <div v-else>
            <v-avatar size="120" style="position:absolute; top: 250px" color="purple">
                <v-icon size="64">mdi-account-circle</v-icon>
            </v-avatar>
        </div>
          </v-col>
            <v-list-item color="rgba(0, 0, 0, .4)">
              <v-list-item-content>
                <v-list-item-title class="title">Marcus Obrien</v-list-item-title>
                <v-list-item-subtitle>Network Engineer</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          <v-card-text class="pt-6" style="position: relative;">
            <h3 class="display-1 font-weight-light orange--text mb-2">
              QW cooking utensils
            </h3>
            <div class="font-weight-light grey--text title mb-2">
              <v-btn @click="logout">ログアウト</v-btn>
            </div>
          </v-card-text>
        </v-card>
      <v-layout row wrap class="justify-end" style="margin: auto;">
      <v-hover v-for="(thisUserPost, index) in newPosts" :key="index" v-slot="{ hover }">
        <v-card class="mx-auto" color="grey lighten-4" max-width="350">
          <v-img :aspect-ratio="14/12" :src="'storage/image/' + thisUserPost.image[0].src" @click="openDialog(index)">
            <v-expand-transition>
              <div v-if="hover" class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text" style="height: 100%;">
                {{thisUserPost.image.length}}枚
              </div>
            </v-expand-transition>
          </v-img>
          <v-card-text class="pt-6" style="position: relative;">
            <v-btn absolute color="orange" class="white--text" fab large right top @click="openDeleteDialog(index)">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
            <h3 class="display-1 font-weight-light orange--text mb-2">
              QW cooking utensils
            </h3>
            <div class="font-weight-light grey--text title mb-2">
              {{ thisUserPost.text }}
            </div>
          </v-card-text>
        </v-card>
      </v-hover>
      <v-dialog v-model="dialog" max-width="500px" @click:outside="outside">
        <v-carousel>
          <v-carousel-item
            v-for="(image,i) in newPosts[postKey].image"
            :key="i"
            :src="'storage/image/' + image.src"
            reverse-transition="fade-transition"
            transition="fade-transition"
          ></v-carousel-item>
        </v-carousel>
      </v-dialog>
    <v-dialog
      v-model="deleteDialog"
      max-width="290"
      @click:outside="outside"
    >
      <v-card>
        <v-card-title class="headline">
          Make sure!!
        </v-card-title>

        <v-card-text>
          Are you sure you are gonna delete this post??
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            color="green darken-1"
            text
            @click="deleteDialog = false"
          >
            Disagree
          </v-btn>

          <v-btn
            color="green darken-1"
            text
            @click="deletePost()"
          >
            Agree
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="blue"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
        <v-dialog
      v-model="changeBgDialog"
      max-width="290"
      @click:outside="outside"
    >
      <v-card>
          <v-img :src="changingBgData"></v-img>

        <v-card-text>
          Do you wanna change to this image??
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            color="green darken-1"
            text
            @click="changeBgDialog = false"
          >
            No
          </v-btn>

          <v-btn
            color="green darken-1"
            text
            @click="changeBg()"
          >
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
<img src="/storage/image/37xywPsD8U.png">
    </v-layout>
  </div>
</template>

<script>

  export default {
    props: ['thisUserPosts', 'thisUser', 'thisUserLikes', 'thisUserComments', 'myInfo', 'commentUsers'],
    data () {
      return {
        userPosts: this.thisUserPosts,
        userData: this.myInfo,
        dialog: false,
        deleteDialog: false,
        postKey: 0,
        snackbar: false,
        text: 'deleted',
        timeout: 2000,
        myMainImage: 'kobe.jpg',
        changeBgDialog: false,
        changingBgData: '',
      }
    },
    computed:{
        newPosts(){
            var userPosts = this.userPosts;
            for(var i=0; i<userPosts.length; i++){
                userPosts[i].image = JSON.parse(userPosts[i].image);
            }
            return this.userPosts;
        },
        userInfo(){
            return this.userData;
        }
    },
    methods: {
      async changeBg(){
            var bgData = this.changingBgData;
            let fd= new FormData();
            fd.append("bgData", bgData);
            fd.append("userId", this.myInfo.id);
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
            this.$refs.input.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
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
            params: {'userId': this.myInfo.id, 'postId': this.images.id, 'text': this.comment},
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
    }
}
</script>
<style scoped>
    .v-dialog {
        max-width: 900px!important;
    }

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
</style>
