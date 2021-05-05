import dayjs from "dayjs";

export default {
    addLists(state, payload){
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
        //delete all items with the given shopping list from the store before adding them again
        let deleteIndex = null;
        do {
            deleteIndex = state.items.indexOf(el => {el.shopping_list_id === payload.shopping_list_id})
            console.log(deleteIndex);
            if (deleteIndex > -1){
                console.log('deleting');
                state.items.splice(deleteIndex);
            }
        }while (deleteIndex > -1)

        payload.items.forEach(el => {
            state.items.push({
                id: el.id,
                unit: el.unit,
                unitAmount: el.unitAmount,
                good: el.good,
                recipe_id: el.recipe_id,
                description: el.desciption,
                shopping_list_id: el.shopping_list_id
            })
        })
    }
}
