export default  {
    getDayPlansByDateAndMeal: (state) => (date, meal) => {
        return state.dayPlans.filter(el => {
            return el.date.isSame(date, 'day') && el.meal_id === meal.id
        }) ?? [];
    },
    getDayByDate: (state) => (date) => {
        return state.days.find(el => {
            return el.date.isSame(date, 'day');
        });
    }
}
