import dayjs from "dayjs";
import {DUMMY_DATE} from '../../../constants/shoppingListConstants';

export default {
    storeLists(state, payload){
        if (payload.clearExisting) {
            state.shoppingLists = [];
        }
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
    updateList(state, payload) {
        const index = state.shoppingLists.findIndex(el => el.id === payload.item.id);
        state.shoppingLists[index].title = payload.item.title;
    },
    deleteList(state, payload) {
        const index = state.shoppingLists.findIndex(el => el.id === payload.id);
        state.shoppingLists.splice(index, 1);
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
                    state.items.splice(deleteIndex, 1);
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
     },
    moveItem(state, payload) {
        const item = payload.item;
        const newShoppingListId = payload.shopping_list_id;
        const oldShoppingListId = payload.old_shopping_list_id;
        const newShoppingListIndex = state.shoppingLists.findIndex(el => el.id === newShoppingListId);
        const oldShoppingListIndex = state.shoppingLists.findIndex(el => el.id === oldShoppingListId);
        const itemIndex = state.items.findIndex(el => el.id === item.id);

        state.items[itemIndex].shopping_list_id = newShoppingListId;
        state.shoppingLists[newShoppingListIndex].items++;
        state.shoppingLists[oldShoppingListIndex].items--;
    },
    updateItem(state, payload){
        const shoppingListId = payload.shopping_list_id;
        const item = payload.item;
        const index = state.items.findIndex(el => el.id === item.id);

        state.items[index].id = item.id;
        state.items[index].date = item.date !== null ? dayjs(item.date) : DUMMY_DATE;
        state.items[index].unit = item.unit;
        state.items[index].unitAmount = item.unitAmount;
        state.items[index].good = item.good;
        state.items[index].recipe_id = item.recipe_id;
        state.items[index].descriptionAmount = item.descriptionAmount;
        state.items[index].description = item.description;
        state.items[index].shopping_list_id = shoppingListId;
    },
    deleteItem(state, payload) {
        const itemId = payload.id;
        const shoppingListId = payload.shoppingListId;
        const index = state.items.findIndex(el => el.id === itemId);
        const shoppingListIndex = state.shoppingLists.findIndex(el => el.id === shoppingListId);
        state.items.splice(index,1);
        state.shoppingLists[shoppingListIndex].items--;
    }
}
