const state = {
    hello: 'hello',
    drawer: true,
    // 右上のアイコン
    iconButton: {
        icons: [
            'mdi-home',
            'mdi-email',
            'mdi-plus-circle-outline',
            'mdi-delete',
        ],
        url: [
            '/home',
            '/home',
            '/add',
            '',
        ],
    },
    // ドロワー内のアイテム
    listItem: {
        icons: [
            'mdi-home',
            'mdi-account',
        ],
        titles: [
            'home',
            'account'
        ],
    }
};

const getters = {
    drawer: state => state.drawer,
    iconButtonIcons: state => state.iconButton.icons,
    iconButtonUrl: state => state.iconButton.url,
    listItemIcons: state => state.listItem.icons,
    listItemTitles: state => state.listItem.titles,
}

const mutations = {
    TOGGLE_HOME_NAVIGATION_DRAWER: (state, open) => {
        state.isHomeNavigationDrawerOpen = open
      },
};

const actions = {
    TOGGLE_HOME_NAVIGATION_DRAWER(context, open) {
        context.commit('TOGGLE_HOME_NAVIGATION_DRAWER', open)
      },
};

export default {
    state,
    getters,
    mutations,
    actions,
}
