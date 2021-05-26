import dayjs from 'dayjs';

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
        payload.forEach(el => {
            state.recipeSearchResult.push({
                id: el.id,
                title: el.title,
                image: el.image,
                rating: Number(el.rating),
                portion: Number(el.portion),
                comments: el.comments,
                favorite: Boolean(el.favorite),
                remember: Boolean(el.remember),
                tags: el.tags,
                queryTime: dayjs().unix()
            })
        });
    },
    updateRecipe(state, payload){
        const foundIndex = state.recipeSearchResult.findIndex(el => el.id === payload.id);
        if (foundIndex){
            const recipe = {
                id: payload.recipe.id,
                title: payload.recipe.title,
                image: payload.recipe.image,
                rating: Number(payload.recipe.rating),
                portion: Number(payload.recipe.portion),
                comments: payload.recipe.comments,
                favorite: Boolean(payload.recipe.favorite),
                remember: Boolean(payload.recipe.remember),
                tags: payload.recipe.tags,
                queryTime: dayjs().unix()
            };
            state.recipeSearchResult.splice(foundIndex, 1, recipe);
        }
    },
    unsetRecipes(state){
        state.recipeSearchResult = [];
    },
    removeRecipe(state, payload){
        const foundIndex = state.recipeSearchResult.findIndex(el => el.id === payload.id);
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
    },
    unsetSearchTerm(state){
        state.searchTerm = "";
    },
    setRememberedRecipes(state, payload){
        state.recipeRemembered = payload;
    }
}
