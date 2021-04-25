<template>
  <div class="plan-item">
    <div>
      <div
        class="recipe-title"
        @click="onRecipeClick"
      >
        <span v-if="plan.done">
          <strike>{{ planTitle }}</strike>
        </span>
        <span v-else>
          {{ planTitle }}
        </span>
      </div>
      <b-button-group
        v-if="day.done"
        size="sm"
      >
        <b-button
          variant="light"
          @click="onDayPlanDone"
        >
          <b-icon-check-square-fill v-if="plan.done" />
          <b-icon-check-square v-else />
        </b-button>
        <b-button
          v-if="!plan.done && plan.recipe"
          variant="light"
          @click="onCookingClick"
        >
          <b-icon-egg-fried />
        </b-button>
      </b-button-group>

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
    <b-modal
      v-if="plan.recipe"
      id="recipe-details-modal"
      :ref="'recipe-details-modal'"
      size="lg"
      hide-footer
    >
      <Recipe
        :recipe-id="plan.recipe.id"
      />
    </b-modal>
  </div>
</template>

<script>
import Recipe from '../views/Recipe';

export default {
  name: "PlanItem",
  components: {Recipe},
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
  computed: {
    planTitle () {
      if (this.plan.recipe){
        return this.plan.recipe.title;
      }

      if (this.plan.description){
        return this.plan.description;
      }

      return "no plan title given"
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
    },
    onRecipeClick() {
      this.$refs['recipe-details-modal'].show();
    },
    onCookingClick(){
      this.$router.push({name: 'recipe', params: {id: this.plan.recipe.id, cooking: true}});
    }
  }
}
</script>

<style scoped>
  .recipe-title {
    cursor: pointer;
  }
  .recipe-title:hover {
    text-decoration: underline;
  }
</style>
