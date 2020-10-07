<template>
    <v-list-item-avatar>
        <v-avatar tile left>
            <v-img :src="'/storage/image/' + userImage" alt="ユーザーイメージ" :style="imageData" ></v-img>
        </v-avatar>
    </v-list-item-avatar>
</template>

<script>
export default {
    props: ['userImage', 'imageData'],

}
</script>
