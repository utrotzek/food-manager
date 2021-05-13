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
            const data = {
                good_id: payload.goodId,
                unit_id: payload.unitId,
                unit_amount: payload.amount,
                description: payload.description,
                shopping_list_id: payload.shoppingListId
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
