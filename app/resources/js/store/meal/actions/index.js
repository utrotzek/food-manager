export default {
    dayChangeDone({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            commit('toggleDayDone', {day: payload.day});
            resolve();
        })
    }
}
