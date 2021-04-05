export default {
    toggleDayDone(state, payload){
        let day = state.days.find(el => {return el.date.isSame(payload.day.date, 'day')})
        day.done = !day.done;
    }
}
