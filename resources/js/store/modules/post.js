const state = {
    imgSrc: 'storage/image/panda.png',
    cropImage: [],
    message: '',
    btnValidation: {'next1': false, 'next2': true, 'send': true},
    submitBtn: {'color': 'pink', 'text': true, 'value': '投稿する'},
};

const getters = {
    cropImg: state => state.cropImage[state.cropImage.length-1],
    btnValidation: state => state.btnValidation,

}

const mutations = {
    imgSrc(state, imgSrc) {
        state.imgSrc = imgSrc;
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
