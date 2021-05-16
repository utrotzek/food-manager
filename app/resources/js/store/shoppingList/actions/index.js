export default {
    fetchShoppingLists({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/shopping-lists', {params: {active: 1}}).then(res => {
                const commitData = {
                    shoppingLists: res.data,
                    clearExisting: true
                }
                commit('storeLists', commitData)
                resolve();
            });
        })
    },
    addNewList({commit}, payload) {
        return new Promise((resolve, reject) => {
            const data = {title: payload.title};
            axios.post('/api/shopping-lists', data).then(res => {
                const commitData = {
                    shoppingLists: [res.data.item],
                    clearExisting: false
                }
                commit('storeLists', commitData)
                resolve();
            })
        });
    },
    editList({commit}, payload) {
        return new Promise((resolve, reject) => {
            const data = {title: payload.title};
            axios.put('/api/shopping-lists/' + payload.id, data).then(res => {
                const commitData = {
                    item: res.data.item
                }
                commit('updateList', commitData)
                resolve();
            })
        });
    },
    fetchItems({commit}, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/shopping-list-items', {params: {shopping_list_id: payload.shopping_list_id}}).then(res => {
                const data = {
                    shopping_list_id: payload.shopping_list_id,
                    items: res.data,
                    clearExisting: true
                }
                commit('storeItemsForList', data)
                resolve();
            })
        });
    },
    deleteItem({commit}, payload) {
        return new Promise((resolve, reject) => {
            const itemId = payload.id;
            axios.delete('/api/shopping-list-items/' + itemId).then(res => {
                commit('deleteItem', {id: itemId});
                resolve();
            });
        })
    },
    editItem({commit}, payload) {
        return new Promise((resolve, reject) => {
            let data = {};
            const itemId = payload.id;
            if (payload.ingredient){
                data = {
                    good_id: payload.ingredient.goodId,
                    unit_id: payload.ingredient.unitId,
                    unit_amount: payload.ingredient.amount,
                    shopping_list_id: payload.shoppingListId
                }
            }else if (payload.freeText){
                data = {
                    description: payload.freeText.description,
                    descriptionAmount: payload.freeText.descriptionAmount,
                    shopping_list_id: payload.shoppingListId
                }
            }else{
                reject('Wrong payload. Ingredient or freeText must be defined');
            }

            axios.put('/api/shopping-list-items/' + itemId, data).then(res => {
                const commitData = {
                    shopping_list_id: payload.shoppingListId,
                    item: res.data.item
                }
                commit('updateItem', commitData)
                resolve();
            });
        })
    },
    addItem({commit}, payload) {
        return new Promise((resolve, reject) => {
            let data = {};
            if (payload.ingredient){
                data = {
                    good_id: payload.ingredient.goodId,
                    unit_id: payload.ingredient.unitId,
                    unit_amount: payload.ingredient.amount,
                    shopping_list_id: payload.shoppingListId
                }
            }else if (payload.freeText){
                data = {
                    description: payload.freeText.description,
                    descriptionAmount: payload.freeText.descriptionAmount,
                    shopping_list_id: payload.shoppingListId
                }
            }else{
                reject('Wrong payload. Ingredient or freeText must be defined');
            }

            axios.post('/api/shopping-list-items', data).then(res => {
                const commitData = {
                    shopping_list_id: payload.shoppingListId,
                    items: [res.data.item],
                    clearExisting: false
                }
                commit('storeItemsForList', commitData)
                resolve();
            });
        })
    }
}
