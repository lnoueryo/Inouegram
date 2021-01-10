<template functional>
    <div>
        <v-card-text class="py-1" style="position: relative; max-width: 340px;">
            <p class="text-h6 font-weight-light orange--text mb-2" style="margin: 0!important;">
            {{ props.postDialog.title }}
            </p>
            <div class="text-subtitle-1 font-weight-light grey--text title mb-2">
                <div class="content">{{ props.postDialog.text }}</div>
            </div>
        </v-card-text>
    </div>
</template>

<script>
export default {
    props: ['postDialog']
}
</script>