interface State {
  windowSize: {x: number, y: number}
  visitor: null | string[]
  requestedUser: null | string[]
}
export const header = {
    state: {
        windowSize: {
            x: 0,
            y: 0,
        },
        visitor: '',
        requestedUser: '',
    },
    getters: {
        windowSize: (state: State) => state.windowSize,
        visitor: (state: State) => state.visitor,
        requestedUser: (state: State) => state.requestedUser,
    },
    mutations: {
        windowSize(state: State, windowSize: { x: number; y: number; }){
          state.windowSize = windowSize;
        },
        visitor(state: State, visitor: null | string[]){
          state.visitor = visitor;
        },
        requestedUser(state: State, requestedUser: null | string[]){
          state.requestedUser = requestedUser;
        },
    },

  }