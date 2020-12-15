<template>
    <div>
        <v-btn style="z-index: 5;" fixed top right @click="cropStart" color="primary" :disabled="stepBtn1" v-if="e1==1">トリミング</v-btn>
        <v-stepper v-model="e1" class="elevation-0">
            <v-stepper-items>
                <v-stepper-content step="1" class="pt-1">
                    <!-- <v-container> -->
                        <v-layout justify-space-around wrap>
                            <div v-if="imgSrc !== ''">
                                <vue-cropper
                                    ref="cropper"
                                    :guides="true"
                                    :view-mode="2"
                                    :auto-crop-area="0.5"
                                    :min-container-width="280"
                                    :min-container-height="280"
                                    :background="true"
                                    :rotatable="false"
                                    :src="imgSrc"
                                    :img-style="{ 'width': 350 + 'px', 'height': 350 + 'px' }"
                                    :aspect-ratio="1 / 1"
                                    drag-mode="crop"
                                />
                            <div>
                                <input-file :size="size" @selectedImage="loadImage($event)"></input-file>
                            </div>
                            </div>
                            <div v-else>
                                <input-file-image :size="size" @selectedImage="loadImage($event)"></input-file-image>
                            </div>
                        </v-layout>
                    <!-- </v-container> -->
                </v-stepper-content>

                <v-stepper-content step="2" class="pt-1">
                    <v-card class="overflow-hidden" height="280" width="280" style="margin: auto;">
                        <div id="canvas-area" style="position: relative">
                            <div v-if="isActive">
                                <input id="newText" :style="inputPosition" type="text" @keyup.enter="discribeNew">
                            </div>
                            <div v-if="isActiveEditText">
                                <input id="selectedText" :style="selectedInputPosition" type="text" v-model="selectedEditTextMessage" @blur="edit" @keyup.enter="blur">
                            </div>
                            <canvas id="drawCanvas" style="position: absolute;top: 0; left: 0;z-index: 1;" :class="{eraser: canvasMode === 'eraser'}+{index2: editItems === 1}" width="280" height="280" @touchstart.prevent="dragStart" @touchend="dragEnd" @touchmove="draw"></canvas>
                            <canvas id="cover" :class="{index2: editItems === 0}" style="position: absolute; top: 0; left: 0;" width="280" height="280"></canvas>
                            <canvas id="text" :class="{index2: editItems === 2}" style="position: absolute;top: 0; left: 0;" width=280 height=280 @dblclick="selectText" @mousedown.prevent="handleMouseDown" @mousemove.prevent="handleMouseMove" @mouseup.prevent="handleMouseUp" @mouseout.prevent="handleMouseOut"></canvas>
                        </div>
                    </v-card>
                    <!-- <v-stepper v-model="toolStepper" style="width: 1000px;">
                        <v-stepper-items>

                            <v-stepper-content step="3">
                                <div v-if="isActive">
                                    <v-slider min="10" max="64" v-model="fontSize" thumb-label="always" :thumb-size="30"></v-slider>
                                    <v-color-picker v-model="inputPosition.color"></v-color-picker>
                                    <v-btn-toggle v-model="toggle_multiple" dense background-color="primary" dark multiple>
                                    <v-btn>Bold</v-btn></v-btn-toggle></div>
                                <div v-if="isActiveEditText">
                                    <v-slider min="10" max="64" v-model="selectedFontSize" thumb-label="always" :thumb-size="30"></v-slider>
                                    <v-color-picker v-model="selectedInputPosition.color"></v-color-picker>aaa
                                </div>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper> -->
                    <v-card flat tile max-width="300" style="margin: auto">
                        <v-window v-model="editItems" touchless>
                            <v-window-item :value="0">
                                <v-window v-model="coverItem" class="px-2 mt-2" touchless>
                                    <v-window-item :value="0">
                                        <!-- <v-slider style="margin-top: 35px" label="opacity" v-model.lazy="opacity" min="4" max="10" thumb-label="always" @input="searchTimeOut2" thumb-color="pink" color="pink"></v-slider> -->
                                        <v-slider style="margin-top: 35px" label="blur" v-model.lazy="filterObject.blur" min="0" max="10" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="purple" color="purple"></v-slider>
                                        <v-slider label="brightness" v-model.lazy="filterObject.brightness" min="35" max="280" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="red"  color="red"></v-slider>
                                        <v-btn color="pink" @click="resetStart(coverctx)">reset</v-btn>
                                        <v-btn color="purple" @click="rotate(coverctx)">rotate</v-btn>
                                        <!-- <v-btn color="black" @click="back">back</v-btn> -->
                                    </v-window-item>

                                    <v-window-item :value="1">
                                        <v-slider style="margin-top: 35px" label="contrast" v-model.lazy="filterObject.contrast" min="0" max="400" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="orange" color="orange"></v-slider>
                                        <v-slider label="grayscale" v-model.lazy="filterObject.grayscale" min="0" max="100" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="indigo" color="indigo"></v-slider>
                                        <v-slider label="hue-rotate" v-model.lazy="filterObject.hueRotate" min="0" max="359" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="green" color="green"></v-slider>
                                    </v-window-item>
                                    <v-window-item :value="2">
                                        <v-slider style="margin-top: 35px" label="invert" v-model.lazy="filterObject.invert" min="0" max="100" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="deep-purple" color="deep-purple"></v-slider>
                                        <v-slider label="saturate" v-model.lazy="filterObject.saturate" min="0" max="280" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="light-blue" color="light-blue"></v-slider>
                                        <v-slider label="sepia" v-model.lazy="filterObject.sepia" min="0" max="100" thumb-label="always" @input="searchTimeOut3(coverctx)" thumb-color="lime" color="lime"></v-slider>
                                    </v-window-item>
                                    <v-window-item :value="3">
                                        <v-color-picker v-model="color" hide-canvas hide-inputs class="ma-2" @input="searchTimeOut2(coverctx)"></v-color-picker>
                                        <v-chip-group column active-class="primary--text">
                                            <v-chip v-for="(tag, index) in globalCompositeOperation" :key="index" @click="click(index)">
                                                {{ tag }}
                                            </v-chip>
                                        </v-chip-group>
                                    </v-window-item>
                                </v-window>
                                <v-card-actions class="justify-space-between">
                                    <v-btn text @click="coverPrev">
                                        <v-icon>mdi-chevron-left</v-icon>
                                    </v-btn>
                                    <v-item-group v-model="coverItem" class="text-center" mandatory>
                                        <v-item v-for="n in coverLength" :key="`btn-${n}`" v-slot="{ active, toggle }">
                                            <v-btn :input-value="active" icon @click="toggle">
                                                <v-icon>mdi-record</v-icon>
                                            </v-btn>
                                        </v-item>
                                    </v-item-group>
                                    <v-btn text @click="coverNext">
                                        <v-icon>mdi-chevron-right</v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-window-item>
                            <v-window-item :value="1">
                                <v-window v-model="drawItem" class="px-2 mt-2" touchless>
                                    <v-window-item :value="0">
                                        <v-btn id="pen-button" class="align-self-start mr-2" @click="pen(drawCanvasctx)">ペン</v-btn>
                                        <v-btn id="eraser-button" class="align-self-start mr-2" @click="eraser(drawCanvasctx)" >消しゴム</v-btn>
                                        <v-btn id="clear-button" class="align-self-start mr-2" @click="clear(drawCanvasctx)">クリア</v-btn>
                                        <div class="py-4">
                                            <v-color-picker class="mb-5" canvas-height="75" dot-size="20" hide-inputs v-model="penColor" @input="searchTimeOut(drawCanvasctx)"></v-color-picker>
                                        </div>
                                    </v-window-item>
                                    <v-window-item :value="1">
                                    <v-col cols="12" sm="12" class="d-flex">
                                        <v-select class="px-1" v-model="lineCap" :items="lineCaps" label="lineCap" outlined @input="searchTimeOut(drawCanvasctx)"></v-select>
                                        <v-select class="px-1" v-model="lineJoin " :items="lineJoins" label="lineJoin" outlined @input="searchTimeOut(drawCanvasctx)"></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="11">
                                            <v-slider label="lineWidth" v-model.lazy="lineWidth" min="2" max="25" thumb-label="always" @input="searchTimeOut(drawCanvasctx)" thumb-color="pink" color="pink"></v-slider>
                                    </v-col>
                                    </v-window-item>
                                </v-window>
                                <v-card-actions class="justify-space-between">
                                    <v-btn text @click="drawPrev">
                                        <v-icon>mdi-chevron-left</v-icon>
                                    </v-btn>
                                    <v-item-group v-model="drawItem" class="text-center" mandatory>
                                        <v-item v-for="n in drawLength" :key="`btn-${n}`" v-slot="{ active, toggle }">
                                            <v-btn :input-value="active" icon @click="toggle">
                                                <v-icon>mdi-record</v-icon>
                                            </v-btn>
                                        </v-item>
                                    </v-item-group>
                                    <v-btn text @click="drawNext">
                                        <v-icon>mdi-chevron-right</v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-window-item>
                        </v-window>
                    </v-card>
                    <!-- <v-bottom-navigation fixed bottom hide-on-scroll scroll-target="#hide-on-scroll-example" v-model="value">
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
                        <v-btn color="deep-purple accent-4" text>
                            <span>ダウンロード</span>
                            <v-icon>mdi-briefcase-download</v-icon>
                        </v-btn>
                    </v-bottom-navigation> -->
                </v-stepper-content>
<!--FIXME: タイトルと内容が埋もれる-->
                <div v-if="e1==2&&editItems==0">
                <v-btn class="elevation-5" fixed right top color="primary" style="z-index: 5;" @click="editItems = 1">次へ</v-btn>
                <v-btn class="elevation-5" style="right: 90px;z-index: 5;" fixed top @click="e1 = 1;">戻る</v-btn>
                </div>
                <div v-if="e1==2&&editItems==1">
                <v-btn class="elevation-5" fixed right top color="primary" style="z-index: 5;" @click="getImage">決定</v-btn>
                <v-btn class="elevation-5" style="right: 90px;z-index: 5;" fixed top @click="editItems = 0;">戻る</v-btn>
                </div>
                <v-stepper-content step="3">
                    <canvas id="concat" width="280" height="280" v-show="false"></canvas>
                    <div v-if="showConcatImg">
                        <v-carousel height="280" v-model="carousel">
                            <v-carousel-item v-for="(image,index) in showConcatImg" :key="index" :src="image" reverse-transition="fade-transition" transition="fade-transition"><v-btn style="z-index: 5;" top right fixed color="red accent-3" small @click="deleteImage(index)">削除</v-btn></v-carousel-item>
                        </v-carousel>
                    </div>
                    <div class="pt-4 mx-auto"  style="width: 100%;max-width: 600px;">
                        <v-text-field label="タイトル" value="Grocery delivery" hint="For example, flowers or used cars" v-model="title"></v-text-field>
                    <v-textarea v-model="message" color="teal" rows="3" counter maxlength="280">
                        <template v-slot:label>
                            <div>
                            内容 <small>(content)</small>
                            </div>
                        </template>
                    </v-textarea>
                    </div>
                </v-stepper-content>

                <v-stepper-content step="4">
                    <div class="d-flex">
                        <v-card class="mx-auto my-5" max-width="280" style="width: 100%">
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
                            <v-carousel height="280" width="280">
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
        <div>
            <v-btn style="z-index: 5;" fixed top right color="primary" @click="e1=4" :disabled="confirmMessage" v-if="e1==3">確認</v-btn>
            <v-btn style="right: 90px;z-index: 5;" fixed top right color="purple" @click="oneMoreImage" v-if="e1==3">追加</v-btn>
            <!-- <v-btn @click="back2edit">戻る</v-btn> -->
        </div>
        <div v-if="showConcatImg">
            <!-- <div class="d-flex flex-wrap mb-3" style="justify-content: space-between;">
                <div class="mb-3" v-for="(image, index) in concatImg" :key="index">
                    <v-img class="mb-1 red accent-3" color="red accent-3" width="100" :src="image"></v-img>
                    <div class="mb-1">
                        <v-btn color="red accent-3" block small @click="deleteImage(index)">削除</v-btn>
                    </div>
                    <div>
                        <v-btn color="green accent-3" block small @click="editImage(index)">編集</v-btn>
                    </div>
                </div>
            </div> -->
            <v-sheet class="mx-auto" elevation="0" max-width="800">
                <v-slide-group v-model="model" active-class="success" show-arrows>
                    <v-slide-item v-for="(image, index) in concatImg" :key="index">
                        <v-card class="ma-4">
                            <v-img class="mb-1" width="100" :src="image"></v-img>
                            <div class="mb-1">
                                <v-btn color="red accent-3" block small @click="deleteImage(index)">削除</v-btn>
                            </div>
                            <!-- <div>
                                <v-btn color="green accent-3" block small @click="editImageStart(index)">編集</v-btn>
                            </div> -->
                        </v-card>
                    </v-slide-item>
                </v-slide-group>
                <v-btn class="ml-4" color="primary" @click="confirmSave()">保存</v-btn>
            </v-sheet>
        </div>
    <v-dialog v-model="sizeDialog" width="280">

      <v-card>
        <v-card-title class="headline grey lighten-2">
          エラー
        </v-card-title>

        <v-card-text>
            ファイルサイズが上限を超えています。4M以下のイメージを選択してください
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="sizeDialog = false">閉じる</v-btn>
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
    <v-overlay :value="cropProgress">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <!-- <v-row justify="center">
        <v-dialog v-model="currentImageDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card style="height: 50;">
            <v-toolbar dark color="primary">
            <v-btn icon dark @click="currentImageDialog = false">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn dark text @click="newEditItems = 0">フィルター</v-btn>
            <v-btn dark text @click="newEditItems = 1">ペン</v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn dark text @click="editImageSaveStart(editIndex)">
                Save
                </v-btn>
            </v-toolbar-items>
            </v-toolbar>
            <v-card class="overflow-hidden" height="280" width="280" style="margin: auto;">
                <div id="canvas-area" style="position: relative">
                    <canvas id="editDrawCanvas" :class="{index2: newEditItems === 1}" style="position: absolute;top: 0; left: 0;z-index: 1;" width="280" height="280" @touchstart.prevent="dragStart" @touchend="dragEnd" @touchmove="draw"></canvas>
                    <canvas id="editCover" style="position: absolute; top: 0; left: 0;z-index: 0;" width="280" height="280"></canvas>
                    <canvas id="text" :class="{index2: newEditItems === 0}" style="position: absolute;top: 0; left: 0;" width=280 height=280 @dblclick="selectText" @mousedown.prevent="handleMouseDown" @mousemove.prevent="handleMouseMove" @mouseup.prevent="handleMouseUp" @mouseout.prevent="handleMouseOut"></canvas>
                    <canvas id="edittedImage" width="280" height="280" v-show="false"></canvas>
                    <canvas id="editcroppedOriginalImage" style="" width="280" height="280" v-show="false"></canvas>
                </div>
            </v-card>
            <v-card flat tile max-width="300" style="margin: auto">
                <v-window v-model="newEditItems" touchless>
                    <v-window-item :value="0">
                        <v-window v-model="newCoverItem" class="px-2 mt-2" touchless>
                            <v-window-item :value="0">
                                <v-slider style="margin-top: 35px" label="blur" v-model.lazy="filterObject.blur" min="0" max="10" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="purple" color="purple"></v-slider>
                                <v-slider label="brightness" v-model.lazy="filterObject.brightness" min="35" max="280" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="red"  color="red"></v-slider>
                                <v-btn color="pink" @click="resetStart(editCoverctx)">reset</v-btn>
                                <v-btn color="purple" @click="rotate(editCoverctx)">rotate</v-btn>
                            </v-window-item>

                            <v-window-item :value="1">
                                <v-slider style="margin-top: 35px" label="contrast" v-model.lazy="filterObject.contrast" min="0" max="400" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="orange" color="orange"></v-slider>
                                <v-slider label="grayscale" v-model.lazy="filterObject.grayscale" min="0" max="100" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="indigo" color="indigo"></v-slider>
                                <v-slider label="hue-rotate" v-model.lazy="filterObject.hueRotate" min="0" max="359" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="green" color="green"></v-slider>
                            </v-window-item>
                            <v-window-item :value="2">
                                <v-slider style="margin-top: 35px" label="invert" v-model.lazy="filterObject.invert" min="0" max="100" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="deep-purple" color="deep-purple"></v-slider>
                                <v-slider label="saturate" v-model.lazy="filterObject.saturate" min="0" max="280" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="light-blue" color="light-blue"></v-slider>
                                <v-slider label="sepia" v-model.lazy="filterObject.sepia" min="0" max="100" thumb-label="always" @input="searchTimeOut3(editCoverctx)" thumb-color="lime" color="lime"></v-slider>
                            </v-window-item>
                            <v-window-item :value="3">
                                <v-color-picker v-model="color" hide-canvas hide-inputs class="ma-2" @input="searchTimeOut2(editCoverctx)"></v-color-picker>
                                <v-chip-group column active-class="primary--text">
                                    <v-chip v-for="(tag, index) in globalCompositeOperation" :key="index" @click="click(index)">
                                        {{ tag }}
                                    </v-chip>
                                </v-chip-group>
                            </v-window-item>
                        </v-window>
                        <v-card-actions class="justify-space-between">
                            <v-btn text @click="newCoverPrev">
                                <v-icon>mdi-chevron-left</v-icon>
                            </v-btn>
                            <v-item-group v-model="newCoverItem" class="text-center" mandatory>
                                <v-item v-for="n in coverLength" :key="`btn-${n}`" v-slot="{ active, toggle }">
                                    <v-btn :input-value="active" icon @click="toggle">
                                        <v-icon>mdi-record</v-icon>
                                    </v-btn>
                                </v-item>
                            </v-item-group>
                            <v-btn text @click="newCoverNext">
                                <v-icon>mdi-chevron-right</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-window-item>
                    <v-window-item :value="1">
                        <v-window v-model="newDrawItem" class="px-2 mt-2" touchless>
                            <v-window-item :value="0">
                                <v-btn id="pen-button" class="align-self-start mr-2" @click="pen(editDrawCanvasctx)">ペン</v-btn>
                                <v-btn id="eraser-button" class="align-self-start mr-2" @click="eraser(editDrawCanvasctx)" >消しゴム</v-btn>
                                <v-btn id="clear-button" class="align-self-start mr-2" @click="clear(editDrawCanvasctx)">クリア</v-btn>
                                <div class="py-4">
                                    <v-color-picker class="mb-5" canvas-height="75" dot-size="20" hide-inputs v-model="penColor" @input="searchTimeOut(editDrawCanvasctx)"></v-color-picker>
                                </div>
                            </v-window-item>
                            <v-window-item :value="1">
                            <v-col cols="12" sm="12" class="d-flex">
                                <v-select class="px-1" v-model="lineCap" :items="lineCaps" label="lineCap" outlined @input="searchTimeOut(editDrawCanvasctx)"></v-select>
                                <v-select class="px-1" v-model="lineJoin " :items="lineJoins" label="lineJoin" outlined @input="searchTimeOut(editDrawCanvasctx)"></v-select>
                            </v-col>
                            <v-col cols="12" sm="11">
                                    <v-slider label="lineWidth" v-model.lazy="lineWidth" min="2" max="25" thumb-label="always" @input="searchTimeOut(editDrawCanvasctx)" thumb-color="pink" color="pink"></v-slider>
                            </v-col>
                            </v-window-item>
                        </v-window>
                        <v-card-actions class="justify-space-between">
                            <v-btn text @click="newDrawPrev">
                                <v-icon>mdi-chevron-left</v-icon>
                            </v-btn>
                            <v-item-group v-model="newDrawItem" class="text-center" mandatory>
                                <v-item v-for="n in drawLength" :key="`btn-${n}`" v-slot="{ active, toggle }">
                                    <v-btn :input-value="active" icon @click="toggle">
                                        <v-icon>mdi-record</v-icon>
                                    </v-btn>
                                </v-item>
                            </v-item-group>
                            <v-btn text @click="newDrawNext">
                                <v-icon>mdi-chevron-right</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-window-item>
                </v-window>
            </v-card>
        </v-card>
        </v-dialog>
    </v-row> -->
    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs'
import InputFile from '../molecules/InputFile.vue'
import InputFileImage from '../molecules/InputFileImage.vue'
import ImageEditer from '../organisms/ImageEditer.vue'
export default {
    name: "DrawTool",
    props: ['user'],
    components: {
        VueCropper,
        InputFile,
        InputFileImage,
        ImageEditer,
    },
    data() {
        return {
            currentImageDialog: false,
            cropProgress: false,
            titleMessagevalidation: false,
            text: 'タイトルと内容を書いてください',
            timeout: 4000,
            carousel: '',
            coverLength: 4,
            coverItem: 0,
            drawLength: 2,
            drawItem: 0,
            editItems: 0,
            basicSize: '',
            mediaSize: '',
            spSize: window.matchMedia('(max-width: 480px)'),
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
            inputPosition: {fontSize: '15px', color: 'black', fontWeght: '280', fontStyle: 'normal', top: '100px', left: '100px', position: 'absolute', zIndex: 1},
            selectedInputPosition: {fontSize: '15px', color: 'black', fontWeght: '280', fontStyle: 'normal', top: '100px', left: '100px', position: 'absolute', zIndex: 1},
            type: 'hexa',
            hexa: '#FF000000',
            size: 479,
            editDrawCanvasctx: '',
            editCoverctx: '',
            editcroppedOriginalImagectx: '',
            editIndex: '',
            newEditItems: 0,
            newCoverItem: 0,
            newDrawItem: 0,
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
        color: {
            get () {
                return this[this.type]
            },
            set (v) {
                this[this.type] = v
            },
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
    },
    methods: {
        coverNext () {
            this.coverItem = this.coverItem + 1 === this.coverLength
            ? 0
            : this.coverItem + 1
        },
        coverPrev () {
            this.coverItem = this.coverItem - 1 < 0
            ? this.coverLength - 1
            : this.coverItem - 1
        },
        newCoverNext () {
            this.newCoverItem = this.newCoverItem + 1 === this.coverLength
            ? 0
            : this.newCoverItem + 1
        },
        newCoverPrev () {
            this.newCoverItem = this.newCoverItem - 1 < 0
            ? this.coverLength - 1
            : this.newCoverItem - 1
        },
        drawNext () {
            this.drawItem = this.drawItem + 1 === this.drawLength
            ? 0
            : this.drawItem + 1
        },
        drawPrev () {
            this.drawItem = this.drawItem - 1 < 0
            ? this.drawLength - 1
            : this.drawItem - 1
        },
        newDrawNext () {
            this.newDrawItem = this.newDrawItem + 1 === this.drawLength
            ? 0
            : this.newDrawItem + 1
        },
        newDrawPrev () {
            this.newDrawItem = this.newDrawItem - 1 < 0
            ? this.drawLength - 1
            : this.newDrawItem - 1
        },
        oneMoreImage(){
            this.e1 = 1;
            this.editItems = 0;
            this.editItems = 0;
            this.coverItem = 0;
            this.drawItem = 0;
            this.imgSrc = '';
            this.stepBtn1 = true;
            this.concatImageBtn = true;
            this.globalAlpha = 1;
            this.hexa = '#FF000000';
            this.model = '';
            this.filters = '';
            this.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
            // this.croppedImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.cover = document.getElementById("cover");
            this.coverctx = this.cover.getContext("2d");
            this.coverctx.globalCompositeOperation = 'source-over';
            this.coverctx.filter = 'grayscale(0%)';
            this.coverctx.globalAlpha = 1;
        },
        loadImage(e){
            if(e == ''){
            this.sizeDialog = true;
            this.imgSrc = '';
            this.stepBtn1 = true;
            } else {
                this.stepBtn1 = false;
                this.imgSrc = e;
                this.$refs.cropper.replace(e);
                var newImg = document.createElement("img");
                newImg.src = this.concatImg[this.concatImg.length-1];
                newImg.width = 280;
                newImg.height = 280;
                var concatImages = document.getElementById('concatImages')
                concatImages.appendChild(newImg);
            }
        },
        submit(){
            this.cropProgress = true;
            let fd= new FormData();
            fd.append("cropped_image", JSON.stringify(this.concatImg));
            fd.append("title", this.title);
            fd.append("message", this.message);
            fd.append("userId", this.user.id);
            axios.post('/api/create', fd)
            .then(
                response => (window.location.href = '/'),this.cropProgress = true
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
            var coverSrc = document.getElementById("cover").toDataURL('image/png');
            var drawCanvasSrc = document.getElementById("drawCanvas").toDataURL('image/png');
            var croppedOriginalImage = this.croppedImage;
            this.imgBeforeConcat.push([coverSrc,drawCanvasSrc,croppedOriginalImage]);
            drawCanvas.onload = function(){
                concatCxt.drawImage(cover,0,0,280,280);
                concatCxt.drawImage(text,0,0,280,280);
                concatCxt.drawImage(drawCanvas,0,0,280,280);
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
        searchTimeOut(ctx) {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.pen(ctx)
            }, 200);
        },
        pen(ctx){
          this.canvasMode = 'pen'
          ctx.globalCompositeOperation = 'source-over';
          ctx.lineCap = this.lineCap;
          ctx.lineJoin = this.lineJoin;
          ctx.lineWidth = this.lineWidth;
          ctx.strokeStyle = this.penColor;
        },
        eraser(ctx) {
          this.canvasMode = 'eraser'
          ctx.lineCap = 'square';
          ctx.lineJoin = 'square';
          ctx.lineWidth = 30;
          ctx.globalCompositeOperation = 'destination-out';
        },
        draw(e) {
            var rect = e.target.getBoundingClientRect();
          var touch =e.targetTouches[0];
          var x = touch.clientX - rect.left;
          var y = touch.clientY - rect.top;
          if(!this.isDrag) {
            return;
          }
          e.target.getContext('2d').lineTo(x, y);
          e.target.getContext('2d').stroke();
        },
        dragStart(e) {
            var rect = e.target.getBoundingClientRect();
          var touch =e.targetTouches[0];
          var x = touch.clientX - rect.left;
          var y = touch.clientY - rect.top;
          e.target.getContext('2d').beginPath();
          e.target.getContext('2d').lineTo(x, y);
          e.target.getContext('2d').stroke();
          this.isDrag = true;
        },
        dragEnd(e) {
          e.target.getContext('2d').closePath();
          this.isDrag = false;
        },
        clear(ctx) {
          ctx.clearRect(0, 0, 280, 280);
        },
        cropStart(){
            let that = this;
            that.cropProgress = true;
            setTimeout(function(){
                that.cropImage();
            },100)
        },
        cropImage(){
            this.e1 = 2;
            this.croppedImage = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            this.cover = document.getElementById("cover");
            this.drawCanvasctx.clearRect(0, 0, 280, 280);
            this.textCanvasctx.clearRect(0, 0, 280, 280);
            this.coverctx = this.cover.getContext("2d");
            this.coverctx.globalAlpha = 1;
            this.coverctx.globalCompositeOperation = 'source-over';
            this.coverctx.filter = 'grayscale(0%)';
            this.coverctx.clearRect(0, 0, 280, 280);
            var background = new Image();
            background.src = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            var that = this;
            background.onload = function(){
                    that.coverctx.drawImage(background,0,0,280,280);
                }
            var that = this;
            that.cropProgress = false;
            this.coverctx.save();
        },
        back() {
            this.coverctx.clearRect(0, 0, 280, 280);
            this.coverctx.restore();
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                that.coverctx.drawImage(picImage, 0, 0, 280, 280);
            };
            this.coverctx.fillRect(0, 0, 280, 280);
        },
        rotate(ctx) {
            ctx.clearRect(0, 0, 280, 280);
            ctx.translate(280, 0);
            ctx.rotate(90 * Math.PI / 180);
            ctx.globalAlpha = this.globalAlpha;
            ctx.fillStyle = this.color;
            ctx.fillRect(0, 0, 280, 280);
            ctx.globalCompositeOperation = this.globalCompositeOperation[this.model];
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                ctx.drawImage(picImage, 0, 0, 280, 280);
                ctx.save();
            };
        },
        click(key) {
            this.coverctx.clearRect(0, 0, 280, 280);
            this.coverctx.globalAlpha = this.globalAlpha;
            this.coverctx.fillStyle = this.hexa;
            this.coverctx.fillRect(0, 0, 280, 280);
            this.coverctx.globalCompositeOperation = this.globalCompositeOperation[key-1];
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                that.coverctx.drawImage(picImage, 0, 0, 280, 280);
                that.coverctx.save();
            };
        },
        resetStart(ctx){
            let that = this;
            that.cropProgress = true;
            setTimeout(function(){
                that.reset(ctx);
            },100)
        },
        reset(ctx) {
            this.globalAlpha = 1;
            this.hexa = '#FF000000';
            this.model = '';
            this.filters = '';
            ctx.globalCompositeOperation = 'source-over';
            this.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
            ctx.filter = 'grayscale(0%)';
            ctx.clearRect(0, 0, 280, 280);
            var background = new Image();
            background.src = this.$refs.cropper.getCroppedCanvas().toDataURL('image/png');
            var that = this;
            background.onload = function(){
                    ctx.drawImage(background,0,0,280,280);
                    that.cropProgress = false;
                }
            ctx.save();
        },
        searchTimeOut2(ctx) {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.colorImage(ctx);
            }, 200);
        },
        searchTimeOut3(ctx) {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.filter(ctx);
                this.colorImage(ctx);
            }, 200);
        },
        searchTimeOut4() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.filter();
                this.colorImage();
            }, 200);
        },
        colorImage(ctx) {
            ctx.clearRect(0, 0, 280, 280);
            ctx.globalAlpha = this.globalAlpha;
            ctx.fillStyle = this.hexa;
            ctx.fillRect(0, 0, 280, 280);
            ctx.globalCompositeOperation = this.globalCompositeOperation[this.model];
            var picImage = new Image();
            picImage.src = this.croppedImage;
            var that = this;
            picImage.onload = function () {
                ctx.drawImage(picImage, 0, 0, 280, 280);
                ctx.save();
            };
        },
        filter(ctx) {
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
            ctx.clearRect(0, 0, 280, 280);
            ctx.globalAlpha = this.globalAlpha;
            ctx.fillstyle = this.hexa;
            ctx.filter = this.filters;
            ctx.globalCompositeOperation = this.globalCompositeOperation[this.model];
            ctx.fillRect(0, 0, 280, 280);
            var picImage = new Image();
            picImage.src = this.croppedImage;
            picImage.onload = function () {
                ctx.drawImage(picImage, 0, 0, 280, 280);
                ctx.save();
            };
        },
        selectText(event){
            var clickX = event.pageX ;
            var clickY = event.pageY ;
            var clientRect = this.textCanvas.getBoundingClientRect()
            var positionX = clientRect.left + window.pageXOffset ;
            var positionY = clientRect.top + window.pageYOffset ;
            this.startX = clickX - positionX ;
            this.startY = clickY - positionY ;
            this.inputPosition.width = 280 - this.startX + 'px';
            this.selectedInputPosition.width = 280 - this.startX + 'px';
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
                  this.selectedInputPosition.top = this.texts[i].y - 45 + 'px';
                  this.selectedInputPosition.left = this.texts[i].x + 15 + 'px';
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
        blur(e){
          e.target.blur();
        },
        create(e){
              this.isActive = true;
              var that = this;
              setTimeout(function(){
              that.inputPosition.top = e.pageY - 210 + 'px';
              that.inputPosition.left = e.pageX - 10 + 'px';
              document.getElementById('newText').focus();
            },100)
        },
        discribeNew(){
            this.isActive = false;
            var text = {
                text: document.getElementById('newText').value,
                x: this.startX,
                y: this.startY + (this.inputPosition.fontSize.replace( /px/g , "" )/2)-5,
                font: `bold ${this.inputPosition.fontSize} normal`,
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
            editText.text = this.selectedEditTextMessage;
            editText.font = `${this.selectedInputPosition.fontWeight} ${this.selectedInputPosition.fontSize} ${this.selectedInputPosition.fontStyle}`;
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
            this.startX = parseInt(event.clientX - this.offsetX);
            this.startY = event.pageY - 188;
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
            var mouseY = event.pageY - 188;
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
        editImageStart(index){
            let that = this;
            that.cropProgress = true;
            that.currentImageDialog = true;
            that.editIndex = index;
            setTimeout(function(){
                that.editImage(index);
            },100)
        },
        editImage(index){
            var editDrawCanvas = document.getElementById('editDrawCanvas');
            var editDrawCanvasctx = editDrawCanvas.getContext('2d');
            this.editDrawCanvasctx = editDrawCanvasctx;

            var editCover = document.getElementById('editCover');
            var editCoverctx = editCover.getContext('2d');
            this.editCoverctx = editCoverctx;

            var editcroppedOriginalImage = document.getElementById('editcroppedOriginalImage');
            var editcroppedOriginalImagectx = editcroppedOriginalImage.getContext('2d');
            this.editcroppedOriginalImagectx = editcroppedOriginalImagectx;
            console.log(this.imgBeforeConcat[index][2])
            // let that = this;
            var coverImage = this.editNewImage(this.imgBeforeConcat[index][0]);
            var drawCanvasImage = this.editNewImage(this.imgBeforeConcat[index][1]);
            var croppedOriginalImage = this.editNewImage(this.imgBeforeConcat[index][2]);
            let that = this;
            croppedOriginalImage.onload = function(){
                editCoverctx.drawImage(coverImage,0,0,280,280);
                editDrawCanvasctx.drawImage(drawCanvasImage,0,0,280,280);
                editcroppedOriginalImagectx.drawImage(croppedOriginalImage,0,0,280,280);
                that.cropProgress = false;
            };
        },
        editNewImage(base64){
            var image = new Image;
            image.src = base64;
            return image;
        },
        deleteImage(index){
            this.concatImg.splice(index,1);
            this.imgBeforeConcat.splice(index,1);
            if(this.concatImg.length==0){
                this.e1 = 1;
                this.imgSrc = '';
                this.stepBtn1 = true;
                this.concatImageBtn = true;
                this.globalAlpha = 1;
                this.hexa = '#FF000000';
                this.model = '';
                this.filters = '';
                this.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
                this.coverctx.globalCompositeOperation = 'source-over';
                this.coverctx.filter = 'grayscale(0%)';
                this.coverctx.globalAlpha = 1;
                this.editItems = 0;
                img[0].src = '';
            }
        },
        editImageSaveStart(index){
            let that = this;
            that.cropProgress = true;
            that.currentImageDialog = false;
            that.editIndex = '';
            setTimeout(function(){
                that.editImageSave(index);
            },100)
        },
        editImageSave(index){
            var edittedImage = document.getElementById('edittedImage');
            var edittedImagecxt = edittedImage.getContext("2d");
            var editCoverImage = this.createImage(document.getElementById("editCover"));
            var editDrawCanvasImage = this.createImage(document.getElementById('editDrawCanvas'));
            var editcroppedOriginalImage = this.createImage(document.getElementById('editcroppedOriginalImage'));

            var coverSrc = document.getElementById("editCover").toDataURL('image/png');
            var drawCanvasSrc = document.getElementById("editDrawCanvas").toDataURL('image/png');
            var editcroppedOriginalImageSrc = document.getElementById("editcroppedOriginalImage").toDataURL('image/png');
            
            this.imgBeforeConcat[index] = [coverSrc,drawCanvasSrc,editcroppedOriginalImageSrc];

            editcroppedOriginalImage.onload = function(){
                edittedImagecxt.drawImage(editCoverImage,0,0,280,280);
                edittedImagecxt.drawImage(editDrawCanvasImage,0,0,280,280);
            };
            let that = this;
            setTimeout(function(){
                var edittedImageSrc = document.getElementById('edittedImage').toDataURL('image/png');
                that.concatImg[index] = edittedImageSrc;
                that.cropProgress = false;
                that.globalAlpha = 1;
                that.hexa = '#FF000000';
                that.model = '';
                that.filterObject = {'blur': 0, 'brightness': 100, 'contrast': 100, 'grayscale': 0, 'hueRotate': 0, 'invert': 0, 'saturate': 100, 'sepia': 0};
                edittedImage.globalCompositeOperation = 'source-over';
                edittedImage.filter = 'grayscale(0%)';
                edittedImage.globalAlpha = 1;
            }, 200);
        },
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
#select {
    margin: auto;
    max-width: 400px;
    width: 100%;
}
.v-stepper__content {
    padding: 0;
}
.v-color-picker__controls {
    padding: 5px;
}
.v-chip.v-size--default {
    font-size: 12px;
    height: 26px;
}
</style>
