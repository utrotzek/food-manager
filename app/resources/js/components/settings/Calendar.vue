<template>
  <div class="calendar">
    <h3>Kalender</h3>
    <p class="small">
      Die Termine der hier aktivierten Kalender, werden in der Planungsansicht angezeigt.
    </p>

    <div v-if="loaded">
      <b-row>
        <b-col
          v-for="account in accounts"
          :key="account.id"
          cols="12"
          md="6"
        >
          <b-card :title="account.title">
            <b-card-text>
              Diese Kalender werden in der Terminübersicht angezeigt:
              <b-list-group>
                <b-list-group-item
                  v-for="calendar in account.calendars"
                  :key="calendar.id"
                >
                  <b-icon-circle-fill :style="`color:${calendar.color}`" />
                  {{ calendar.title }}
                </b-list-group-item>
              </b-list-group>
            </b-card-text>

            <b-button-toolbar>
              <b-button
                class="text-left mx-1"
              >
                <b-icon-pencil />
                Bearbeiten
              </b-button>

              <b-button
                variant="danger"
                class="text-left"
              >
                <b-icon-trash />
                Löschen
              </b-button>
            </b-button-toolbar>
          </b-card>
        </b-col>
      </b-row>

      <b-row class="mt-4">
        <b-col
          cols="12"
          md="4"
          offset-md="4"
        >
          <b-button
            variant="secondary"
            block
            @click="authGoogleAccount"
          >
            <b-icon-plus-circle-fill />
            Google Account hinzufügen
          </b-button>
        </b-col>
      </b-row>
    </div>
    <div
      v-else
      class="text-center"
    >
      <b-spinner label="Loading..." />
    </div>
  </div>
</template>

<script>
export default {
  name: "Calender",
  data() {
    return {
      loaded: false
    }
  },
  computed: {
    accounts() {
      return this.$store.state.calendar.accounts;
    }
  },
  mounted() {
    this.loadData();
  },
  methods: {
    async loadData() {
      await this.$store.dispatch('calendar/fetchConnectedAccounts');
      this.loaded = true;
    },
    authGoogleAccount() {
      axios.get('/api/calendar/auth-url').then(res => {
        window.location.href = res.data.auth_url;
      });
    }
  }
}
</script>

<style scoped>

</style>
