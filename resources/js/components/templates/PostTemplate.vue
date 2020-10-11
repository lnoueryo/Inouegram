
<template>
    <v-stepper v-model="e1">
        <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1">
                Step 1: Select Image
            </v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step :complete="e1 > 2" step="2">
                Step 2: Crop Image
            </v-stepper-step>
            <!-- <v-divider></v-divider>
            <v-stepper-step step="3">
                Step 3: Post or Download Image
            </v-stepper-step> -->
        </v-stepper-header>

        <v-stepper-items>
            <v-stepper-content step="1">
                <div style="font-size: 14px; text-align: center; width: 100vw;">
                    <crop @secondStep="e1 = 2;"></crop> <!-- 画像を切り抜く project://resources/js/components/organisms/Crop.vue -->
                </div>
            </v-stepper-content>

            <v-stepper-content step="2">
                <div style="font-size: 14px; text-align: center; width: 100vw;">
                    <post-canvas></post-canvas>
                    <post-all></post-all>
                        <!-- <input id="cropped-image" type="hidden" name="cropped-image" :value="cropImg"> -->
                </div>
                <v-btn color="primary" @click="e1 = 3; submit()">トリミング</v-btn>
                <v-btn color="red" @click="e1 = 1">戻る</v-btn>
            </v-stepper-content>

            <!-- <v-stepper-content step="3">
                <v-form @submit.prevent style="max-width: 400px; width: 400px">
                    <v-card class="mb-12">
                        <post-all></post-all>
                    </v-card>
                    <v-btn color="primary" @click="post()">Post</v-btn>
                    <v-btn text @click="e1 = 2">Cancel</v-btn>
                </v-form>
            </v-stepper-content> -->
        </v-stepper-items>
    </v-stepper>
</template>

<script>
import PostCanvas from '../organisms/PostCanvas.vue'
import Crop from '../organisms/Crop.vue'
import PostAll from '../organisms/PostAll.vue'
import { mapGetters } from 'vuex'
export default {
    components: {
        'crop': Crop,
        'post-all': PostAll,
        'post-canvas': PostCanvas,
    },
    data () {
        return {
            e1: 1,
            filename: '',
            data: null,
        }
    },
    computed: {
        // 長くなったためmapGetterでまとめる
        ...mapGetters([
            'cropImg',
        ]),
    },
    methods: {
        submit(){
            let fd= new FormData();
            fd.append("cropped_image", JSON.stringify(this.$store.state.post.cropImage));
            fd.append("message", this.$store.state.post.message);
            axios.post('/api/create', fd)
            .then(
                // response => (this.followed = response.data)
                console.log('yes'),
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        //     submit () {
        //     document.getElementById('cropped-image').value = this.$store.state.add.cropImage;
        //     console.log(document.getElementById('cropped-image').value);
        // },
    }
}
</script>

    <!-- 写真アップロードの紫ボタン -->
    <!-- project://resources/js/components/organisms/Crop.vue#line[3] -->

    <!-- 親コンポーネント -->
    <!--☆ project://resources/js/components/pages/Post.vue#3 -->

    <!-- 子コンポーネント -->
    <!--☆ project://resources/js/components/organisms/Crop.vue --><!--☆ project://resources/js/components/molecules/InputFile.vue --><!-- project://resources/js/components/atoms/InputFileBtn.vue -->

    <!-- event -->
    <!-- secondStep project://resources/js/components/organisms/Crop.vue#60 -->
    <!--☆ input  project://resources/js/components/organisms/Crop.vue#64 -->
