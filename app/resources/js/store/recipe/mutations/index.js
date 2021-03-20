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
    addUnit(state, payload){
        state.units.push(payload);
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
    },
    addRecipes(state, payload){
        state.recipeSearchResult = state.recipeSearchResult.concat(payload);
    },
    updateRecipe(state, payload){
        const foundIndex = state.recipeSearchResult.findIndex(el => el.id === payload.id);
        if (foundIndex){
            state.recipeSearchResult[foundIndex] = payload.recipe;
        }
    },
    removeRecipe(state, payload){
        const foundIndex = state.recipeSearchResult.findIndex(el => el.id === payload.id);
        console.log('found index:' + foundIndex);
        state.recipeSearchResult.splice(foundIndex, 1);
    },
    incrementPageCounter(state){
        state.recipePageCounter++;
    },
    clearSearchResult(state){
        state.recipeSearchResult = [];
        state.recipePageCounter = 1;
    },
    setLoadingState(state, payload){
        state.recipeLoading = payload;
    },
    saveFilter(state, payload){
        state.searchFilter = payload;
    },
    saveSearchTerm(state, payload){
        state.searchTerm = payload;
    }
}
