<template atoms functional>
    <div>
        <v-btn v-for="(icon, key) in props.iconButtonIcons" :key="key" class="mx-3" :href="props.iconButtonUrl[key]" icon>
            <v-icon size="24px">{{ icon }}</v-icon>
        </v-btn>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue'

  export type PropArrType = {
    url: string
  }

export default Vue.extend({
    props: {
        iconButtonIcons: {
            type: Array,
        },
        iconButtonUrl: {
            type: Array,
        },
    }
    // props: ['iconButtonIcons', 'iconButtonUrl'],
})
// 下記をjson形式でvuexに記述
// iconButton: {icons: [''], url: ['/']},
</script>

<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/HeaderIcons.vue#3 --><!-- project://resources/js/components/organisms/BarItems.vue#8 --><!-- project://resources/js/components/templates/HeaderTemplate.vue#4 -->

<!-- 子コンポーネント -->


<!-- event -->
<!-- click  project://resources/js/components/organisms/Crop.vue#60 -->
<!--☆ input  project://resources/js/components/organisms/Crop.vue#64 -->
