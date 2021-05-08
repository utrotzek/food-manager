export default {
    storeShoppingListSorting(state, payload){
        state.shoppingList.sorting = payload.sorting;
    },
    setInitialized(state) {
        state.initialized = true;
    }
}
