<template organisms>
    <div>
        <input-file></input-file>
        <v-container>
            <v-layout>
                <section class="cropper-area">
                    <div  class="l_cropper_container">
                        <vue-cropper
                        ref="cropper"
                        :guides="true"
                        :view-mode="2"
                        :auto-crop-area="0.5"
                        :min-container-width="600"
                        :min-container-height="600"
                        :background="true"
                        :rotatable="false"
                        :src="imgSrc"
                        :img-style="{ 'width': '600px', 'height': '600px' }"
                        :aspect-ratio="targetWidth / targetHeight"
                        drag-mode="crop"
                        preview=".preview"
                        />
                        
                    </div>
                </section>
                <section class="preview-area">
                    <p>Preview</p>
                    <div class="preview" />
                </section>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import InputFile from '../molecules/InputFile.vue'
// import Cropper from '../molecules/Cropper.vue'
export default {
    components: {
        'input-file': InputFile,
        // 'cropper': Cropper,
        VueCropper,
    },
    data () {
        return {
            targetWidth: 1,
            targetHeight: 1,
            filename: '',
            data: null,
        }
    },
    computed: {
        btnValidationNext1(){
            return this.$store.state.post.btnValidation.next1;
        },
        imgSrc(){
            return this.$store.state.post.imgSrc;
        }
    },
    methods: {
        clickNext(){
            this.secondStep();
            this.cropImage();
        },
        secondStep(){
            this.$emit('secondStep');
        },
        cropImage () {
            const cropImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            var canvas = document.getElementById("myCanvas");
            var ctx = canvas.getContext("2d");
            var background = new Image();
            background.src = cropImage;
            var width = background.naturalWidth;
            var height = background.naturalHeight;
            background.onload = function(){
            ctx.drawImage(background,0,0,600,600);
            }
            // vuexに送信
            this.$store.dispatch('cropImage', [
                cropImage,
                width,
                height
            ]);
        },
    }

}
</script>
<style scoped>

.crop-placeholder {
  width: 100%;
  height: 200px;
  background: #ccc;
}
.cropped-image img {
  max-width: 100%;
}

.preview {
  width: 300px;
  height: 300px;
  border: solid 1px black;
  /* overflow: hidden; */
}
.l_cropper_container {
  width: 600px;
  height: 600px;
  border: 1px solid gray;
  display: inline-block;
}
/* .preview {
  width: 100%;
  height: calc(372px * (9 / 16));
  overflow: hidden;
} */
</style>
