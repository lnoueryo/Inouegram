<template atoms functional>
    <v-list-item-avatar>
        <v-avatar tile left>
            <v-img :src="'/storage/image/' + props.userImage" alt="ユーザーイメージ" :style="props.imageData" ></v-img>
        </v-avatar>
    </v-list-item-avatar>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
    props: {
        userImage: String as PropType<string>,
        imageData:  String as PropType<string>,
    },
})
</script>

<!-- 親コンポーネント -->
<!-- project://resources/ts/components/molecules/UserList.vue#4 --><!-- project://resources/js/components/organisms/PostCard.vue#3 --><!-- project://resources/js/components/templates/HeaderTemplate.vue#4 -->
