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
      <validation-observer
        ref="observer"
        v-slot="{ handleSubmit, invalid }"
      >
        <b-row>
          <b-col md="8">
            <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
              <b-form-group
                id="input-group-title"
                label="Title:"
                label-for="input-title"
              >
                <validation-provider
                  v-slot="validationContext"
                  ref="title"
                  name="Titel"
                  :rules="{ required: true, min: 3, max: 40 }"
                >
                  <b-form-group
                    id="title-group"
                    label="Titel"
                    label-for="title"
                  >
                    <b-form-input
                      id="input-title"
                      v-model="form.title"
                      placeholder="Bitte gib Deinen Account Titel ein"
                      :state="getValidationState(validationContext)"
                      autofocus
                    />

                    <b-form-invalid-feedback id="title-feedback">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-form-group>

              <b-form-group
                id="input-group-calendars"
                v-slot="{ ariaDescribedby }"
                label="Kalender"
                description="Diese Kalender werden in der Planungsübersicht angezeigt"
              >
                <validation-provider
                  v-slot="{valid, dirty}"
                  ref="calendar"
                  name="Kalendar"
                  :rules="{ required: true}"
                >
                  <b-form-checkbox-group
                    id="input-calendars"
                    :aria-describedby="ariaDescribedby"
                    stacked
                  >
                    <b-form-checkbox
                      v-for="calendar in calendars"
                      :key="calendar.id"
                      v-model="form.calendars"
                      :value="calendar.id"
                    >
                      <b-icon-circle-fill :style="`color:${calendar.color}`" />
                      {{ calendar.title }}
                    </b-form-checkbox>
                    <b-form-invalid-feedback
                      id="calendar-feedback"
                      :force-show="dirty && !valid"
                    >
                      Es muss mindestens ein Kalender ausgewählt werden.
                    </b-form-invalid-feedback>
                  </b-form-checkbox-group>
                </validation-provider>
              </b-form-group>
              <b-button
                type="submit"
                variant="primary"
                :disabled="invalid"
              >
                Speichern
              </b-button>
              <b-button
                type="reset"
                variant="secondary"
              >
                Abbrechen
              </b-button>
            </b-form>
          </b-col>
        </b-row>
      </validation-observer>
    </div>
  </div>
</template>

<script>
export default {
  name: "Auth",

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
      form: {
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
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
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
    },
    getSelectedCalendars() {
      let calendars = [];
      this.form.calendars.forEach(cal => {
        const foundCalendar = this.calendars.find(x => x.id === cal);

        calendars.push({
          external_id: foundCalendar.id,
          title: foundCalendar.title,
          color: foundCalendar.color
        });
      });
      return calendars;
    }
  }

}
</script>

<style scoped>

</style>
