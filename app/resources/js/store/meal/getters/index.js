export default  {
    getDayPlansByDayAndMeal: (state) => (day, meal) => {
        return state.dayPlans.filter(el => {
            return el.day.id === day.id && el.meal.id === meal.id
        }) ?? [];
    },
    getDayByDate: (state) => (date) => {
        return state.days.find(el => {
            return el.date.isSame(date, 'day');
        });
    }
}
