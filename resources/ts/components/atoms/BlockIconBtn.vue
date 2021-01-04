<template atoms functional>
    <div>
        <v-btn v-for="(icon, key) in props.blockIconButton.icons" :color="props.blockIconButton.color[key]" :key="key" class="mx-3" :disabled="btnValidation" @click="listeners.click">
            {{ icon.rightValue}}<v-icon size="24px" :left="icon.left" :right="icon.right">{{ icon.name }}</v-icon>{{ icon.leftValue}}
        </v-btn>
    </div>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue'
export type blockIconButton = {
    icons: [{name: string | null, leftValue: string | null, rightValue: string | null, left: boolean, right: boolean}], color: [string | null]
}
export default {
    props: {
        blockIconButton: Object as PropType<blockIconButton>,
        btnValidation: String,
    },
}
</script>
