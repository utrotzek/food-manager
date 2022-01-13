import dayjs from "dayjs";
export default {
    authenticateGoogleAccount() {

    },
    fetchConnectedAccounts({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/account').then(res => {
                commit('addAccounts', {
                    items: res.data,
                    override: true
                });
                resolve();
            });
        });
    }
}
