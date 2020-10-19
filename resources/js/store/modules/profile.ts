interface State {
    thisUserPosts: null | [{id: number, user_id: number, text: string, image: any}],
  }
const state = {
    thisUserPosts: '',
    // thisUserLikes: '',
    // thisUserComments: '',
    // myInfo: '',
    // commentUsers: '',
    // thisUser: '',
    // followed: '',
    // followedCount: '',
    // followingCount: '',
};
const getters = {
    // getThisUserPosts: function(state) {
    //   return state.hoge;
    // },
    getThisUserPosts(state: State){
        let thisUserPosts = state.thisUserPosts;
        if(thisUserPosts) {
            for(var i=0; i<thisUserPosts.length; i++){
                thisUserPosts[i].image = JSON.parse(thisUserPosts[i].image);
                for(var j=0; j<thisUserPosts[i].image.length; j++){
                    thisUserPosts[i].image[j].src = 'storage/image/' + thisUserPosts[i].image[j].src;
                }
            }
        }
        return thisUserPosts;
    },
}
const mutations = {
    profileInfo(state: State, profileInfo: any) {
        state.thisUserPosts = profileInfo[0];
        // state.thisUserLikes = profileInfo[1];
        // state.thisUserComments = profileInfo[2];
        // state.myInfo = profileInfo[3];
        // state.commentUsers = profileInfo[4];
        // state.thisUser = profileInfo[5];
        // state.followed = profileInfo[6];
        // state.followedCount = profileInfo[7];
        // state.followingCount = profileInfo[8];
    }
};
const actions = {
    profileInfo (context: any, profileInfo: string[]) {
        context.commit('profileInfo', profileInfo);
    }
};
export default {
    state,
    getters,
    mutations,
    actions,
}
