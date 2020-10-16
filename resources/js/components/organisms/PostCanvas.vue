<template>
  <div>
    <h1>DrawTool</h1>
    <div id="canvas-area">
        <draw-canvas @abcd="canvasInfo($event)"></draw-canvas><!-- width,heightは写真の大きさに合わせる -->
    </div>
    <p>
        <a :href="cropImage" :download="filename">ダウンロード</a>
    </p>
    <!-- <v-card height="400px">
    <v-footer
      v-bind="localAttrs"
      :padless="padless"
      app
    >
      <v-card
        flat
        tile
        width="100%"
        class="red lighten-1 text-center"
      >
        <v-card-text>
          <v-btn
            v-for="icon in icons"
            :key="icon"
            class="mx-4"
            icon
          >
            <v-icon size="24px">
              {{ icon }}
            </v-icon>
          </v-btn>
        </v-card-text>

      </v-card>
    </v-footer>
  </v-card> -->
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
            filename: 'abc',
            // icons: [
            //     'mdi-home',
            //     'mdi-email',
            //     'mdi-calendar',
            //     'mdi-delete',
            // ],
            // items: [
            //     'default',
            //     'absolute',
            //     'fixed',
            // ],
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
