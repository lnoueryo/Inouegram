
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
                        <crop ref="crop"></crop>
                    </div>
                <v-btn color="primary" @click="e1 = 2; cropImage()" :disabled="btnValidation.next1">Next<v-icon right>mdi-menu-right</v-icon></v-btn>
                <v-btn color="red" text><v-icon>mdi-menu-right</v-icon>Cancel</v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
                <div style="font-size: 14px; text-align: center; width: 100vw;">
                    <v-card class="mb-12">
                        <div v-if="cropImg !== ''">
                            <img id="testImage" :src="cropImg" alt="Cropped Image" class="c_cropped_image">
                            <p>
                                <a :href="cropImg" :download="filename">ダウンロード</a>
                            </p>
                            <br>
                            <vue-canvas></vue-canvas>
                        </div>
                        <post-all></post-all>
                        <input id="cropped-image" type="hidden" name="cropped-image" :value="cropImg">
                    </v-card>
                </div>
                <v-btn color="primary" @click="e1 = 3; submit()" :disabled="btnValidation.next2">トリミング</v-btn>
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
import Canvas from '../organisms/Canvas.vue'
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import InputFile from '../molecules/InputFile.vue'
import Crop from '../organisms/Crop.vue'
import PostAll from '../organisms/PostAll.vue'
import { mapGetters } from 'vuex'
export default {
    components: {
        VueCropper,
        'input-file': InputFile,
        'crop': Crop,
        'post-all': PostAll,
        'vue-canvas': Canvas,
    },
    data () {
        return {
            e1: 1,
            targetWidth: 1,
            targetHeight: 1,
            filename: '',
            data: null,
        }
    },
    computed: {
        // 長くなったためmapGetterでまとめる
        ...mapGetters([
            'imgSrc',
            'cropImg',
            'btnValidation',
        ]),
    },
    methods: {
        cropImage () {
            this.$refs.crop.cropImage();
        },
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

