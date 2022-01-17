import dayjs from "dayjs";
export default {
    addAccounts(state, payload) {
        if (payload.override) {
            state.accounts = [];
        }

        payload.items.forEach(item => {
            let calendars = [];
            item.calendars.forEach(calendar => {
                calendars.push({
                    id: parseInt(calendar.id),
                    title: calendar.title,
                    color: calendar.color,
                    external_id: parseInt(calendar.external_id)
                })
            });

            state.accounts.push({
                id: parseInt(item.id),
                title: item.title,
                token: item.token,
                refresh_token: item.refresh_token,
                calendars: calendars
            });
        });
    }
}
