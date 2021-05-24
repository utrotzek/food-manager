export default  {
    getDayPlansByDayAndMeal: (state) => (day, meal) => {
        return state.dayPlans.filter(el => {
            return el.day.id === day.id && el.meal.id === meal.id
        }) ?? [];
    },
    getDayPlansForCart: (state) => (day) => {
        return state.dayPlans.filter(el => {
            return el.day.id === day.id && !day.addedToCart
        }) ?? [];
    },
    getDayByDate: (state) => (date) => {
        return state.days.find(el => {
            return el.date.isSame(date, 'day');
        });
    }
}
