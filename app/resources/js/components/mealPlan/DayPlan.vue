<template>
  <div class="plan-item">
    <div v-if="plan.recipe">
      <b-button
        v-if="day.done"
        variant="light"
        @click="onDayPlanDone"
      >
        <b-icon-check-square-fill v-if="plan.done" />
        <b-icon-check-square v-else />
      </b-button>
      <span v-if="plan.done">
        <strike>{{ plan.recipe.title }}</strike>
      </span>
      <span v-else>
        {{ plan.recipe.title }}
      </span>

      <b-button-group size="sm">
        <b-button
          v-if="!day.done && !plan.done"
          variant="light"
        >
          <b-icon-arrows-move @click="onMove" />
        </b-button>
        <b-button
          v-if="!day.done && !plan.done"
          variant="light"
        >
          <b-icon-trash @click="onDelete" />
        </b-button>
      </b-button-group>
    </div>
    <span v-if="plan.description">
      {{ plan.description }}
      <b-button-group size="sm">
        <b-button
          v-if="!day.done"
          variant="light"
        >
          <b-icon-arrows-move @click="onMove" />
        </b-button>
        <b-button
          v-if="!day.done"
          variant="light"
        >
          <b-icon-trash @click="onDelete" />
        </b-button>
      </b-button-group>
    </span>
  </div>
</template>

<script>
export default {
  name: "PlanItem",
  props: {
    plan: {
      type: Object,
      required: true
    },
    day: {
      type: Object,
      required: true
    }
  },
  data() {
    return {

    }
  },
  mounted() {

  },
  methods: {
    onDelete(){
      this.$store.dispatch('meal/deletePlanItem', {dayPlanId: this.plan.id});
    },
    onMove() {
      this.$store.commit('meal/enableRecipeMoveMode', {plan: this.plan});
    },
    onDayPlanDone() {
      let data = this.plan;
      data.done = !this.plan.done;
      this.$store.dispatch('meal/updateDayPlan', data);
    }
  }
}
</script>

<style scoped>

</style>
