<template>
    <div>
        <v-navigation-drawer v-model="drawer" :mini-variant="miniVariant" :clipped="clipped" fixed app>
        <v-list>
            <v-list-item v-for="(item, i) in items" :key="i" :href="item.to" router exact>
            <v-list-item-action>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title v-text="item.title" />
            </v-list-item-content>
            </v-list-item>
        </v-list>
        </v-navigation-drawer>
        <v-app-bar :clipped-left="clipped" fixed app>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
                <v-btn icon @click.stop="miniVariant = !miniVariant">
                    <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
                </v-btn>
                <v-btn icon @click.stop="clipped = !clipped">
                    <v-icon>mdi-application</v-icon>
                </v-btn>
            <v-toolbar-title v-text="title" />
        </v-app-bar>
    </div>
</template>

<script>
export default {
  data () {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
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
          to: '/profile'
        },
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'My Memories',
      thisUserInfo: {id: 1, screen_name: 'inoueryo', profile_image: 'panda.png'},
    }
  }
}
</script>
