<template atoms functional>
    <v-container>
        <input style="display: none" ref="input" type="file" accept="image/jpeg, image/jpg, image/png" @input="listeners.input">
        <v-layout text-xs-center wrap>
            <v-flex xs12>
                <v-btn color="deep-purple text--white" @click="listeners.click" fab><v-icon color="white">mdi-image-plus</v-icon></v-btn>{{ abc }}
            </v-flex>
        </v-layout>
    </v-container>
</template>
<style>
input {
position: relative;
margin: 8px 0 0 -14px;
}

input::before {
content: “添付ファイル：”;
position: absolute;
background: #fff;
width: 110px;
height: 26px;
line-height: 1.8;
text-align: right;
}
</style>




<!-- 親コンポーネント -->
<!-- project://resources/js/components/molecules/InputFile.vue#3 --><!-- project://resources/js/components/organisms/Crop.vue#3 --><!-- project://resources/js/components/templates/ProfileTemplate.vue#3 -->

<!-- event -->
<!-- click  project://resources/js/components/organisms/Crop.vue#60 -->
<!-- input  project://resources/js/components/organisms/Crop.vue#64 -->
