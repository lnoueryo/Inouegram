<template>
    <div>
        <input-file @change="setImage"></input-file>
        <v-container>
            <v-layout>
                <section class="cropper-area">
                    <div v-if="imgSrc !== ''" class="l_cropper_container">
                        <vue-cropper
                        ref="cropper"
                        :guides="true"
                        :view-mode="2"
                        :auto-crop-area="0.5"
                        :min-container-width="500"
                        :min-container-height="500"
                        :background="true"
                        :rotatable="false"
                        :src="imgSrc"
                        :img-style="{ 'width': '500px', 'height': '500px' }"
                        :aspect-ratio="targetWidth / targetHeight"
                        drag-mode="crop"
                        preview=".preview"
                        />
                        <br>
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

import InputFile from '../molecules/InputFile.vue'
export default {
    components: {
        VueCropper,
        'input-file': InputFile,
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
        imgSrc(){
          return this.$store.state.post.imgSrc;
        }
    },
    methods: {
        setImage (e) {
            const file = e.target.files[0];
            this.filename = file.name;
            console.log(this.filename)
            if (!file.type.includes('image/')) {
                alert('Please select an image file')
                return
            }
            if (typeof FileReader === 'function') {
                const reader = new FileReader()
                reader.onload = (event) => {
                this.imgSrc = event.target.result,
                this.$refs.cropper.replace(event.target.result);
                }
                reader.readAsDataURL(file)
            } else {
                alert('Sorry, FileReader API not supported')
            };
        },
        cropImage () {
            var cropImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.$store.dispatch('cropImage', [
                cropImage,
            ]);

            // var testCanvas = document.getElementById('myCanvas');
            // var testContext = testCanvas.getContext('2d');
            // testContext.drawImage(cropImage, 0, 0);
            // document.getElementById('cropped-image').value = this.cropImg;
            // console.log(document.getElementById('cropped-image').value);
            console.log('hello');
        },
    }
}
</script>
<style scoped>
h2 {
  font-size: 25px;
  margin-top: 20px;
}
h3 {
  font-size: 20px;
}
.c_cropped_image {
  width: 500px;
  height: 500px;
  border: 1px solid gray;
}
.l_cropper_container {
  width: 500px;
  height: 500px;
  border: 1px solid gray;
  display: inline-block;
}
.preview {
  width: 100%;
  height: calc(372px * (9 / 16));
  overflow: hidden;
}
.crop-placeholder {
  width: 100%;
  height: 200px;
  background: #ccc;
}
.cropped-image img {
  max-width: 100%;
}

.preview {
  width: 100%;
  height: calc(372px * (9 / 16));
  overflow: hidden;
}
</style>
