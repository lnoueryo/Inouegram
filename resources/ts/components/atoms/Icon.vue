<template atoms functional>
    <div>
        <v-icon>{{ props.icon }}</v-icon>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
    props: {
        icon: {
            type: String,
        }
    }
})
</script>

<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/ListItem.vue#4 --><!-- project://resources/js/components/organisms/Drawer.vue#3 --><!-- project://resources/js/components/templates/HeaderTemplate.vue#4 -->

