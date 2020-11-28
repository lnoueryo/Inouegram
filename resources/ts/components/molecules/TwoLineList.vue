<template>
    <v-list two-line v-if="user.length > 0">
        <template v-for="(user, index) in user">
        <v-list-item :key="index" :href="'/profile?id=' + user.id">
            <v-list-item-avatar>
                <v-avatar>
                    <v-img :src="'/storage/image/avatar/' + user.profile_image"></v-img>
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
    },
    computed: {
        user(){
            console.log(this.users);
            return this.users;
        }
    },
    mounted(){
        console.log(this.users);
    }
}
</script>
