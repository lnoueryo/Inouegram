<template>
    <div>
        <v-list-item v-for="(icon,key) in listItemIcons" :key="key">
            <v-list-item-icon>
                <icon :icon="icon"></icon>
            </v-list-item-icon>
            <v-list-item-title>{{ listItemTitles[key] }}</v-list-item-title>
        </v-list-item>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Icon from '../atoms/Icon.vue'
export default {
    components: {
        'icon': Icon,
    },
    computed: {
        ...mapGetters([
            'listItemIcons',
            'listItemTitles',
        ])
    }
}
</script>

