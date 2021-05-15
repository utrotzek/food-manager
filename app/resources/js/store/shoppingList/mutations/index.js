import dayjs from "dayjs";
import {DUMMY_DATE} from '../../../constants/shoppingListConstants';

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
        const shoppingListId = payload.shopping_list_id;
        const shoppingListIndex = state.shoppingLists.findIndex(el => el.id === shoppingListId);

        //delete all items with the given shopping list from the store before adding them again
        if (payload.clearExisting){
            let deleteIndex = null;
            do {
                deleteIndex = state.items.findIndex(el => {
                    return el.shopping_list_id === shoppingListId
                });
                if (deleteIndex > -1){
                    state.items.splice(deleteIndex);
                }
            }while (deleteIndex > -1)
            state.shoppingLists[shoppingListIndex].items = payload.items.length;
        }else{
            state.shoppingLists[shoppingListIndex].items += payload.items.length;
        }

        payload.items.forEach(el => {
            state.items.push({
                id: el.id,
                date: el.date !== null ? dayjs(el.date) : DUMMY_DATE,
                unit: el.unit,
                unitAmount: el.unitAmount,
                good: el.good,
                recipe_id: el.recipe_id,
                descriptionAmount: el.descriptionAmount,
                description: el.description,
                shopping_list_id: shoppingListId
            })
        })
    }
}
