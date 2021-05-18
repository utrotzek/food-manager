export default {
    storeShoppingListSorting(state, payload){
        state.shoppingList.sorting = payload.sorting;
    },
    setInitialized(state) {
        state.initialized = true;
    },
    updateBreakpoints(state, payload){
        state.breakpoints = {
            isXs: payload.isXs,
            isSm: payload.isSm,
            isMd: payload.isMd,
            isLg: payload.isLg,
            isXl: payload.isXl,
        }
    }
}
