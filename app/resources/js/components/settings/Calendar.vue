<template>
  <div class="calendar">
    <div v-if="$route.meta.settingSubModule != null">
      <router-view :key="$route.path" />
    </div>
    <div v-else-if="loaded">
      <h3>Kalender</h3>
      <p class="small">
        Die Termine der hier aktivierten Kalender, werden in der Planungsansicht angezeigt.
      </p>
      <b-row>
        <b-col
          cols="12"
          md="6"
        >
          <b-row>
            <b-col
              v-for="account in accounts"
              :key="account.id"
              cols="12"
              class="mb-3"
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
                    variant="light"
                  >
                    <b-icon-pencil />
                  </b-button>

                  <b-button
                    variant="light"
                    class="text-left"
                    @click="deleteAccountPrompt(account)"
                  >
                    <b-icon-trash />
                  </b-button>
                </b-button-toolbar>
              </b-card>
            </b-col>
          </b-row>
          <b-row class="mt-4">
            <b-col
              cols="12"
              md="6"
              offset-md="3"
            >
              <b-button
                variant="secondary"
                block
                @click="authGoogleAccount"
              >
                <b-icon-plus-circle-fill />
                Account hinzufügen
              </b-button>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </div>
    <div
      v-else
      class="text-center"
    >
      <b-spinner label="Loading..." />
    </div>

    <!-- modals -->
    <b-modal
      id="delete-account"
      ref="delete-account"
      title="Account löschen?"
      ok-title="Löschen"
      ok-variant="danger"
      centered
      @ok="deleteAccount"
    >
      <p v-if="accountToDelete !== null">
        Wollen Sie den Account <b>{{ accountToDelete.title }}</b> wirklich löschen?
      </p>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: "Calender",
  data() {
    return {
      loaded: false,
      accountToDelete: null
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
      axios.get('/api/google-api/auth-url').then(res => {
        window.location.href = res.data.auth_url;
      });
    },
    deleteAccountPrompt(account) {
      this.accountToDelete = account;
      this.$bvModal.show('delete-account');
    },
    deleteAccount() {
      this.$store.dispatch('calendar/deleteAccount', {id: this.accountToDelete.id });
    }
  }
}
</script>

<style scoped>

</style>
