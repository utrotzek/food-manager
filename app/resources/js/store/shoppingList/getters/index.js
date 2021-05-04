export default  {
    allItemCount(state){
        let overallCount = 0;
        state.shoppingLists.forEach(el => {
            overallCount+= el.items
        });
        return overallCount;
    }
}
