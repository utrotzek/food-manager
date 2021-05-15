export default {
    fetchShoppingLists({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/shopping-lists', {params: {active: 1}}).then(res => {
                commit('storeLists', {shoppingLists: res.data})
                resolve();
            });
        })
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
    addItem({commit}, payload){
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
