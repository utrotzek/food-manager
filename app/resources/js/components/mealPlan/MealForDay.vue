<template>
  <div class="meal">
    <b-row>
      <b-col>
        <h4 class="card-title">
          {{ meal.title }}
        </h4>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <div
          v-for="plan in dayPlans"
          :key="plan.id"
        >
          <DayPlan
            :plan="plan"
            :day="day"
          />
          <hr>
        </div>
      </b-col>
    </b-row>
    <b-row
      v-if="!day.done"
      class="mt-3 mb-3"
    >
      <b-col class="text-center">
        <b-button
          v-if="!$store.state.meal.assign.enabled && !$store.state.meal.movePlan.enabled"
          class="plan-recipe"
        >
          Gericht einplanen
        </b-button>
        <b-button
          v-else
          @click="onAssignConfirmed"
        >
          Hier zuweisen zuweisen
        </b-button>
      </b-col>
    </b-row>
    <hr class="d-md-none">
  </div>
</template>

<script>
import DayPlan from "./DayPlan";

export default {
  name: "Meal",
  components: {DayPlan},
  props: {
    meal: {
      type: Object,
      required: true
    },
    day: {
      type: Object,
      required: true
    },
    dayPlans: {
      type: Array,
      required: true
    }
  },
  data(){
    return {

    }
  },
  mounted() {
  },
  methods: {
    onAssignConfirmed(){
      if (this.$store.state.meal.assign.enabled){
        const data = {
          meal: this.meal,
          day: this.day,
          recipe: this.$store.state.meal.assign.recipe
        };
        this.$store.dispatch('meal/planRecipeForDay', data);
      }

      if (this.$store.state.meal.movePlan.enabled){
        const data = {
          id: this.$store.state.meal.movePlan.plan.id,
          meal: this.meal,
          day: this.day,
          recipe: this.$store.state.meal.movePlan.plan.recipe,
        };
        this.$store.dispatch('meal/movePlanToDay', data);
      }
    }
  }
}
</script>

<style scoped>
  .planned-meal {
  }

  .odd {
    background-color: green;
  }

  .plan-recipe {
    border-radius: 20px;
  }
</style>
