<template>
  <div style="font-size: 14px; text-align: center; width: 100vw;">
    <h2>画像のトリミング</h2>
    <hr>
    <h3>画像を選択</h3>
    <input-file @selectedImage="loadImage($event)"></input-file>
    <br>
    <v-container>
      <v-layout>
        <div v-if="imgSrc !== ''">
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
        /></div>
        <br>
            <p>Preview</p>
            <div class="preview" />
        </v-layout>
    </v-container>
    <br>
    <br>
    <br>
    <cropping-btn @clickCroppingBtn="clickCroppingBtn($event)"></cropping-btn>
    <!-- <v-btn color="primary" @click="clickNext()" :disabled="btnValidationNext1">Next<v-icon right>mdi-menu-right</v-icon></v-btn> -->
    <div v-if="cropImg !== ''">
      <img
        :src="cropImg"
        alt="Cropped Image"
        class="c_cropped_image"
      >
      <p>
        <a :href="cropImg" :download="filename">画像を保存</a>
        <v-btn @click="croppedInfo()">submit</v-btn>
      </p>
      <br>
    </div>
  </div>
</template>
<script>
import InputFile from '../molecules/InputFile.vue'
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import CroppingBtn from '../molecules/CroppingBtn'
export default {
  components: {
    VueCropper,
    'input-file': InputFile,
    'cropping-btn': CroppingBtn,
  },
  data () {
    return {
      targetWidth: 1,
      targetHeight: 1,
      imgSrc: 'storage/image/panda.png',
      cropImg: '',
    }
  },
  methods: {
    loadImage(e){
      this.$refs.cropper.replace(e);
      this.imgSrc = e;
    },
    clickCroppingBtn(){
        this.secondStep();
        this.cropImage();
    },
    secondStep(){
        this.$emit('secondStep');
    },
    cropImage () {
        const cropImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
        var canvas = document.getElementById("cover");
        var ctx = canvas.getContext("2d");
        var background = new Image();
        background.src = cropImage;
        var width = background.naturalWidth;
        var height = background.naturalHeight;
        background.onload = function(){
                ctx.drawImage(background,0,0,500,500);
            }
        this.$store.dispatch('cropImage', cropImage);

        var that = this;
        setTimeout(function () {
            that.abc = cropImage;
            console.log(that.abc)
        }, 500)
        var canvas = [];
        var canvasCtx = [];
        var coverImage = [];
        var that = this;
        setTimeout(function () {
            for (let i = 1; i < 14; i++) {
                canvas[i] = document.getElementById("pic" + i);
                canvasCtx[i] = canvas[i].getContext('2d');
                canvasCtx[i].globalCompositeOperation = that.globalCompositeOperation[i - 1];
                canvasCtx[i].globalAlpha = 1;
                coverImage[i] = new Image();
                coverImage[i].src = that.abc;
                coverImage[i].onload = function () {
                    canvasCtx[i].drawImage(coverImage[i], 0, 0, 113.3, 113.3);
                };
            }
        }, 500);
        },
    croppedInfo(){
        // var croppedInfo = this.cropImg.toDataURL();
        document.getElementById('cropped-image').value = this.cropImg;
        // console.log(document.getElementById('cropped-image').value);
    },
  }
}
</script>
<style>
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
  width: 300px;
  height: 300px;
  border: solid 1px black;
  overflow: hidden;
}

</style>




