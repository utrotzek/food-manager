export default {
    fetchShoppingLists({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/shopping-lists', {params: {active: 1}}).then(res => {
                commit('addLists', {shoppingLists: res.data})
                resolve();
            });
        })
    }
}
