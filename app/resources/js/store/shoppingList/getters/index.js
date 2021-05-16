export default  {
    allItemCount(state){
        let overallCount = 0;
        state.shoppingLists.forEach(el => {
            overallCount+= el.items
        });
        return overallCount;
    },
    shoppingListForId: (state) => (listId) => {
        return state.shoppingLists.find(el => el.id === listId);
    },
    itemsForShoppingList: (state) => (shoppingId) => {
        return state.items.filter(el => el.shopping_list_id === shoppingId);
    }
}
