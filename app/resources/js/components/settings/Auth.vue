<template>
  <div>
    <b-alert
      :show="errorMessage != null"
      variant="danger"
    >
      {{ errorMessage }}
      <router-link :to="{name: 'settings-calendar'}">
        Zurück zur Kalenderübersicht
      </router-link>
    </b-alert>

    <div v-if="token != null">
      <AccountForm
        v-model="account"
        :calendars="calendars"
      />
    </div>
  </div>
</template>

<script>
import AccountForm from "./AccountForm";

export default {
  name: "Auth",
  components: {AccountForm},
  data() {
    return {
      token: null,
      refresh_token: null,
      calendars: [
        {
          id: "test1",
          title: "calendar1",
          color: "#e2e2e2"
        },
        {
          id: "test2",
          title: "calendar2",
          color: "#e2e2e2"
        }
      ],
      errorMessage: null,
      account: {
        title: "",
        calendars: []
      }
    }
  },
  mounted() {
    if (this.$route.query.code){
      const data = {
        'code': this.$route.query.code
      };

      axios.post('/api/google-api/auth-confirm', data)
        .then(res => {
          this.token = res.data.token;
          this.refresh_token = res.data.refresh_token;
          this.calendars = res.data.calendars;
        })
        .catch(err => {
          this.errorMessage = "Kalender Daten konten nicht abgefragt werden."
        })
    }else{
      this.$router.push({name: 'settings-calendar'});
    }
  },
  methods: {
    async onSubmit() {
      const valid = await this.$refs.observer.validate();
      if (valid) {
        const calendars = this.getSelectedCalendars();

        this.$store.dispatch('calendar/saveAccount', {
          calendars: calendars,
          title: this.form.title,
          token: this.token,
          refresh_token: this.refresh_token
        }).then(res => {
          this.$router.push({name: 'settings-calendar'});
        }).catch(err => {
          this.errorMessage = "Der Account konnte nicht hinzugefügt werden."
        });
      }
    }
  }

}
</script>

<style scoped>

</style>
