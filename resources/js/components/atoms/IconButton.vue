<template atoms functional>
    <div>
        <v-btn v-for="(icon, key) in props.iconButtonIcons" :key="key" class="mx-3" :href="props.iconButtonUrl[key]" icon>
            <v-icon size="24px">{{ icon }}</v-icon>
        </v-btn>
    </div>
</template>

<script>
export default {
    props: ['iconButtonIcons', 'iconButtonUrl'],
}
</script>

<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/HeaderIcons.vue#3 --><!-- project://resources/js/components/organisms/BarItems.vue#8 --><!-- project://resources/js/components/templates/HeaderTemplate.vue#4 -->

<!-- 子コンポーネント -->


<!-- event -->
<!-- click  project://resources/js/components/organisms/Crop.vue#60 -->
<!--☆ input  project://resources/js/components/organisms/Crop.vue#64 -->
