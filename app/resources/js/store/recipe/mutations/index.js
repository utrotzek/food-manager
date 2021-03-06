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
    },
    updateGoodGroups(state, payload) {
        state.goodGroups = payload;
    },
    addGood(state, payload){
        state.goods.push(payload);
    },
    addGoodGroup(state, payload){
        state.goodGroups.push(payload);
    }
}
