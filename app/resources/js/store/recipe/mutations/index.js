export default {
    updateTags(state, payload) {
        state.tags = payload;
    },
    addTag(state, payload) {
        state.tags.push(payload)
    }
}
