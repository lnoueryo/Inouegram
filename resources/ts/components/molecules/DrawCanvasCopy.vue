<template>
  <div>
    <div id="canvas-area" style="position: relative;">
        <canvas id="photo" style="position: absolute; z-index: 0; top: 50px;" :width="width" :height="height"></canvas>
        <canvas id="cover" style="position: absolute; z-index: 10; top: 50px" :width="width" :height="height"></canvas>
        <div contenteditable="true">
        <canvas id="myCanvas" style="position: absolute; z-index: 20; top: 50px " v-bind:class="{eraser: canvasMode === 'eraser'}" :width="width" :height="height" @mousedown="dragStart" @mouseup="dragEnd" @mouseout="dragEnd" @mousemove="draw"></canvas>
        </div>
    </div>
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

<v-color-picker
      class="ma-2"
      hide-mode-switch
      v-model="context.strokeStyle"
    ></v-color-picker><br><br><br><br><br><br><br>
        <button id="pen-button" @click="pen" >消しゴム</button>
        <button id="eraser-button" @click="eraser" >消しゴム</button>
        <button id="clear-button" @click="clear">クリア</button>
        <button id="rotate-button" @click="gray()">反転</button>
        <button id="reflection-button" @click="reflection">反映</button>
        <v-btn @click="save()">保存</v-btn>
    </div>
        <canvas id="concat" :width="width" :height="height" />
  </div>
</template>
<script>
export default {
    name: "DrawTool",
    data() {
        return {
        canvasMode: 'penBlack',
            canvas: null,
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
        };
    },
    mounted(){
        this.canvas = document.getElementById('myCanvas')
        this.context = this.canvas.getContext('2d')
        this.context.lineCap = 'round';
        this.context.lineJoin = 'round';
        this.context.lineWidth = 10;
        this.photocxt = document.getElementById('photo').getContext('2d');
    },
    methods: {
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
    },
    rotate(){
        var ct = this.photocxt
    ct.beginPath();
    ct.rect(0, 0, 600, 600);
    ct.stroke();
    ct.rotate(180);
    },
    reflection(){
        var concat = document.getElementById('concat');
        var concatCxt = concat.getContext("2d");
        var photo = this.createImage(document.getElementById("photo"));
        var cover = this.createImage(document.getElementById("cover"));
        var image = this.createImage(this.canvas);
        image.onload = function(){
            concatCxt.drawImage(photo,0,0,600,600);
            concatCxt.drawImage(image,0,0,600,600);
        };
        let that = this;
        setTimeout(function(){
            that.concatImg = document.getElementById('concat').toDataURL('image/png');
        }, 200);
    },
        createImage(context){
            var image = new Image;
            image.src = context.toDataURL();
            return image;
        },
    }
};
</script>


<style scoped>
#myCanvas {
 border: 1px solid #000000;
}
</style>
