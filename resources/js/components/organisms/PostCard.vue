<template>
    <v-card>
        <user-list></user-list>
        <v-img></v-img>
    </v-card>
</template>

<script>
import UserList from '../molecules/UserList.vue'
export default {
    components: {
        'user-list': UserList,
    }
}
</script>
