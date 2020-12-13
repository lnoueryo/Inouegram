<template molecules>
    <div>
        <input-file-image-btn @input="selectedFile()" @click="btnclick" ref="input"></input-file-image-btn>
        <v-dialog
        v-model="show"
        hide-overlay
        persistent
        width="300"
        >
        <v-card
            color="primary"
            dark
        >
            <v-card-text>
            Please stand by
            <v-progress-linear
                indeterminate
                color="white"
                class="mb-0"
            ></v-progress-linear>
            </v-card-text>
        </v-card>
        </v-dialog>
    </div>
</template>
<script>
import InputFileImageBtn from '../atoms/InputFileImageBtn'
export default {
    props: ['size'],
    components: {
        'input-file-image-btn': InputFileImageBtn,
    },
    data(){
        return {
            show: false
        }
    },
    methods: {
        // button'next'をクリックしたときの処理
        btnclick() {
            this.$refs.input.click(); // 実際のinputと別のボタンを用意しており、そのボタンを押すとinputが動く
        },
        // fileが選択されたときの処理
        async selectedFile() {
            const file = this.$refs.input.files[0];
            if (!file.type.includes('image/')) {
                    alert('Please select an image file')
                    return
                }
            if (typeof FileReader === 'function') {
                if(this.size<480){
                    if(file.size<4100000){
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            const imgSrc = event.target.result;
                            this.$emit('selectedImage', imgSrc);
                        }
                        reader.readAsDataURL(file)
                    } else {
                        this.$emit('selectedImage', '');
                    }
                } else {
                    if(file.size<6100000){
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            const imgSrc = event.target.result;
                            this.$emit('selectedImage', imgSrc);
                        }
                        reader.readAsDataURL(file)
                    } else {
                        this.$emit('selectedImage', '');
                    }
                }
            } else {
                alert('Sorry, FileReader API not supported')
            }
            // 最初のif文はfileimageファイルかどうかのvalidation
            // 次のif文はfileのデータを読み込み映し出す処理とvuexに値を送る処理
        },
    }
}
</script>
