const state = {
    imgSrc: '/storage/image/panda.png',
    cropImage: [],
    message: '',
    btnValidation: {'next1': false, 'next2': true, 'send': true},
    submitBtn: {'color': 'pink', 'text': true, 'value': '投稿する'},
};

const getters = {
    imgSrc: state => state.imgSrc,
    cropImg: state => state.cropImage[state.cropImage.length-1],
    btnValidation: state => state.btnValidation,
    // cropImg(state){
    //     const canvas = document.getElementById("canvas");
    //     let imagePath = state.cropImage[state.cropImage.length-1];
    //     draw(canvas,imagePath);
    //     function draw(canvas,imagePath){
    //         console.log("draw");
    //         const image = new Image();
    //         image.addEventListener("load",function (){
    //             canvas.width = image.naturalWidth;
    //             canvas.height = image.naturalHeight;
    //             const ctx = canvas.getContext("2d");
    //             ctx.drawImage(image, 0, 0);
    //             console.log("load!");
    //         });
    //         image.src = imagePath;
    //     }
    // },
}
// const canvas = document.getElementById("canvas");
// let imagePath = "image.png";
// draw(canvas,imagePath);
// function draw(canvas,imagePath){
//     console.log("draw");
//     const image = new Image();
//     image.addEventListener("load",function (){
//         canvas.width = image.naturalWidth;
//         canvas.height = image.naturalHeight;
//         const ctx = canvas.getContext("2d");
//         ctx.drawImage(image, 0, 0);
//         console.log("load!");
//     });
//     image.src = imagePath;
// }

const mutations = {
    imgSrc(state, imgSrc) {
        state.imgSrc = imgSrc;
        state.btnValidation.next1 = false;
    },
    cropImage(state, cropImage) {
        state.cropImage.push(cropImage);
    },
    message(state, message) {
        state.message = message;
        if(message !== ''){
            state.btnValidation.next2 = false;
        } else {
            state.btnValidation.next2 = true;
        }
    }
};

const actions = {
    imgSrc (context, imgSrc) {
        context.commit('imgSrc', imgSrc);
    },
    cropImage (context, cropImage) {
        context.commit('cropImage', cropImage);
    },
    message(context, message){
        context.commit('message', message);
    }
};

export default {
    state,
    getters,
    mutations,
    actions,
}
