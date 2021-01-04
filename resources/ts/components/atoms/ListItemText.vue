<template>
    <v-list-item-content style="align-items: start;">
        <v-list-item-title style="-webkit-line-clamp: initial;">{{ user.screen_name }}</v-list-item-title>
        <v-list-item-subtitle style="-webkit-line-clamp: initial;">{{ user.comment }}</v-list-item-subtitle>
    </v-list-item-content>
</template>

<script>
export default {
    props: ['user'],
}
</script>

