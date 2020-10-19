interface State {
    drawer: boolean,
    iconButton: {icons: string[], url: string[]},
    listItem: {icons: string[], titles: string[]}
  }

const state: object = {
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

const getters: any = {
    drawer: (state: State) => state.drawer,
    iconButtonIcons: (state: State) => state.iconButton.icons,
    iconButtonUrl: (state: State) => state.iconButton.url,
    listItemIcons: (state: State) => state.listItem.icons,
    listItemTitles: (state: State) => state.listItem.titles,
}

const mutations = {

};

const actions = {

};

export default {
    state,
    getters,
    mutations,
    actions,
}
