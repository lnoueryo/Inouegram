<template>
    <div>
        <label>写真：</label><br>
        <canvas id="canvas" height="0"></canvas><br>
        <label>
            <span class="btn btn-primary btn-sm">
                画像を選択
                <input type="file" name="photo" v-on:change="drawCanvas">
            </span>
        </label>
        <div id="result"></div>
        <v-btn @click="canvasInfo()">submit</v-btn>
    </div>
</template>

<script>
export default {
    methods: {
        drawCanvas(e) {
            let fileData = e.target.files[0]
            if (!fileData.type.match('image.*')) {
                alert('画像を選択してください')
                return
            }
            let canvas = document.getElementById('canvas');
            let canvasWidth = 400;
            let canvasHeight = 300;
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;
            let ctx = canvas.getContext('2d');

            let reader = new FileReader();
            let that = this;
            reader.onload = function() {
                let uploadImgSrc = reader.result;
                // canvas上に画像を重ねて表示
                let img = new Image();
                img.src = uploadImgSrc;
                img.onload = function() {
                    ctx.drawImage(img, 0, 0, canvasWidth, this.height * (canvasWidth / this.width));
                    // imgをloadした後にframe.jpgをloadして乗せる
                    let frame = new Image();
                    frame.src = "img/frame.jpg";
                    frame.onload = function() {
                        ctx.drawImage(frame, 0, 200, 400, 100);
                        // canvasを画像に変換
                        let imgSrc = canvas.toDataURL();
                        that.$store.commit('setImage', imgSrc)
                    }
                }
            }
            reader.readAsDataURL(fileData);
        },
        canvasInfo(){
            let canvas = document.getElementById('canvas');
            var canvasInfo = canvas.toDataURL();
            // document.getElementById('image').value = this.canvas.toDataURL('image/png');
            // console.log(document.getElementById('image').value);
            axios.post('api/canvas', {
                params: {'canvasInfo': canvasInfo},
            })
            .then(
                // response => (this.followed = response.data)
                console.log(response),
            )
            .catch(
                console.log('error'),
            );
        },
    }
}
</script>
