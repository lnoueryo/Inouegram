<template>
    <v-list three-line>
        <v-list-item>
            <avatar-image></avatar-image>
            <list-item-text></list-item-text>
        </v-list-item>
    </v-list>
</template>

<script>
import AvatarImage from '../atoms/AvatarImage'
import ListItemText from '../atoms/ListItemText'
export default {
    components: {
        'avatar-image': AvatarImage,
        'list-item-text': ListItemText,
    },
}
</script>
