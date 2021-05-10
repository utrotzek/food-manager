import dayjs from "dayjs";

export default {
    storeLists(state, payload){
        state.shoppingLists = [];
        payload.shoppingLists.forEach(el => {
            state.shoppingLists.push({
                id: el.id,
                done: Boolean(el.done),
                created: dayjs(el.created),
                items: Number(el.items),
                title: el.title
            })
        })
    },
    storeItemsForList(state, payload) {
        let newItems = []
        const shoppingListId = payload.shopping_list_id;
        payload.items.forEach(el => {
            newItems.push({
                id: el.id,
                unit: el.unit,
                unitAmount: el.unitAmount,
                good: el.good,
                recipe_id: el.recipe_id,
                description: el.desciption,
                shopping_list_id: el.shopping_list_id,
                date: dayjs(el.date)
            })
        })
        state.items[shoppingListId] = newItems;
    }
}
