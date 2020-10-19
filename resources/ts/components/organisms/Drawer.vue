<template>
    <v-list nav dense>
        <v-list-item-group v-model="group" active-class="deep-purple--text text--accent-4">
            <list-items></list-items>
        </v-list-item-group>
    </v-list>
</template>

<script>
import ListItem from '../molecules/ListItem.vue'
export default {
    components: {
        'list-items': ListItem,
    },
    data() {
        return {
            group: null,
        }
    }
}
</script>
