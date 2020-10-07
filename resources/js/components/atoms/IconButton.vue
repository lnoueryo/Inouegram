<template>
    <div>
        <v-btn v-for="(icon, key) in iconButtonIcons" :key="key" class="mx-3" :href="iconButtonUrl[key]" icon>
            <v-icon size="24px">{{ icon }}</v-icon>
        </v-btn>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    computed: {
        ...mapGetters([
        'iconButtonIcons',
        'iconButtonUrl'
        ]),
        otherComputed() {

        }
    }
}
</script>
