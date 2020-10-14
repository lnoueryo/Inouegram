<template>
  <div>
    <h1>DrawTool</h1>
    <div id="canvas-area">
        <draw-canvas @abcd="canvasInfo($event)"></draw-canvas><!-- width,heightは写真の大きさに合わせる -->
    </div>
    <p>
        <a :href="cropImage" :download="filename">ダウンロード</a>
    </p>
    <!-- <button id="putImageToCanvas" @click="putImageToCanvas()">転送→</button> -->
  </div>
</template>

<script>
import DrawCanvas from '../molecules/DrawCanvas'
export default {
    components: {
        'draw-canvas': DrawCanvas,
    },
    data() {
        return {
            canvas: null,
            isDrag: false,
            cropImage: this.$store.state.post.cropImage,
            filename: 'abc'
        };
    },
    methods: {
        canvasInfo(edittedImg){
            var canvasInfo = edittedImg;
            let fd= new FormData();
            fd.append("canvasInfo", canvasInfo);
            console.log(canvasInfo)
            axios.post('api/canvas', fd)
            .then(
                response => (console.log('abc')),

            )
            .catch(function (error) {
                console.log(error);
            });
            console.log(canvasInfo)
        },
    }
}
</script>


<style scoped>
#myCanvas {
 border: 1px solid #000000;
}
</style>
