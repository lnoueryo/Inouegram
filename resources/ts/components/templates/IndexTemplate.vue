<template>
<div>
  <v-card class="mx-auto my-5" max-width="500" v-for="(followingUserPost,index) in followingUserPosts" :key="index">
    <v-list>
      <v-list-item
      >
        <v-list-item-avatar>
          <v-img
            :src="abc"
          ></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title v-text="def"></v-list-item-title>
        </v-list-item-content>

      </v-list-item>
    </v-list>
    <!-- <div v-if="followingUserPost.image.length == 1">
      <v-img :src="followingUserPost.image.src"></v-img>
    </div>
    <div v-else> -->
      <v-carousel>
        <v-carousel-item v-for="(image,i) in followingUserPost.image" :key="i" :src="image.src" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
      </v-carousel>
    <!-- </div> -->
    <v-btn icon>
    <v-icon>mdi-heart</v-icon>
    </v-btn>
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
      {{ followingUserPost.text }}
    </v-card-subtitle>
    <v-card-actions>
      <v-btn color="orange lighten-2" text>
        Explore
      </v-btn>

      <v-spacer></v-spacer>

      <v-btn icon @click="show = !show">
        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
      </v-btn>
    </v-card-actions>

    <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>
        <v-card-text>
          I'm a thing. But, like most politicians, he promised more than he could deliver. You won't have time for sleeping, soldier, not with all the bed making you'll be doing. Then we'll go with that data file! Hey, you add a one and two zeros to that or we walk! You're going to do his laundry? I've got to find a way to escape.
        </v-card-text>
      </div>
    </v-expand-transition>
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
    data: () => ({
      show: false,
      abc: '/storage/image/cat.png',
      def: 'ryo',
      followingUser: [
        {following_id: 2, followed_id: 1}
      ],
      followingUserPosts: [
          {user_id: 5, text: 'I am learning Chinese!!', image: [{src: '/storage/image/cat.png'}, {src: '/storage/image/panda.png'}]},
          {user_id: 2, text: '何の写真かな？', image: [{src: '/storage/image/bGrTq4xB93.png'}, {src: '/storage/image/0StUSMfWNN.png'}, {src: '/storage/image/6YzL90UESm.png'}]},
          {user_id: 2, text: '楽しみ～', image: [{src: '/storage/image/9I3cMSljgK.png'}, {src: '/storage/image/KAcZqWoMB0.png'}, {src: '/storage/image/q0BNEezHBS.png'}]},
          {user_id: 1, text: 'パンダから', image: [{src: '/storage/image/panda.png'}]},
        ],
      postKey: 0,
    }),
  }
</script>
