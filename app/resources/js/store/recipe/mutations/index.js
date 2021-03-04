export default {
    updateTags(state, payload) {
        state.tags = payload;
    },
    addTag(state, payload) {
        state.tags.push(payload)
    },
    updateUnits(state, payload) {
        state.units = payload;
    },
    updateGoods(state, payload) {
        state.goods = payload;
    }
}
