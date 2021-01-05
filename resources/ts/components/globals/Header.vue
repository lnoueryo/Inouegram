<template>
    <div>
      <div v-resize="onResize">
        <v-navigation-drawer v-model="drawer" fixed app>
        <v-list>
            <v-list-item href="/user" v-if="user.id == 1 || user.id == 2">
            <v-list-item-action>
                <v-icon>mdi-google-downasaur</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                ダッシュボード
            </v-list-item-content>
            </v-list-item>
            <v-list-item v-for="(item, i) in items" :key="i" :href="item.to" router exact>
            <v-list-item-action>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title v-text="item.title" />
            </v-list-item-content>
            </v-list-item>
            <v-list-item @click="logout">
            <v-list-item-action>
                <v-icon>mdi-logout</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                ログアウト
            </v-list-item-content>
            </v-list-item>
            <v-list-item>
            <v-list-item-content>
                <search-field></search-field>
            </v-list-item-content>
            </v-list-item>
        </v-list>
      <template v-slot:append>
        <v-list>
            <v-list-item @click="dialog = true" v-if="user.email !== 'guest@guest.com'">
            <v-list-item-action>
                <v-icon>mdi-account-cancel</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                退会
            </v-list-item-content>
            </v-list-item>
        </v-list>
      </template>
        </v-navigation-drawer>
        <v-app-bar fixed app>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title v-text="title"/>
        </v-app-bar>
      </div>
  <v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">
          本当に退会されますか？
        </v-card-title>
        <v-card-text>いいねやコメントだけでなく、投稿した写真やユーザー情報すべて消えてしまいます。</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">
            キャンセル
          </v-btn>
          <v-btn color="green darken-1" text @click="resign">
            退会
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
    </div>
</template>

<script>
import SearchField from '../molecules/SearchField'
export default {
  props: ['user'],
  components:{
    SearchField
  },
  data () {
    return {
      dialog: false,
      size: true,
      windowSize: {
          x: 0,
          y: 0,
      },
      drawer: false,
      items: [
        {
          icon: 'mdi-home',
          title: 'ホーム',
          to: '/home'
        },
        {
          icon: 'mdi-plus-circle-outline',
          title: '投稿',
          to: '/post'
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'マイページ',
          to: '/profile?id=' + this.user.id,
        },
      ],
      title: 'MyMemories',
    }
  },
  methods: {
    onResize () {
      this.windowSize = { x: window.innerWidth, y: window.innerHeight }
    },
    logout() {
      axios.post('/logout')
      .then(() => location.href = '/')
      .catch(function (error) {
          location.href = '/';
      });
    },
    resign(){
      this.dialog=false;
      axios.delete('/api/users/' + this.user.id, {data: {id: this.user.id}})
      .then(() => location.href = '/login')
      .catch((error) => {
          this.progress = false;
          console.log('error')
      })
    }
  }
}
</script>
<style scoped>
  .v-application--is-ltr .v-toolbar__content>.v-btn.v-btn--icon:first-child+.v-toolbar__title, .v-application--is-ltr .v-toolbar__extension>.v-btn.v-btn--icon:first-child+.v-toolbar__title {
    padding-left: 0;
    font-size: 1.15rem;
}
</style>