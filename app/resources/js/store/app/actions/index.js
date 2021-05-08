export default {
    updateShoppingListSorting({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            axios.put('/api/app-states/ShoppingListSorting', {value: payload.sorting}).then (res => {
                commit('storeShoppingListSorting', {sorting: payload.sorting});
            })
        })
    },
    initializeAppState({commit, state}) {
        axios.get('/api/app-states').then(res => {
            res.data.forEach(el => {
                switch (el.state_name){
                    case "ShoppingListSorting":
                        commit('storeShoppingListSorting', {sorting: el.state_value});
                        break;
                }
            })
            commit('setInitialized');
        });
    }
}
