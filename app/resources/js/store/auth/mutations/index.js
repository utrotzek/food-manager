export default {
    LOGIN(state, payload) {
        state.userName = payload.username;
    },
    LOGOUT(state, payload) {
        state.userName = null;
    }
}
