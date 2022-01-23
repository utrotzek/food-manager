import dayjs from "dayjs";
export default {
    saveAccount({commit}, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/account', {
                title: payload.title,
                token: payload.token,
                refresh_token: payload.refresh_token
            }).then(accountResult => {
                const accountId = accountResult.data.item.id;
                let calendarPromises = [];
                payload.calendars.forEach(cal => {
                    calendarPromises.push(
                        axios.post('/api/calendar', {
                            title: cal.title,
                            color: cal.color,
                            account_id: accountId,
                            external_id: cal.external_id
                        })
                    )
                });

                Promise.all(calendarPromises).then(() => {
                    resolve();
                }).catch(err => {
                    reject();
                })
            });
        });
    },
    deleteAccount({commit}, payload) {
        return new Promise((resolve, reject) => {
            const id = payload.id;
            axios.delete('/api/account/' + id)
                .then(res => {
                    commit('deleteAccount', {id: id})
                    resolve();
                })
                .catch(err => {
                    reject (err);
                })
        });
    },
    fetchConnectedAccounts({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/account').then(res => {
                commit('addAccounts', {
                    items: res.data,
                    override: true
                });
                resolve();
            }).catch(err => {
                reject();
            })
        });
    }
}
