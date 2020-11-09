<template>
<div>
  <v-card class="mx-auto my-5" max-width="500" v-for="(newPost,index) in newPosts" :key="index">
    <v-list>
      <v-list-item
      >
        <v-list-item-avatar>
          <v-img
            :src="'storage/image/' + myInfo.profile_image"
          ></v-img>
        </v-list-item-avatar>

        <!-- <v-list-item-content>
          <v-list-item-title v-text="def"></v-list-item-title>
        </v-list-item-content> -->

      </v-list-item>
    </v-list>
    <!-- <div v-if="followingUserPost.image.length == 1">
      <v-img :src="followingUserPost.image.src"></v-img>
    </div>
    <div v-else> -->
      <v-carousel>
        <v-carousel-item v-for="(image,i) in newPost.image" :key="i" :src="'storage/image/' + image.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
      </v-carousel>
    <!-- </div> -->

        <v-menu
      v-model="menu"
      :close-on-content-click="false"
      :nudge-width="200"
      offset-y
      top
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
        <v-icon>mdi-heart</v-icon>
        </v-btn>
        <!-- <v-btn icon @click="like(newPost.id)" v-bind="attrs" v-on="on">
        <v-icon>mdi-heart</v-icon>
        </v-btn> -->
      </template>

      <v-card :key="newPost.id">

        <v-card-actions>
        <v-btn icon @click="like(newPost.id, 0)">
        <v-icon>mdi-heart</v-icon>
        </v-btn>
        <v-btn icon @click="like(newPost.id, 1)">
        <v-icon>mdi-heart</v-icon>
        </v-btn>
        <v-btn icon @click="like(newPost.id, 2)">
        <v-icon>mdi-heart</v-icon>
        </v-btn>
        <v-btn icon @click="like(newPost.id, 3)">
        <v-icon>mdi-heart</v-icon>
        </v-btn>
        <v-btn icon @click="like(newPost.id, 4)">
        <v-icon>mdi-heart</v-icon>
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
      <v-btn color="orange lighten-2" text>
        Explore
      </v-btn>

      <v-spacer></v-spacer>

      <!-- <v-btn icon @click="show = !show">
        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
      </v-btn> -->
    </v-card-actions>

    <!-- <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>
        <v-card-text>
          I'm a thing. But, like most politicians, he promised more than he could deliver. You won't have time for sleeping, soldier, not with all the bed making you'll be doing. Then we'll go with that data file! Hey, you add a one and two zeros to that or we walk! You're going to do his laundry? I've got to find a way to escape.
        </v-card-text>
      </div>
    </v-expand-transition> -->
    <v-divider></v-divider>
    <div class="d-flex align-center justify-space-around">
    <v-col cols="12" md="9">
        <v-text-field></v-text-field>
            </v-col>
    <v-btn>
    投稿する
    </v-btn>
    </div>
  </v-card>
  </div>
</template>

<script>
  export default {
      props: ['myInfo', 'myPosts'],
    data() {
      return {
      // show: false,
      thisUser: this.myInfo,
      thisPosts: this.myPosts,
      // def: 'ryo',
      followingUser: [
        {following_id: 2, followed_id: 1}
      ],
      postKey: 0,
      menu: false,
      }
    },
    computed: {
        newPosts(){
            var posts = this.myPosts;
            for(let i=0; i<posts.length; i++){
                posts[i].image = JSON.parse(posts[i].image);
            }
            return posts;
        }
    },
    methods:{
      like(thisPostId, num){
        axios.get('/api/like', {
          params: {
            postId: thisPostId,
            postingUserId: this.myInfo.id,
            reaction: num,
          }
        })
        .then(response => {
          this.menu = false;
          console.log('hello');
        })
        .catch(error => {
          console.log('fail')
        })
      }
    }
  }
</script>
