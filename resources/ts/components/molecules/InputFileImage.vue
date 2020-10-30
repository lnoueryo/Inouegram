<template molecules>
    <div>
        <input-file-image-btn @input="selectedFile()" @click="btnclick" ref="input"></input-file-image-btn>
    </div>
</template>
<script>
import InputFileImageBtn from '../atoms/InputFileImageBtn'
export default {
    components: {
        'input-file-image-btn': InputFileImageBtn,
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
                const reader = new FileReader();
                reader.onload = (event) => {
                    const imgSrc = event.target.result;
                    this.$emit('selectedImage', imgSrc);
                }
                reader.readAsDataURL(file)
            } else {
                alert('Sorry, FileReader API not supported')
            }
            // 最初のif文はfileimageファイルかどうかのvalidation
            // 次のif文はfileのデータを読み込み映し出す処理とvuexに値を送る処理
        },
    }
}
</script>