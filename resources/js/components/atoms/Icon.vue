<template>
    <div>
        <v-icon>{{ icon }}</v-icon>
    </div>
</template>

<script>
export default {
    props: {
        icon: {
            type: String,
        }
    }
}
</script>
