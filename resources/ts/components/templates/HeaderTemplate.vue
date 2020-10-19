<template>
    <div>
        <v-app-bar color="deep-purple" dark app>
            <bar-items @drawer="drawer = true"></bar-items>
        </v-app-bar>
        <v-navigation-drawer v-model="drawer" absolute temporary>
            <drawer></drawer>
        </v-navigation-drawer>
    </div>
</template>

<script>
import Drawer from '../organisms/Drawer.vue'
import BarItems from '../organisms/BarItems.vue'
export default {
    components: {
        'drawer': Drawer,
        'bar-items': BarItems,
    },
    data: () => ({
        drawer: false,
        group: null,
    }),
}
</script>
