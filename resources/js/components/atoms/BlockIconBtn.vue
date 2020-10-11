<template atoms functional>
    <div>
        <v-btn v-for="(icon, key) in props.blockIconButton.icons" :color="props.blockIconButton.color[key]" :key="key" class="mx-3" :disabled="btnValidation" @click="listeners.click">
            {{ icon.rightValue}}<v-icon size="24px" :left="icon.left" :right="icon.right">{{ icon.name }}</v-icon>{{ icon.leftValue}}
        </v-btn>
        <!-- <v-btn color="primary" @click="clickNext()" :disabled="btnValidationNext1">Next<v-icon right>mdi-menu-right</v-icon></v-btn> -->
    </div>
</template>

<script>
export default {
    props: ['blockIconButton', 'btnValidation'],
}
// 下記をjson形式でvuexに記述
// blockIconButton: {icons: [{name: '', leftValue: '', rightValue: '', left: false, right: true}], color: ['']},
</script>

<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/HeaderIcons.vue#3 --><!-- project://resources/js/components/organisms/BarItems.vue#8 --><!-- project://resources/js/components/templates/HeaderTemplate.vue#4 -->

<!-- 子コンポーネント -->


<!-- event -->
<!-- click  project://resources/js/components/organisms/Crop.vue#60 -->
<!--☆ input  project://resources/js/components/organisms/Crop.vue#64 -->
