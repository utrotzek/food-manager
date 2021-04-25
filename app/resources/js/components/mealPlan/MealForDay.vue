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
          variant="light"
          class="plan-recipe"
        >
          <b-icon-plus-circle @click="onAddPlan" />
        </b-button>
        <b-button
          v-else
          class="assign-recipe"
          variant="light"
          @click="onAssignConfirmed"
        >
          <b-icon-arrow-down-circle />
        </b-button>
      </b-col>
    </b-row>
    <hr class="d-md-none">

    <b-modal
      id="assign-plan-modal"
      ref="assign-plan-modal"
      title="Planung hinzufügen"
      ok-title="Planung einfügen"
      centered
      @ok="onAddPlanSubmit"
    >
      <b-form-group label="Freitext">
        <b-input
          id="plan-to-add-description"
          v-model="planToAdd.description"
          placeholder="Freitext"
        />
      </b-form-group>
    </b-modal>
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
    },
  },
  data(){
    return {
      planToAdd: {
        description: null,
        recipe: null
      }
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
    },
    onAddPlan() {
      this.$refs['assign-plan-modal'].show();
    },
    onAddPlanSubmit(){
      if (this.planToAdd.description) {
        const data = {
          meal: this.meal,
          day: this.day,
          description: this.planToAdd.description
        }
        this.$store.dispatch('meal/planRecipeForDay', data);
      }
      this.clearPlanToAdd();
      this.$refs['assign-plan-modal'].hide();
    },
    clearPlanToAdd() {
      this.planToAdd = {
        description: null,
        recipe: null
      };
    }
  }
}
</script>

<style scoped lang="scss">
@import "resources/sass/variables";

  .planned-meal {
  }

  .odd {
    background-color: green;
  }

  .plan-recipe {
    border-radius: 20px;
    color: $gray-400;
    font-size: 1.5em;
  }

  .assign-recipe {
    border-radius: 20px;
    color: $gray-400;
    font-size: 1.5em;
  }
</style>
