<template>
    <v-container>
        <input style="display: none" ref="input" type="file" accept="image/jpeg, image/jpg, image/png" @change="selectedFile();">
        <v-layout text-xs-center wrap>
            <v-flex xs12>
                <v-btn color="deep-purple text--white" @click="btnclick" fab><v-icon color="white">mdi-image-plus</v-icon></v-btn>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    methods: {
        // selectfileボタン押下時
        btnclick() {
            this.$refs.input.click();
        },
        async selectedFile() {
            this.isUploading = true;
            const file = this.$refs.input.files[0];
            if (!file.type.includes('image/')) {
                    alert('Please select an image file')
                    return
                }
            if (typeof FileReader === 'function') {
                const reader = new FileReader();
                // vuexに送信
                reader.onload = (event) => {
                    this.$store.dispatch('imgSrc', [
                    event.target.result,
                    ]);
                }
                reader.readAsDataURL(file)
            } else {
                alert('Sorry, FileReader API not supported')
            }
        },
    }
}
</script>

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
