<template>
  <div class="day">
    <b-row>
      <b-col
        cols="12"
        md="3"
        class="d-none d-md-block"
      >
        <div class="card bg-light">
          <div class="card-header">
            Termine
          </div>
          <div class="card-body">
            <ul>
              <li>Termin 1</li>
              <li>Termin 2</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
            </ul>
          </div>
        </div>
      </b-col>
      <b-col
        cols="12"
        md="9"
      >
        <div
          class="card bg-light"
          :class="todayClass"
        >
          <div class="card-header">
            <b-row>
              <b-col
                cols="8"
                class="title"
              >
                {{ title }}
              </b-col>
              <b-col
                cols="4"
                class="text-right"
              >
                <div v-if="!day.done">
                  <b-button
                    variant="light"
                    @click="toggleDone"
                  >
                    <b-icon-check-circle />
                  </b-button>
                </div>
                <div v-else>
                  <b-button
                    variant="light"
                    @click="toggleDone"
                  >
                    <b-icon-check-circle-fill />
                  </b-button>
                </div>
              </b-col>
            </b-row>
          </div>
          <div class="card-body">
            <b-row>
              <b-col
                v-for="meal in meals"
                :key="meal.id"
                cols="12"
                lg="4"
              >
                <Meal
                  :meal="meal"
                  :day="day"
                  :day-plans="$store.getters['meal/getDayPlansByDateAndMeal'](day.date, meal)"
                  :done="day.done"
                />
              </b-col>
            </b-row>
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>

import Meal from "./Meal";

export default {
  name: "Day",
  components: {Meal},
  props: {
    title: {
      type: String,
      required: true
    },
    day: {
      type: Object,
      required: true
    },
    meals: {
      type: Array,
      required: true
    },
    assignMode: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    todayClass() {
      const isToday = this.day.date.isSame(this.$dayjs(), 'day');

      return {
        'border-warning': isToday
      }
    },
  },
  methods: {
    toggleDone(){
      this.$store.dispatch('meal/dayChangeDone', {day: this.day});
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/variables';

  .day {
    margin-bottom: 2em;
  }

  .day .header .title,
  .day .meal .inner {
    padding: 1em;
  }

  .day .meal.odd {
    background-color: green;
  }

  .title {
    line-height: 2em;
  }
</style>
