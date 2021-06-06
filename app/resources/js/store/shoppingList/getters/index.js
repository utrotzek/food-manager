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
    },
    mergeableItemsOfShoppingList: (state) => (shoppingId)  => {
        const itemsInList = state.items.filter(el => el.shopping_list_id === shoppingId && el.good);
        const groupedItems = [];
        itemsInList.forEach(item => {
            let foundIndex = -1;
            foundIndex = groupedItems.findIndex(itemEl => itemEl.good.id === item.good.id);
            if (foundIndex > -1) {
                groupedItems[foundIndex].items.push(item);
            }else{
                groupedItems.push({
                    good: item.good,
                    items: [item]
                })
            }
        });
        return groupedItems.filter(el => el.items.length > 1);
    }
}
