export default  {
    allItemCount(state){
        let overallCount = 0;
        state.shoppingLists.forEach(el => {
            overallCount+= el.items
        });
        return overallCount;
    },
    itemsForShoppingList: (state) => (shoppingId) => {
        if (state.items.hasOwnProperty(shoppingId)){
            return state.items[shoppingId];
        }else{
            return [];
        }
    }
}
