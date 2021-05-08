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
                commit('storeItemsForList', {shopping_list_id: payload.shopping_list_id, items: res.data})
                resolve();
            })
        });
    }
}
