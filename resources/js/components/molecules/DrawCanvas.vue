<template>
    <div>
        <v-card class="overflow-hidden mx-auto" height="550" max-width="500">
            <div id="canvas-area" style="position: relative;">
                <canvas id="photo" style=" z-index: 0;" :width="width" :height="height"></canvas>
                <canvas id="cover" style="z-index: 10;" :width="width" :height="height"></canvas>
                <canvas id="myCanvas" style="z-index: 20;" v-bind:class="{eraser: canvasMode === 'eraser'}" :width="width" :height="height" @mousedown.prevent="mousedown" @mouseup.prevent="mouseup" @mouseout.prevent="mouseout" @mousemove.prevent="mousemove" />
            </div>
            <v-bottom-navigation absolute hide-on-scroll scroll-target="#hide-on-scroll-example" v-model="value">
            <v-btn color="deep-purple accent-4" text　@click="pen" value="0">
                <span>ペン</span>
                <v-icon>mdi-draw</v-icon>
            </v-btn>
            <v-btn color="deep-purple accent-4" text　@click="text">
                <span>テキスト</span>
                <v-icon>mdi-format-color-text</v-icon>
            </v-btn>
            <v-btn color="deep-purple accent-4" text>
                <span>エフェクト</span>
                <v-icon>mdi-checkerboard</v-icon>
            </v-btn>
            <v-btn color="deep-purple accent-4" text>
                <span>ダウンロード</span>
                <v-icon>mdi-briefcase-download</v-icon>
            </v-btn>
            </v-bottom-navigation>
        </v-card>

  <v-stepper v-model="el" style="width: 600px; margin: auto;">
    <v-stepper-items>
      <v-stepper-content step="1">
        <v-card class="mb-12" color="grey lighten-1" height="600px">
            <button id="eraser-button" @click="eraser" >消しゴム</button>
            <button id="clear-button" @click="clear">クリア</button>
            <v-subheader class="pl-0">
            太さ
            </v-subheader>
            <v-slider v-model="context.lineWidth" max="40" min="1"></v-slider>
            Options
            Dot Size
            Swatches Max Height
            Mode
            <v-subheader class="pl-0">
            カラー
            </v-subheader>
            <v-color-picker class="ma-2" hide-mode-switch v-model="context.strokeStyle"></v-color-picker>
        </v-card>
      </v-stepper-content>

      <v-stepper-content step="2">
        <v-card class="mb-12" color="grey lighten-1" height="200px">
        <input id="theText" type="text">
        <button id="submit" @click="submit()">Draw text on canvas</button>
        </v-card>
      </v-stepper-content>

      <v-stepper-content step="3">
        <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>

            <!-- <js-accordion>
            <div slot="title">アコーディオン1</div>
            <div class="js-accordion--body" slot="body">

            </div>
            </js-accordion> -->


        <div id="tool-area" style="position: relative;">
            <v-subheader class="pl-0">
            太さ
            </v-subheader>
            <v-slider v-model="context.lineWidth" max="40" min="1"></v-slider>
            Options
            Dot Size
            Swatches Max Height
            Mode
            <v-subheader class="pl-0">
            カラー
            </v-subheader>
            <button id="pen-button" @click="pen" >ペン</button>
            <button id="text-button" @click="text" >テキスト</button>
            <button id="rotate-button" @click="gray()">反転</button>
            <button id="reflection-button" @click="reflection()">反映</button>
            <v-btn @click="$emit('abcd', concatImg)">保存</v-btn>



        </div>
            <!-- <canvas id="concat" :width="width" :height="height" /> -->
    </div>
</template>
<script>
import JSaccordion from './JSaccordion'
export default {
    name: "DrawTool",
    components: {
        'js-accordion': JSaccordion,
    },
    data() {
        return {
            canvasMode: 'penBlack',
            canvas: document.getElementById('myCanvas'),
            context: {
                lineWidth: '',
                strokeStyle: '',
            },
            isDrag: false,
            width: '500px',
            height: '500px',
            photocxt: '',
            concatImg: '',
            picker: '',
            texts: [],
            selectedText: -1,
            offsetX: '',
            offsetY: '',
            scrollX: '',
            scrollY: '',
            startX: '',
            startY: '',
            font: '',
            length: 4,
            onboarding: '',
            value: 0,
            };
    },
    computed: {
        el(){
            return Number(this.value) + 1;
        }
    },
    mounted(){
        var canvas = this.canvas;
        this.canvas = document.getElementById('myCanvas')
        this.context = this.canvas.getContext('2d')
        this.context.lineCap = 'round';
        this.context.lineJoin = 'round';
        this.context.lineWidth = 10;
        this.photocxt = document.getElementById('photo').getContext('2d');
        var canvasOffset = canvas.getBoundingClientRect();
        this.offsetX = canvasOffset.left;
        this.offsetY = canvasOffset.top;
        this.scrollX = canvas.scrollLeft;
        this.scrollY = canvas.scrollTop;

        this.canvasMode = 'penBlack';
        this.context.globalCompositeOperation = 'source-over';
        this.context.lineCap = 'round';
        this.context.lineJoin = 'round';
        this.onboarding = 0;
    },
    methods: {
              next () {
        this.onboarding = this.onboarding + 1 === this.length
          ? 0
          : this.onboarding + 1
      },
      prev () {
        this.onboarding = this.onboarding - 1 < 0
          ? this.length - 1
          : this.onboarding - 1
      },
        mousedown(e){
            if(this.canvasMode == 'penBlack' || this.canvasMode == 'eraser'){
                this.dragStart(e);
            } else {
                this.dragText(e)
            }
        },
        mouseup(e){
            if(this.canvasMode == 'penBlack' || this.canvasMode == 'eraser'){
                this.dragEnd(e);
            } else {
                this.dropText(e);
            }
        },
        mouseout(e){
            if(this.canvasMode == 'penBlack' || this.canvasMode == 'eraser'){
                this.dragEnd(e);
            } else {
                this.dropText(e);
            }
        },
        mousemove(e){
            if(this.canvasMode == 'penBlack' || this.canvasMode == 'eraser'){
                this.draw(e);
            } else {
                this.move(e);
            }
        },
        gray(){
            var photo = this.createImage(document.getElementById("cover"));
            let ctx = photo.getContext("2d");
            ctx.drawImage(image, 0, 0, image.width, image.height)
            let src = ctx.getImageData(0, 0, image.width, image.height)
            let dst = ctx.createImageData(image.width, image.height)

            for (let i = 0; i < src.data.length; i += 4) {
            let y = 0.2126 * src.data[i] + 0.7152 * src.data[i + 1] + 0.0722 * src.data[i + 2]
            y = parseInt(y, 10)
            dst.data[i] = y
            dst.data[i + 1] = y
            dst.data[i + 2] = y
            dst.data[i + 3] = src.data[i + 3]
            }
            ctx.putImageData(dst, 0, 0)
        },

        // 描画
        draw :function(e) {
        var x = e.layerX
        var y = e.layerY

        if(!this.isDrag) {
            return;
        }

        this.context.lineTo(x, y);
        this.context.stroke();
        },
        // 描画開始（mousedown）
        dragStart:function(e) {
        var x = e.layerX
        var y = e.layerY

        this.context.beginPath();
        this.context.lineTo(x, y);
        this.context.stroke();

        this.isDrag = true;
        },
        // 描画終了（mouseup, mouseout）
        dragEnd: function() {
        this.context.closePath();
        this.isDrag = false;
        },
        clear: function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
        },
        // 消しゴムモード
        eraser: function() {
        // カーソル変更
        this.canvasMode = 'eraser'
        this.context.globalCompositeOperation = 'destination-out';
        // 描画設定
        this.context.lineCap = 'square';
        this.context.lineJoin = 'square';
        },
        pen(){
        this.canvasMode = 'penBlack';
        this.context.globalCompositeOperation = 'source-over';
        this.context.lineCap = 'round';
        this.context.lineJoin = 'round';
        this.onboarding = 0;
        },
        text(){
            this.canvasMode = 'text';
            this.canvas = document.getElementById('myCanvas')
            this.context.lineCap = 'round';
            this.context.lineJoin = 'round';
            this.context.lineWidth = 10;
            var canvasOffset = this.canvas.getBoundingClientRect();
            this.offsetX = canvasOffset.left;
            this.offsetY = canvasOffset.top;
            this.scrollX = this.canvas.scrollLeft;
            this.scrollY = this.canvas.scrollTop;
            this.onboarding = 1;
        },
        // rotate(){
        //     var ct = this.photocxt
        // ct.beginPath();
        // ct.rect(0, 0, 600, 600);
        // ct.stroke();
        // ct.rotate(180);
        // },
        reflection(){
            var concat = document.getElementById('concat');
            var concatCxt = concat.getContext("2d");
            var photo = this.createImage(document.getElementById("photo"));

            // var cover = this.createImage(document.getElementById("cover"));
            var image = this.createImage(this.canvas);
            console.log(image);
            image.onload = function(){
                concatCxt.drawImage(photo,0,0,600,600);
                concatCxt.drawImage(image,0,0,600,600);
            };
            let that = this;
            setTimeout(function(){
                that.concatImg = concat.toDataURL('image/png');
            }, 500);
        },
        createImage(context){
            var image = new Image;
            image.src = context.toDataURL();
            return image;
        },
        drawText() {
            this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
            for (var i = 0; i < this.texts.length; i++) {
                var text = this.texts[i];
                this.context.fillText(text.text, text.x, text.y);
            }
        },
        textHittest(x, y, textIndex) {
            var text = this.texts[textIndex];
            return (x >= text.x && x <= text.x + text.width && y >= text.y - text.height && y <= text.y);
        },
        dragText(e) {
            this.startX = parseInt(e.clientX - this.offsetX);
            this.startY = parseInt(e.clientY - this.offsetY);
            // Put your mousedown stuff here
            for (var i = 0; i < this.texts.length; i++) {
                if (this.textHittest(this.startX, this.startY, i)) {
                    this.selectedText = i;
                }
            }
        },
        dropText(e) {
            this.selectedText = -1;
        },
        move(e) {
            if (this.selectedText < 0) {
                return;
            }
            var mouseX = parseInt(e.clientX - this.offsetX);
            var mouseY = parseInt(e.clientY - this.offsetY);

            // Put your mousemove stuff here
            var dx = mouseX - this.startX;
            var dy = mouseY - this.startY;
            this.startX = mouseX;
            this.startY = mouseY;

            var text = this.texts[this.selectedText];
            text.x += dx;
            text.y += dy;
            this.drawText();
        },
        submit(){
            this.canvasMode = 'text';
            // calc the y coordinate for this text on the canvas
            var y = this.texts.length * 20 + 50;

            // get the text from the input element
            var text = {
                text: document.getElementById('theText').value,
                x: 20,
                y: y
            };

            // calc the size of this text for hit-testing purposes
            this.context.font = '48px serif';
            this.context.fillStyle = 'rgba(0, 0, 255)';
            text.width = this.context.measureText(text.text).width;
            text.height = 16;

            // put this new text in the texts array
            this.texts.push(text);

            // redraw everything
            this.drawText();

        }
    }
};
</script>


<style scoped>
    canvas{
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
