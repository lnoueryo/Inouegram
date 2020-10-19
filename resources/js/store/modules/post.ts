interface State {
    imgSrc: null | string,
    cropImage: null | string[],
    message: null | string,
    listItem: {icons: string[], titles: string[]},
    btnValidation: {next1: boolean, next2: boolean, send: boolean}
    croppingBtn: {icons: [{name: string, leftValue: null, rightValue: string, left: boolean, right: boolean}], color: string[]}
  }

const state = {
    imgSrc: 'storage/image/panda.png',
    cropImage: [],
    message: '',
    btnValidation: {'next1': false, 'next2': true, 'send': true},
    submitBtn: {'color': 'pink', 'text': true, 'value': '投稿する'},
    croppingBtn: {icons: [{name: 'mdi-menu-right', leftValue: '', rightValue: 'Next', left: false, right: true}], color: ['primary']},
};

const getters = {
    cropImage: (state: State) => state.cropImage !== null ? state.cropImage[state.cropImage.length-1] : null,
    btnValidationNext1: (state: State) => state.btnValidation.next1,
    croppingBtn: (state: State) => state.croppingBtn,
}

const mutations = {
    imgSrc(state: State, imgSrc: string) {
        state.imgSrc = imgSrc;
    },
    cropImage(state: State, cropImage: string) {
        if(state.cropImage){
            state.cropImage.push(cropImage);
        }
    },
    message(state: State, message: string) {
        state.message = message;
        if(message !== ''){
            state.btnValidation.next2 = false;
        } else {
            state.btnValidation.next2 = true;
        }
    }
};

const actions = {
    imgSrc (context: any, imgSrc: string) {
        context.commit('imgSrc', imgSrc);
    },
    cropImage (context: any, cropImage: string) {
        context.commit('cropImage', cropImage);
    },
    message(context: any, message: string){
        context.commit('message', message);
    }
};

export default {
    state,
    getters,
    mutations,
    actions,
}
