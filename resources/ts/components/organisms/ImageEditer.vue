<template>
    <div>

    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs'
export default {
    name: "DrawTool",
    components: {
        VueCropper,
    },
    data() {
      return {
        imgSrc: 'storage/image/panda.png',
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
        inputPosition: {fontSize: '15px', color: 'black', fontWeght: '500', fontStyle: 'normal', top: '100px', left: '100px', position: 'absolute', zIndex: 1},
        selectedInputPosition: {fontSize: '15px', color: 'black', fontWeght: '500', fontStyle: 'normal', top: '100px', left: '100px', position: 'absolute', zIndex: 1},
      };
    },
    computed: {
        toolStepper(){
            return Number(this.value) + 1;
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
                return this.inputPosition.fontSize.replace( /px/g , "" );
            },
            set(newValue) {
                this.inputPosition.fontSize = newValue + 'px';
            }
        },
        selectedFontSize: {
            get() {
                return this.selectedInputPosition.fontSize.replace( /px/g , "" );
            },
            set(newValue) {
                this.selectedInputPosition.fontSize = newValue + 'px';
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
        var canvasOffset = this.textCanvas.getBoundingClientRect();
        this.offsetX = canvasOffset.left;
        this.offsetY = canvasOffset.top;
        this.scrollX = this.textCanvas.scrollLeft;
        this.scrollY = this.textCanvas.scrollTop;
    },
    methods: {
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
            this.croppedImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.cover = document.getElementById("cover");
            this.coverctx = this.cover.getContext("2d");
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
            this.coverctx.globalCompositeOperation = 'source-over';
            this.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
            this.coverctx.clearRect(0, 0, 500, 500);
            var myImage = new Image();
            myImage.src = this.croppedImage;
            var that = this;
            myImage.onload = function () {
                that.coverctx.drawImage(myImage, 0, 0, 500, 500);
                that.coverctx.save();
            };
            this.color = '#00000080';
            that.searchTimeOut();
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
        blur(e){
          e.target.blur();
        },
        create(e){
              this.isActive = true;
              var that = this;
              setTimeout(function(){
              that.startX = parseInt(e.clientX - that.offsetX);
              that.startY = parseInt(e.clientY - that.offsetY);
              var currentDiv = document.getElementById("abc");
              that.inputPosition.top = that.startY + 20 + 'px';
              that.inputPosition.left = that.startX + 77 + 'px';
              document.getElementById('newText').focus();
              },100)
        },
        discribeNew(){
          this.isActive = false;
          var text = {
              text: document.getElementById('newText').value,
              x: this.startX,
              y: this.startY,
              font: `bold ${this.inputPosition.fontSize} normal`,
              fillStyle: this.inputPosition.color,
          };
          this.textCanvasctx.font = text.font;
          this.textCanvasctx.fillStyle = text.fillStyle;
          text.width = this.textCanvasctx.measureText(text.text).width;
          text.height = this.inputPosition.fontSize.replace( /px/g , "" );
          this.texts.push(text);
          this.drawText();

        },
        edit(){
          this.isActiveEditText = false;
          var editText = this.texts[this.selectedEditText];
          editText.text = this.selectedEditTextMessage;
          editText.width = this.textCanvasctx.measureText(this.selectedEditTextMessage).width;
          editText.fillStyle = this.selectedInputPosition.color;
          editText.font = `${this.selectedInputPosition.fontWeight} ${this.selectedInputPosition.fontSize} ${this.selectedInputPosition.fontStyle}`;
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
        selectText(event){
            this.startX = parseInt(event.clientX - this.offsetX);
            this.startY = parseInt(event.clientY - this.offsetY);
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
                  this.selectedInputPosition.top = this.texts[i].y + 20 + 'px';
                  this.selectedInputPosition.left = this.texts[i].x + 80 + 'px';
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
            return (x >= text.x-20 && x <= text.x + text.width+20 && y >= text.y - text.height-10 && y <= text.y+10);
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
            this.startX = parseInt(event.clientX - this.offsetX);
            this.startY = parseInt(event.clientY - this.offsetY);
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
            var mouseX = parseInt(e.clientX - this.offsetX);
            var mouseY = parseInt(e.clientY - this.offsetY);
            var dx = mouseX - this.startX;
            var dy = mouseY - this.startY;
            this.startX = mouseX;
            this.startY = mouseY;

            var text = this.texts[this.selectedText];
            text.x += dx;
            text.y += dy;
            this.drawText();
        },
    }
};
</script>

<style>

.eraser {
    cursor: url('/storage/image/eraser.png') 15 15,auto;
    z-index: 2
}
#newText {
　outline: none!important;
  height: 64px;
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
#canvas-area .preview{
    width: 500px!important;
    height: 500px!important;
}
</style>
