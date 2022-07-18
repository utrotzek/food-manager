<template>
  <div class="clandar-form">
    <div
      v-if="loaded"
      class="form-wrapper"
    >
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
                      v-model="form.account.title"
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
                      v-for="calendar in form.calendars"
                      :key="calendar.id"
                      v-model="form.account.calendars"
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
    <div v-else>
      <b-spinner label="Loading..." />
    </div>
  </div>
</template>

<script>
export default {
  name: "AccountForm",
  props: ['value', 'calendars'],
  data() {
    return {
      loaded: false,
      form: {
        account: this.value,
        calendars: this.calendars ?? null
      },
    }
  },
  mounted() {
    if (this.form.account.id !== undefined && this.calendars === undefined){
      this.$store.dispatch('calendar/fetchCalendarsForAccount', {
        token: this.form.account.token,
        refresh_token: this.form.account.refresh_token
      }).then(res => {
        this.form.calendars = res;
        this.loaded = true;
      });
    }else{
      this.loaded = true;
    }
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    save() {
      this.$emit('input', this.form.account);
      this.$emit('saved');
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
