<template functional>
    <v-list two-line v-if="props.users.length > 0">
        <template v-for="(user, index) in props.users">
        <v-list-item :key="index" :href="'/profile?id=' + user.id">
            <v-list-item-avatar>
                <v-avatar>
                    <v-img :src="'/storage/image/' + user.profile_image"></v-img>
                </v-avatar>
            </v-list-item-avatar>
            <list-item-text :user="user"></list-item-text>
        </v-list-item>
        </template>
    </v-list>
</template>

<script>
import ListItemText from '../atoms/ListItemText.vue'
export default {
    props: ['users'],
    components: {
        'list-item-text': ListItemText,
    }
}
</script>
