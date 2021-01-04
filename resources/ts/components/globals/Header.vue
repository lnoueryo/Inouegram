<template>
    <div>
      <div v-resize="onResize">
        <v-navigation-drawer v-model="drawer" fixed app>
        <v-list>
            <v-list-item v-for="(item, i) in items" :key="i" :href="item.to" router exact>
            <v-list-item-action>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title v-text="item.title" />
            </v-list-item-content>
            </v-list-item>
            <v-list-item>
            <v-list-item-content>
                <search-field></search-field>
            </v-list-item-content>
            </v-list-item>
        </v-list>
        </v-navigation-drawer>
        <v-app-bar fixed app>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title v-text="title"/>
        </v-app-bar>
      </div>
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
  }
}
</script>
<style scoped>
  .v-application--is-ltr .v-toolbar__content>.v-btn.v-btn--icon:first-child+.v-toolbar__title, .v-application--is-ltr .v-toolbar__extension>.v-btn.v-btn--icon:first-child+.v-toolbar__title {
    padding-left: 0;
    font-size: 1.15rem;
}
</style>