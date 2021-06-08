export default  {
    goodsForRecipes(state) {
        const goods = state.goods.filter(el => el.allow_in_recipes === 1);
        return state.goods.sort((a,b) => {
            return a.title.localeCompare(b.title);
        })
    },
    goodsSorted(state){
        return state.goods.sort((a,b) => {
            return a.title.localeCompare(b.title);
        })
    },
    unitsSorted(state){
        return state.units.sort((a,b) => {
            return a.title.localeCompare(b.title);
        })
    }
}
