<template>
  <div>
    <h1>DrawTool</h1>
    <div id="canvas-area">
      <canvas id="myCanvas" @mousedown="dragStart" @mouseup="dragEnd" @mouseout="dragEnd" @mousemove="draw" :width="width" :height="height"></canvas>
    </div>
    ><button id="putImageToCanvas" @click="putImageToCanvas()">転送→</button>
    <v-btn @click="canvasInfo()">submit</v-btn>
    {{ cropImg }}
  </div>
</template>

<script>
export default {
    data() {
        return {
            canvas: null,
            context: null,
            isDrag: false,
            width: '',
            height: '',
        };
    },
    computed: {
        cropImg(){
            return this.$store.state.post.cropImage[0];
        }
    },
    mounted(){
        this.canvas = document.querySelector('#myCanvas');
        this.context = this.canvas.getContext('2d');
        //   ペンの形状[round, squre, butt]
        this.context.lineCap = 'squre';
        //   ペンを切り返したときの挙動[bevel,round,miter]
        this.context.lineJoin = 'round';
        //   ペンの太さ
        this.context.lineWidth = 20;
        //   ペンの色
        this.context.strokeStyle = '#000000';
    },
    methods: {
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
        canvasInfo(){
            var canvasInfo = this.canvas.toDataURL();
            let fd= new FormData();
            fd.append("canvasInfo", canvasInfo);
            // document.getElementById('image').value = this.canvas.toDataURL('image/png');
            // console.log(document.getElementById('image').value);
            console.log(canvasInfo)
            axios.post('api/canvas', fd)
            .then(
                response => (console.log(response)),
                console.log(response),
            )
            .catch(function (error) {
                console.log(error);
            });
        },
        putImageToCanvas() {
            var canvas = document.getElementById("myCanvas");
            var ctx = canvas.getContext("2d");

            var background = new Image();
            background.src = this.cropImg;

            //画像をCanvasのサイズに合わせて等倍して画像をcanvasに貼り付ける.
            this.width = background.naturalWidth;
            this.height = background.naturalHeight;
            background.onload = function(){
                //canvas_widthを height / width倍する.
            ctx.drawImage(background,0,0,this.width, this.height);
            // ctx.drawImage(background,0,0,canvas_width, background.height * canvas_width / background.width);
}
        }
    }
};
</script>


<style scoped>
#myCanvas {
 border: 1px solid #000000;
}
</style>
