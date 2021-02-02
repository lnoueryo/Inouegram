<template>
    <div>
        <v-stepper v-model="e1" class="elevation-0 mb-4">
            <v-stepper-header class="elevation-0">
                <v-stepper-step :complete="e1 > 1" step="1">
                    画像のトリミング
                </v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="e1 > 2" step="2">
                    画像の加工
                </v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="3">
                    コメント
                </v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="4">
                確認
                </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <v-container>
                        <v-layout justify-space-around wrap>
                            <div v-if="imgSrc !== ''">
                                <vue-cropper
                                class="mb-2"
                                    ref="cropper"
                                    :guides="true"
                                    :view-mode="2"
                                    :auto-crop-area="0.5"
                                    :min-container-width="500"
                                    :min-container-height="500"
                                    :background="true"
                                    :rotatable="false"
                                    :src="imgSrc"
                                    :img-style="{ 'width': 500 + 'px', 'height': 500 + 'px' }"
                                    :aspect-ratio="1 / 1"
                                    drag-mode="crop"
                                    preview=".preview"
                                />
                    <v-btn @click="cropImage" color="primary" :disabled="stepBtn1">トリミング</v-btn>
                            </div>
                            <div v-else>
                                <input-file-image :size="size" @selectedImage="loadImage($event)"></input-file-image>
                            </div>
                            <div>
                            <div id="preview" class="preview"/>
                            <input-file :size="size" @selectedImage="loadImage($event)"></input-file>
                            </div>
                        </v-layout>
                    </v-container>
                </v-stepper-content>

                <v-stepper-content step="2">
                    <v-layout>
                    <v-card class="overflow-hidden" height="500" width="500">
                        <div id="canvas-area" style="position: relative">
                        <div v-if="isActive" style="position: absolute; top: 0; left: 0; height: 100%; width: 100%;">
                            <input id="newText" :style="inputPosition" type="text" @keyup.enter="discribeNew">
                        </div>
                        <div v-if="isActiveEditText">
                            <input id="selectedText" :style="selectedInputPosition" type="text" v-model="selectedEditTextMessage" @blur="edit" @keyup.enter="blur">
                        </div>
                            <canvas id="drawCanvas" style="position: absolute; z-index: 1;top: 0; left: 0;" v-bind:class="{eraser: canvasMode === 'eraser'}" width="500" height="500" @mousedown="dragStart" @mouseup="dragEnd" @mouseout="dragEnd" @mousemove="draw"></canvas>
                            <canvas id="cover" style="position: absolute; top: 0; left: 0;" width="500" height="500"></canvas>
                            <!-- <canvas id="text" style="z-index: 0; position: absolute;" width=500 height=500 @dblclick="selectText" @mousedown.prevent="handleMouseDown" @mousemove.prevent="handleMouseMove" @mouseup.prevent="handleMouseUp" @mouseout.prevent="handleMouseOut"></canvas> -->
                            <canvas id="text" :class="{index2: value == 2}" style="position: absolute;top: 0; left: 0;" width=500 height=500 @dblclick="selectText" @mousedown.prevent="handleMouseDown" @mousemove.prevent="handleMouseMove" @mouseup.prevent="handleMouseUp" @mouseout.prevent="handleMouseOut"></canvas>
                        </div>
                        <v-bottom-navigation absolute hide-on-scroll scroll-target="#hide-on-scroll-example" v-model="value">
                        <v-btn color="deep-purple accent-4" text　@click="pen" value="0">
                            <span>ペン</span>
                            <v-icon>mdi-draw</v-icon>
                        </v-btn>
                        <v-btn color="deep-purple accent-4" text>
                            <span>エフェクト</span>
                            <v-icon>mdi-checkerboard</v-icon>
                        </v-btn>
                        <v-btn color="deep-purple accent-4" text>
                            <span>テキスト</span>
                            <v-icon>mdi-format-color-text</v-icon>
                        </v-btn>
                        <!-- <v-btn color="deep-purple accent-4" text>
                            <span>ダウンロード</span>
                            <v-icon>mdi-briefcase-download</v-icon>
                        </v-btn> -->
                        </v-bottom-navigation>
                    </v-card>
                    <v-card class="overflow-hidden" height="550" width="500" flat>
                    <v-stepper v-model="toolStepper" style="width: 500px;" class="elevation-0">
                        <v-stepper-items class="px-4">
                            <v-stepper-content step="1">
                            <v-card-title>Pen</v-card-title>
                                    <v-color-picker class="mb-5 ml-3" v-model="penColor" @input="searchTimeOut"></v-color-picker>
                            <v-col cols="12" sm="11">
                                    <v-slider label="lineWidth" v-model.lazy="lineWidth" min="4" max="25" thumb-label="always" @input="searchTimeOut" thumb-color="pink" color="pink"></v-slider>
                            </v-col>
                                    <v-col cols="12" sm="12" class="d-flex">
                                        <v-select class="px-1" v-model="lineCap" :items="lineCaps" label="lineCap" outlined @input="searchTimeOut"></v-select>
                                        <v-select class="px-1" v-model="lineJoin " :items="lineJoins" label="lineJoin" outlined @input="searchTimeOut"></v-select>
                                    </v-col>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-card-title class="headline">
                                    Filters
                                </v-card-title>
                                <v-slider label="opacity" v-model.lazy="opacity" min="4" max="10" thumb-label="always" @input="searchTimeOut2" thumb-color="pink" color="pink">
                                    <template v-slot:append>
                                        <v-text-field v-model="opacity" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="blur" v-model.lazy="filterObject.blur" min="0" max="10" thumb-label="always" @input="searchTimeOut3" thumb-color="purple" color="purple">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.blur" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="brightness" v-model.lazy="filterObject.brightness" min="35" max="250" thumb-label="always" @input="searchTimeOut3" thumb-color="red"  color="red">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.brightness" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="contrast" v-model.lazy="filterObject.contrast" min="0" max="400" thumb-label="always" @input="searchTimeOut3" thumb-color="orange" color="orange">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.contrast" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="grayscale" v-model.lazy="filterObject.grayscale" min="0" max="100" thumb-label="always" @input="searchTimeOut3" thumb-color="indigo" color="indigo">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.grayscale" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="hue-rotate" v-model.lazy="filterObject.hueRotate" min="0" max="359" thumb-label="always" @input="searchTimeOut3" thumb-color="green" color="green">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.hueRotate" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="invert" v-model.lazy="filterObject.invert" min="0" max="100" thumb-label="always" @input="searchTimeOut3" thumb-color="deep-purple" color="deep-purple">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.invert" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="saturate" v-model.lazy="filterObject.saturate" min="0" max="300" thumb-label="always" @input="searchTimeOut3" thumb-color="light-blue" color="light-blue">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.saturate" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                                <v-slider label="sepia" v-model.lazy="filterObject.sepia" min="0" max="100" thumb-label="always" @input="searchTimeOut3" thumb-color="lime" color="lime">
                                    <template v-slot:append>
                                        <v-text-field v-model="filterObject.sepia" class="mt-0 pt-0" hide-details single-line type="number" style="width: 60px"></v-text-field>
                                    </template>
                                </v-slider>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <v-card-title class="headline">
                                </v-card-title>
                                <div v-if="isActive">
                                    <v-slider min="10" max="64" v-model="fontSize" thumb-label="always" :thumb-size="30"></v-slider>
                                    <v-color-picker v-model="inputPosition.color"></v-color-picker>
                                    <!-- <v-btn-toggle v-model="toggle_multiple" dense background-color="primary" dark multiple>
                                    <v-btn>Bold</v-btn></v-btn-toggle> -->
                                </div>
                                <div v-else>
                                    <v-slider min="10" max="64" v-model="selectedFontSize" thumb-label="always" :thumb-size="30" :disabled="!isActiveEditText"></v-slider>
                                    <v-color-picker v-model="selectedInputPosition.color" :disabled="!isActiveEditText"></v-color-picker>
                                </div>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                    </v-card>
                    </v-layout>
                    <v-stepper v-model="toolStepper" style="width: 1000px;" class="elevation-0 mb-4">
                        <v-stepper-items>
                            <v-stepper-content step="1">
                                <v-btn id="eraser-button" class="align-self-start mr-2" @click="eraser" >消しゴム</v-btn>
                                <v-btn id="clear-button" class="align-self-start mr-2" @click="clear">クリア</v-btn>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <v-col cols="12" sm="12" class="d-flex">
                                    <v-row>
                                        <v-btn class="mr-2" color="pink" @click="reset">リセット</v-btn>
                                        <v-btn class="mr-2" color="purple" @click="rotate">回転</v-btn>
                                        <v-btn class="mr-2" @click="back">戻す</v-btn>
                                        <v-sheet elevation="1" max-width="520">
                                            <v-slide-group v-model="model" class="pa-3" center-active show-arrows>
                                                <v-slide-item v-for="n in 13" :key="n" v-slot:default="{ active, toggle }">
                                                    <v-card :color="active ? 'primary' : 'grey lighten-1'" class="ma-1" height="117.3" width="113.3" @click="click">
                                                        <canvas :id="'pic' + n" height="113.3" width="113.3" @click="toggle"></canvas>
                                                        <v-row class="fill-height" align="center" justify="center">
                                                            <v-scale-transition>
                                                                <v-icon v-if="active" color="white" size="48" v-text="'mdi-close-circle-outline'"></v-icon>
                                                            </v-scale-transition>
                                                        </v-row>
                                                    </v-card>
                                                </v-slide-item>
                                            </v-slide-group>
                                        </v-sheet>
                                    </v-row>
                                    <v-color-picker v-model="color" @input="searchTimeOut2" hide-inputs></v-color-picker>
                                </v-col>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <!-- <div v-if="isActive">
                                    <v-slider min="10" max="64" v-model="fontSize" thumb-label="always" :thumb-size="30"></v-slider>
                                    <v-color-picker v-model="inputPosition.color"></v-color-picker>
                                    <v-btn-toggle v-model="toggle_multiple" dense background-color="primary" dark multiple>
                                    <v-btn>Bold</v-btn></v-btn-toggle></div>
                                <div v-if="isActiveEditText">
                                    <v-slider min="10" max="64" v-model="selectedFontSize" thumb-label="always" :thumb-size="30"></v-slider>
                                    <v-color-picker v-model="selectedInputPosition.color"></v-color-picker>aaa
                                </div> -->
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>
                    <v-btn color="primary" @click="getImage">決定</v-btn>
                    <v-btn text @click="e1 = 1;">戻る</v-btn>
                </v-stepper-content>

                <v-stepper-content step="3">
                        <div class="d-flex pt-4 justify-content-around mb-4">
                            <canvas id="concat" width="500" height="500" v-show="false"></canvas>
                            <div class="mb-4" v-if="showConcatImg">
                                <v-carousel height="500" v-model="carousel">
                                    <v-carousel-item v-for="(image,index) in showConcatImg" :key="index" :src="image" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                                </v-carousel>
                            </div>
                            <div class="pt-2 px-3 mx-auto"  style="width: 100%;max-width: 600px;">
                                <v-text-field label="タイトル" value="Grocery delivery" hint="For example, flowers or used cars" v-model="title"></v-text-field>
                                <v-textarea v-model="message" color="teal" counter maxlength="500">
                                    <template v-slot:label>
                                        <div>
                                        内容 <small>(content)</small>
                                        </div>
                                    </template>
                                </v-textarea>
                            </div>
                        </div>
                        <div>
                            <v-btn color="primary" @click="e1=4" :disabled="confirmMessage">確認</v-btn>
                            <v-btn color="primary" @click="oneMoreImage">もう一枚投稿</v-btn>
                            <v-btn text @click="back2edit">戻る</v-btn>
                        </div>
                </v-stepper-content>

                <v-stepper-content step="4">
                    <div class="d-flex">
                    <!-- <v-img :src="img" max-width="250" v-for="(img, index) in concatImg" :key="index"></v-img> -->
                        <v-card class="mx-auto my-5" max-width="500">
                            <v-list>
                                <v-list-item>
                                    <v-list-item-avatar>
                                        <v-img src="/storage/image/panda.png"></v-img>
                                    </v-list-item-avatar>

                                    <v-list-item-content>
                                        <v-list-item-title></v-list-item-title>
                                    </v-list-item-content>

                                </v-list-item>
                            </v-list>
                            <v-carousel>
                                <v-carousel-item v-for="(image,i) in concatImg" :key="i" :src="image" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                            </v-carousel>
                            <v-btn icon>
                                <v-icon>mdi-heart</v-icon>
                            </v-btn>
                            <v-btn icon>
                                <v-icon>mdi-comment</v-icon>
                            </v-btn>
                            <v-btn icon>
                                <v-icon>mdi-bookmark</v-icon>
                            </v-btn>
                            <v-card-title>
                            {{ title }}
                            </v-card-title>
                            <v-card-subtitle>
                            {{ message }}
                            </v-card-subtitle>
                        </v-card>
                    </div>
                    <div>
                        <v-btn color="primary" @click="submit">保存</v-btn>
                        <v-btn text @click="e1=3">戻る</v-btn>
                    </div>
                </v-stepper-content>

            </v-stepper-items>
        </v-stepper>
        <div v-if="showConcatImg">
            <div class="d-flex mb-4" style="justify-content: space-between;">
                <div v-for="(image, index) in concatImg" :key="index">
                    <img width="250" :src="image">
                    <div>
                        <v-btn color="red" block @click="deleteImage(index)">削除</v-btn>
                    </div>
                </div>
            </div>
        <v-btn color="primary" @click="confirmSave()">保存</v-btn>
        </div>
    <v-dialog v-model="sizeDialog" width="500">

      <v-card>
        <v-card-title class="headline grey lighten-2">
          Error
        </v-card-title>

        <v-card-text>
          ファイルサイズが上限を超えています。6M以下のイメージを選択してください
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="sizeDialog = false"
          >
            I accept
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="titleMessagevalidation" :timeout="timeout">
      {{ text }}
      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="titleMessagevalidation = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs'
import InputFile from '../molecules/InputFile.vue'
import InputFileImage from '../molecules/InputFileImage.vue'
export default {
    name: "DrawTool",
    props: ['user'],
    components: {
        VueCropper,
        InputFile,
        InputFileImage,
    },
    data() {
      return {
          size: 1100,
      titleMessagevalidation: false,
      text: 'タイトルと内容を書いてください',
      timeout: 4000,
          carousel: '',
          basicSize: '',
          mediaSize: '',
        sizeDialog: false,
        show: false,
        title: '',
        message: '',
        concatImageBtn: false,
        stepBtn1: true,
        e1: 1,
        concatImg: [],
        imgBeforeConcat: [],
        imgSrc: '',
        value: 0,
        canvasMode: 'penBlack',
        drawCanvas: null,
        drawCanvasctx: null,
        isDrag: false,
        cover: null,
        coverctx: null,
        penColor: '#000000',
        lineWidth: 5,
        lineCaps: ['square','butt','round'],
        lineCap: 'round',
        lineJoins: ['bebel','miter','round'],
        lineJoin : 'round',
        filterObject: {'blur': 0,'brightness': 100,'contrast': 100,'grayscale': 0,'hueRotate': 0,'invert': 0,'saturate': 100,'sepia': 0},
        globalCompositeOperation: ['lighter', 'darken', 'overlay', 'color-dodge', 'color-burn', 'hard-light', 'soft-light', 'difference', 'exclusion', 'hue', 'saturation', 'color', 'luminosity'],
        isDrag: false,
        model: null,
        globalAlpha: 1,
        color: '#00000080',
        timer: '',
        filters: '',
        croppedImage: '',
        toggle_multiple: [],
        textCanvas: '',
        textCanvasctx: '',
        texts: [],
        selectedText: -1,
        selectedEditText: -1,
        offsetX: '',
        offsetY: '',
        scrollX: '',
        scrollY: '',
        startX: '',
        startY: '',
        isActive: false,
        isActiveEditText: false,
        dialog: false,
        selectedEditTextMessage: '',
        inputPosition: {fontSize: '15px', color: 'black', fontWeght: '500', fontStyle: 'normal', top: '', left: '', position: 'absolute', zIndex: 1, height: '15px'},
        selectedInputPosition: {fontSize: '15px', color: 'black', fontWeght: '500', fontStyle: 'normal', top: '', left: '', position: 'absolute', zIndex: 1, height: '15px'},
      };
    },
    computed: {
        confirmMessage(){
            return this.message && this.title ? false : true;
        },
        showConcatImg(){
            if(this.concatImg.length == 0){
                return false;
            } else {
                return this.concatImg;
            }
        },
        toolStepper: {
            get() {
                return Number(this.value) + 1;
            },
            set(newValue) {
                this.value = newValue;
            }
        },
        opacity: {
            get() {
                return this.globalAlpha * 10;
            },
            set(newValue) {
                this.globalAlpha = newValue / 10;
            }
        },
        fontSize: {
            get() {
                return this.inputPosition.fontSize.replace( /px/g , "" ) && this.inputPosition.height.replace( /px/g , "" );
            },
            set(newValue) {
                this.inputPosition.fontSize = newValue + 'px';
                this.inputPosition.height = newValue + 'px';
            }
        },
        selectedFontSize: {
            get() {
                return this.selectedInputPosition.fontSize.replace( /px/g , "" ) && this.selectedInputPosition.height.replace( /px/g , "" );
            },
            set(newValue) {
                this.selectedInputPosition.fontSize = newValue + 'px';
                this.selectedInputPosition.height = newValue + 'px';
            }
        },
    },
    mounted(){
      // canvas
        this.drawCanvas = document.getElementById('drawCanvas');
        this.drawCanvasctx = this.drawCanvas.getContext('2d');
        this.drawCanvasctx.lineCap = 'round';
        this.drawCanvasctx.lineJoin = 'round';
        this.drawCanvasctx.lineWidth = 5;
        this.drawCanvasctx.miterLimit  = 50;
        this.drawCanvasctx.strokeStyle = '#000000';
        // text
        this.textCanvas = document.getElementById("text");
        this.textCanvasctx = this.textCanvas.getContext("2d");
        this.mediaSize = window.innerWidth;
        var img = document.getElementById("select");
    },
    methods: {
        oneMoreImage(){
            this.e1 = 1;
            this.imgSrc = '';
            this.stepBtn1 = true;
            this.concatImageBtn = true;
            var preview = document.getElementsByClassName('preview');
            var img = preview[0].getElementsByTagName('img');
            img[0].src = '';
            this.coverctx.filter = 'grayscale(0%)';
            this.globalAlpha = 1;
            this.hexa = 'transparent';
            this.model = '';
            this.filters = '';
            this.coverctx.globalCompositeOperation = 'source-over';
            this.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
            this.croppedImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.cover = document.getElementById("cover");
            this.coverctx = this.cover.getContext("2d");
            this.coverctx.globalAlpha = 1;
            this.coverctx.globalCompositeOperation = 'source-over';
            this.coverctx.filter = 'grayscale(0%)';
            // var newImg = document.createElement("img");
            // newImg.src = this.concatImg[this.concatImg.length-1];
            // newImg.width = 250;
            // newImg.height = 250;
            // var concatImages = document.getElementById('concatImages')
            // concatImages.appendChild(newImg);
        },
        loadImage(e){
            var preview = document.getElementById('preview');
            if(e == ''){
            this.sizeDialog = true;
            this.imgSrc = '';
            this.stepBtn1 = true;
            var preview = document.getElementsByClassName('preview');
            var img = preview[0].getElementsByTagName('img');
            img[0].src = '';
            } else {
                preview.classList.add('preview');
                this.stepBtn1 = false;
                this.imgSrc = e;
                this.$refs.cropper.replace(e);
                var img = preview[0].getElementsByTagName('img');
                img[0].src = '';
                var newImg = document.createElement("img");
                newImg.src = this.concatImg[this.concatImg.length-1];
                newImg.width = 250;
                newImg.height = 250;
                var concatImages = document.getElementById('concatImages')
                concatImages.appendChild(newImg);
            }
        },
        submit(){
            let fd= new FormData();
            fd.append("cropped_image", JSON.stringify(this.concatImg));
            fd.append("title", this.title);
            fd.append("message", this.message);
            fd.append("userId", this.user.id);
            axios.post('/api/create', fd)
            .then(
                response => (window.location.href = '/')
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        confirmSave(){
            if(this.title && this.message){
                if(this.e1 == 4){
                    this.submit();
                } else {
                    this.e1 = 4;
                }
            } else {
                this.e1 = 3;
                this.titleMessagevalidation = true
            }
        },
        getImage(){
            this.e1 = 3;
            var concat = document.getElementById('concat');
            var concatCxt = concat.getContext("2d");
            var cover = this.createImage(document.getElementById("cover"));
            var text = this.createImage(document.getElementById("text"));
            var drawCanvas = this.createImage(document.getElementById("drawCanvas"));
            this.imgBeforeConcat.push([cover,text,drawCanvas]);
            // var image = this.createImage(this.canvas);
            drawCanvas.onload = function(){
                concatCxt.drawImage(cover,0,0,500,500);
                concatCxt.drawImage(text,0,0,500,500);
                concatCxt.drawImage(drawCanvas,0,0,500,500);
            };
            let that = this;
            setTimeout(function(){
                var concatSrc = document.getElementById('concat').toDataURL('image/png');
                that.concatImg.push(concatSrc);
            }, 200);
        },
        createImage(context){
            var image = new Image;
            image.src = context.toDataURL();
            return image;
        },
        searchTimeOut() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.pen()
            }, 200);
        },
        pen(){
          this.canvasMode = 'pen'
          this.drawCanvasctx.globalCompositeOperation = 'source-over';
          this.drawCanvasctx.lineCap = this.lineCap;
          this.drawCanvasctx.lineJoin = this.lineJoin;
          this.drawCanvasctx.lineWidth = this.lineWidth;
          this.drawCanvasctx.strokeStyle = this.penColor;
        },
        eraser() {
          this.canvasMode = 'eraser'
          this.drawCanvasctx.lineCap = 'square';
          this.drawCanvasctx.lineJoin = 'square';
          this.drawCanvasctx.lineWidth = 30;
          this.drawCanvasctx.globalCompositeOperation = 'destination-out';
        },
        draw(e) {
          var x = e.layerX
          var y = e.layerY
          if(!this.isDrag) {
            return;
          }
          this.drawCanvasctx.lineTo(x, y);
          this.drawCanvasctx.stroke();
        },
        dragStart(e) {
          var x = e.layerX
          var y = e.layerY
          this.drawCanvasctx.beginPath();
          this.drawCanvasctx.lineTo(x, y);
          this.drawCanvasctx.stroke();
          this.isDrag = true;
        },
        dragEnd() {
          this.drawCanvasctx.closePath();
          this.isDrag = false;
        },
        clear() {
          this.drawCanvasctx.clearRect(0, 0, this.drawCanvas.width, this.drawCanvas.height);
        },
        cropImage(){
            this.e1 = 2;
            this.croppedImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.cover = document.getElementById("cover");
            this.drawCanvasctx.clearRect(0, 0, 500, 500);
            this.textCanvasctx.clearRect(0, 0, 500, 500);
            this.coverctx = this.cover.getContext("2d");
            this.coverctx.globalAlpha = 1;
            this.coverctx.globalCompositeOperation = 'source-over';
            this.coverctx.filter = 'grayscale(0%)';
            this.coverctx.clearRect(0, 0, 500, 500);
            var background = new Image();
            background.src = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            var that = this;
            background.onload = function(){
                    that.coverctx.drawImage(background,0,0,500,500);
                }
            var that = this;
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
                    canvasCtx[i].globalCompositeOperation = 'source-over';
                    canvasCtx[i].filter = 'grayscale(0%)';
                    canvasCtx[i].clearRect(0, 0, 500, 500);
                    coverImage[i] = new Image();
                    coverImage[i].src = background.src;
                    coverImage[i].onload = function () {
                        canvasCtx[i].drawImage(coverImage[i], 0, 0, 113.3, 113.3);
                    };
                }
            }, 500);
            this.coverctx.save();
        },
        back() {
            this.coverctx.clearRect(0, 0, 500, 500);
            this.coverctx.restore();
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                that.coverctx.drawImage(picImage, 0, 0, 500, 500);
            };
            this.coverctx.fillRect(0, 0, 500, 500);
        },
        rotate() {
            this.coverctx.clearRect(0, 0, 500, 500);
            this.coverctx.translate(500, 0);
            this.coverctx.rotate(90 * Math.PI / 180);
            this.coverctx.globalAlpha = this.globalAlpha;
            this.coverctx.fillStyle = this.color;
            this.coverctx.fillRect(0, 0, 500, 500);
            this.coverctx.globalCompositeOperation = this.globalCompositeOperation[this.model];
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                that.coverctx.drawImage(picImage, 0, 0, 500, 500);
                that.coverctx.save();
            };
        },
        click() {
            this.coverctx.clearRect(0, 0, 500, 500);
            this.coverctx.globalAlpha = this.globalAlpha;
            this.coverctx.fillStyle = this.color;
            this.coverctx.fillRect(0, 0, 500, 500);
            this.coverctx.globalCompositeOperation = this.globalCompositeOperation[this.model];
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                that.coverctx.drawImage(picImage, 0, 0, 500, 500);
                that.coverctx.save();
            };
        },
        reset() {
            this.coverctx.filter = 'grayscale(0%)';
            this.globalAlpha = 1;
            this.color = 'transparent';
            this.model = '';
            this.filters = '';
            this.coverctx.globalCompositeOperation = 'source-over';
            this.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
            this.croppedImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.cover = document.getElementById("cover");
            this.coverctx = this.cover.getContext("2d");
            this.coverctx.globalAlpha = 1;
            this.coverctx.globalCompositeOperation = 'source-over';
            this.coverctx.filter = 'grayscale(0%)';
            this.coverctx.clearRect(0, 0, 500, 500);
            var background = new Image();
            background.src = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            var that = this;
            background.onload = function(){
                    that.coverctx.drawImage(background,0,0,500,500);
                }
            var that = this;
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
                    canvasCtx[i].globalCompositeOperation = 'source-over';
                    canvasCtx[i].filter = 'grayscale(0%)';
                    canvasCtx[i].clearRect(0, 0, 500, 500);
                    coverImage[i] = new Image();
                    coverImage[i].src = background.src;
                    coverImage[i].onload = function () {
                        canvasCtx[i].drawImage(coverImage[i], 0, 0, 113.3, 113.3);
                    };
                }
            }, 500);
            this.coverctx.save();
            this.color = '#00000080';
            // that.searchTimeOut();
        },
        searchTimeOut2() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.colorImage();
            }, 200);
        },
        searchTimeOut3() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.filter();
                this.colorImage();
            }, 200);
        },
        colorImage() {
            var canvas = [];
            var canvasCtx = [];
            var coverImage = [];
            var that = this;
            setTimeout(function () {
                for (let i = 1; i < 14; i++) {
                    canvas[i] = document.getElementById("pic" + i);
                    canvasCtx[i] = canvas[i].getContext('2d');
                    canvasCtx[i].clearRect(0, 0, 500, 500);
                    canvasCtx[i].globalCompositeOperation = that.globalCompositeOperation[i - 1];
                    canvasCtx[i].globalAlpha = that.globalAlpha;
                    canvasCtx[i].fillStyle = that.color;
                    canvasCtx[i].filter = that.filters;
                    canvasCtx[i].fillRect(0, 0, 500, 500);
                    coverImage[i] = new Image();
                    coverImage[i].src = that.croppedImage;
                    coverImage[i].onload = function () {
                        canvasCtx[i].drawImage(coverImage[i], 0, 0, 113.3, 113.3);
                    };
                }
            }, 500)
        },
        filter() {
            var filterKeys = Object.keys(this.filterObject);
            var filterValues = Object.values(this.filterObject);
            var filtersArray = [];
            var newValue
            for (let i = 0; i < filterKeys.length; i++) {
                if(filterKeys[i] == 'hueRotate'){
                    newValue = `hue-rotate(${filterValues[i]}deg)`;
                    filtersArray.push(newValue);
                } else if(filterKeys[i] == 'blur'){
                    newValue = filterKeys[i] +`(${filterValues[i]}px)`;
                    filtersArray.push(newValue);
                } else {
                    newValue = filterKeys[i] +`(${filterValues[i]}%)`;
                    filtersArray.push(newValue);
                }
            };
            var filters = filtersArray.join(' ');
            this.filters = filters;
            this.coverctx.clearRect(0, 0, 500, 500);
            this.coverctx.globalAlpha = this.globalAlpha;
            this.coverctx.fillstyle = this.color;
            this.coverctx.filter = this.filters;
            this.coverctx.globalCompositeOperation = this.globalCompositeOperation[this.model];
            this.coverctx.fillRect(0, 0, 500, 500);
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                that.coverctx.drawImage(picImage, 0, 0, 500, 500);
                that.coverctx.save();
            };
        },
        selectText(event){
            // var clientRect = this.textCanvas.getBoundingClientRect()
            var clickX = event.pageX;
            var clickY = event.pageY;
            // var positionX = clientRect.left + window.pageXOffset ;
            // var positionY = clientRect.top + window.pageYOffset ;
            this.startX = event.offsetX;
            this.startY = event.offsetY;
            this.inputPosition.width = 500 - event.offsetX + 'px';
            this.selectedInputPosition.width = 500 - event.offsetX + 'px';
            for (var i = 0; i < this.texts.length; i++) {
                if (this.textHittest(this.startX, this.startY, i)) {
                  this.isActiveEditText = true;
                  this.isActive = false;
                  this.selectedEditText = i;
                  setTimeout(function(){
                    document.getElementById('selectedText').focus();
                  },100)
                  // var selectedInput = document.getElementById('selectedText');
                  // selectedInput.focus();
                  this.selectedInputPosition.top = (this.texts[i].y - this.texts[i].fontSize.replace( /px/g , "" )) + 'px';
                  this.selectedInputPosition.left = this.texts[i].x + 'px';
                  this.selectedInputPosition.color = this.texts[i].fillStyle;
                  this.texts[i].fillStyle = 'transparent';
                  var fontArray = this.texts[i].font.split(/\s+/);
                  this.selectedInputPosition.fontWeight = fontArray[0];
                  this.selectedInputPosition.fontSize = fontArray[1];
                  this.selectedInputPosition.fontStyle = fontArray[2];
                  var that = this;
                  setTimeout(that.drawText(),100)
                  this.selectedEditTextMessage = this.texts[i].text
                }
            }
            if(this.selectedEditText == -1){
              this.create(event);
            }
        },
        textHittest(x, y, textIndex) {
            var text = this.texts[textIndex];
            return (x >= text.x-20 && x <= text.x + text.width+20 && y <= text.y + 20 && y >= text.y - text.fontSize+5);
        },
        blur(e){
          e.target.blur();
        },
        create(e){
              this.isActive = true;
              var that = this;
              setTimeout(function(){
              that.inputPosition.top = e.offsetY + 'px';
              that.inputPosition.left = e.offsetX + 'px';
              document.getElementById('newText').focus();
            },100)
        },
        discribeNew(){
            this.isActive = false;
            var text = {
                text: document.getElementById('newText').value,
                x: this.startX,
                y: this.startY + Number(this.inputPosition.fontSize.replace( /px/g , "" )),
                font: `bold ${this.inputPosition.fontSize} normal`,
                fontSize: this.inputPosition.fontSize.replace( /px/g , "" ),
                fillStyle: this.inputPosition.color,
            };
            this.textCanvasctx.font = `bold ${this.inputPosition.fontSize} normal`;
            this.textCanvasctx.fillStyle = text.fillStyle;
            text.width = this.textCanvasctx.measureText(text.text).width;
            text.height = this.inputPosition.fontSize.replace( /px/g , "" );
            this.texts.push(text);
            this.drawText();
        },
        edit(){
            this.isActiveEditText = false;
            var editText = this.texts[this.selectedEditText];
            // editText.x = this.startX
            // editText.y = this.startY
            editText.text = this.selectedEditTextMessage;
            editText.font = `${this.selectedInputPosition.fontWeight} ${this.selectedInputPosition.fontSize} ${this.selectedInputPosition.fontStyle}`;
            if(editText.fontSize !== this.selectedInputPosition.fontSize.replace( /px/g , "" )){
                editText.fontSize = this.selectedInputPosition.fontSize.replace( /px/g , "" );
            }
            editText.fillStyle = this.selectedInputPosition.color;
            var that = this;
            setTimeout(function(){
                editText.width = that.textCanvasctx.measureText(that.selectedEditTextMessage).width;
            },100)
            this.drawText();
            this.selectedEditText = -1;
        },
        drawText() {
            this.textCanvasctx.clearRect(0, 0, this.textCanvas.width, this.textCanvas.height);
            for (var i = 0; i < this.texts.length; i++) {
                var text = this.texts[i];
                if(text.text == ''){
                    this.texts.splice(i, 1);
                }
            }
            for (var i = 0; i < this.texts.length; i++) {
                var text = this.texts[i];
                this.textCanvasctx.font = text.font;
                this.textCanvasctx.fillStyle = text.fillStyle;
                this.textCanvasctx.fillText(text.text, text.x, text.y);
            }
        },
        handleMouseDown(event) {
          var newText = document.getElementById('newText');
          var selectedText = document.getElementById('selectedText');
          // if(newText == document.activeElement){
          //   newText.blur();
          // }
          // if(selectedText == document.activeElement){
          //   selectedText.blur();
          // }
          this.pageY = event.pageY;
            this.startX = event.offsetX;
            this.startY = event.offsetY;
            for (var i = 0; i < this.texts.length; i++) {
                if (this.textHittest(this.startX, this.startY, i)) {
                    this.selectedText = i;
                }
            }
        },
        handleMouseUp(e) {
            this.selectedText = -1;
        },
        handleMouseOut(e) {
            this.selectedText = -1;
        },
        handleMouseMove(e) {
            if (this.selectedText < 0) {
                return;
            }
            var mouseX = e.offsetX;
            var mouseY = e.offsetY;
            var dx = mouseX - this.startX;
            var dy = mouseY - this.startY;
            this.startX = mouseX;
            this.startY = mouseY;

            var text = this.texts[this.selectedText];
            text.x += dx;
            text.y += dy;
            this.drawText();
        },
        back2edit(){
            this.e1 = 2;
            this.concatImg.pop();
        },
        deleteImage(index){
            this.concatImg.splice(index,1);
            this.imgBeforeConcat.splice(index,1);
            if(this.concatImg.length==0){
                this.e1 = 1;
                this.imgSrc = '';
                this.stepBtn1 = true;
                this.concatImageBtn = true;
                this.toolStepper = 0;
                var preview = document.getElementsByClassName('preview');
                var img = preview[0].getElementsByTagName('img');
                img[0].src = '';
            }
        }
    }
};
</script>

<style>

.eraser {
    cursor: url('/image/eraser.png') 15 15,auto;
    z-index: 5
}
#newText {
　outline: none!important;
  /* height: 64px; */
}
#newText:focus {
  outline: none!important;
  border: none!important;
}
#selectedText {
　outline: none!important;
  height: 64px;
}
#selectedText:focus {
  outline: none!important;
  border: none!important;
}

.index2{
  z-index: 2;
}


</style>

    <!-- 写真アップロードの紫ボタン -->
    <!-- project://resources/js/components/organisms/Crop.vue#line[3] -->

    <!-- 親コンポーネント -->
    <!--☆ project://resources/js/components/pages/Post.vue#3 -->

    <!-- 子コンポーネント -->
    <!--☆ project://resources/js/components/organisms/Crop.vue --><!--☆ project://resources/js/components/molecules/InputFile.vue --><!-- project://resources/js/components/atoms/InputFileBtn.vue -->

    <!-- event -->
    <!-- secondStep project://resources/js/components/organisms/Crop.vue#60 -->
    <!--☆ input  project://resources/js/components/organisms/Crop.vue#64 -->
