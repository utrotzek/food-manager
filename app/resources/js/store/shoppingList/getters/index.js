export default  {
    allItemCount(state){
        let overallCount = 0;
        state.shoppingLists.forEach(el => {
            overallCount+= el.items
        });
        return overallCount;
    },
    itemsForShoppingList: (state) => (shoppingId) => {
        return state.items.filter(el => el.shopping_list_id === shoppingId);
    }
}
