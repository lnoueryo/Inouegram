<template atoms functional>
    <v-list-item-avatar>
        <v-avatar tile left>
            <v-img :src="'/storage/image/' + props.userImage" alt="ユーザーイメージ" :style="props.imageData" ></v-img>
        </v-avatar>
    </v-list-item-avatar>
</template>

<script>
export default {
    props: ['userImage', 'imageData'],

}
</script>

<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/UserList.vue#4 --><!-- project://resources/js/components/organisms/PostCard.vue#3 --><!-- project://resources/js/components/templates/HeaderTemplate.vue#4 -->
