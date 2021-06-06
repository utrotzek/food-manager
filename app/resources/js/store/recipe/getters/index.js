export default  {
    goodsForRecipes(state) {
        return state.goods.filter(el => el.allow_in_recipes === 1);
    }
}
