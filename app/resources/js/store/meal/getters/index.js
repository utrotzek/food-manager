export default  {
    getDayPlansByDateAndMeal: (state) => (date, meal) => {
        return state.dayPlans.filter(el => {
            return el.date.isSame(date, 'day') && el.meal_id === meal.id
        }) ?? [];
    },
}
