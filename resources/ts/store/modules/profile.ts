interface State {
  isMainUser: boolean,
  windowSize: {x: number, y: number}
  visitor: { id: number | null }
  requestedUser: { id: number | null, posts: string[] }
  postDialog: null | string[]
  postDialogIndex: null | number
}

export const profile = {
    state: {
        isMainUser: '',
        windowSize: {
            x: 0,
            y: 0,
        },
        visitor: '',
        requestedUser: '',
        postDialog: '',
        postDialogIndex: '',
    },
    getters: {
        isMainUser: (state: State) => (state.visitor.id == state.requestedUser.id) ? true : false,
        windowSize: (state: State) => state.windowSize,
        visitor: (state: State) => state.visitor,
        requestedUser: (state: State) => state.requestedUser,
        postDialog: (state: State) => state.postDialog,
        postDialogIndex: (state: State) => state.postDialogIndex,
    },
    mutations: {
        windowSize(state: State, windowSize: { x: number; y: number; }){
          state.windowSize = windowSize;
        },
        visitor(state: State, visitor: { id: number | null }){
          state.visitor = visitor;
        },
        requestedUser(state: State, requestedUser: { id: number | null, posts: string[] }){
          state.requestedUser = requestedUser;
        },
        postDialog(state: State, postDialog: null | string[]){
          state.postDialog = postDialog;
        },
        postDialogIndex(state: State, postDialogIndex: null | number){
          state.postDialogIndex = postDialogIndex;
        },
        posts(state: State, posts: string[]){
          state.requestedUser.posts = posts;
        },
    },

  }