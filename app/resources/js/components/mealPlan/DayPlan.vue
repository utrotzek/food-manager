<template>
  <div
    class="plan-item"
    :class="{withRecipe: plan.recipe}"
  >
    <div class="card ">
      <div class="card-body">
        <h4 class="card-title recipe-title">
          <span
            v-if="plan.done"
            @click="onShowRecipeModal"
          >
            <strike>{{ planTitle }}</strike>
          </span>
          <span
            v-else
            @click="onShowRecipeModal"
          >
            {{ planTitle }}
          </span>
        </h4>
        <div class="card-subtitle text-muted">
          <b-icon-cart v-if="plan.recipe && !plan.addedToCart" />
          {{ plan.portion }} Portionen
        </div>
      </div>
      <div class="card-footer">
        <b-button-group
          v-if="day.done"
          class="button-group-full-width"
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
            <b-icon-basket3 />
          </b-button>
        </b-button-group>

        <b-button-group
          v-else
          class="button-group-full-width"
          size="sm"
        >
          <b-button
            variant="light"
          >
            <b-icon-arrows-move @click="onMove" />
          </b-button>
          <b-button
            variant="light"
          >
            <b-icon-trash @click="onDelete" />
          </b-button>
        </b-button-group>
      </div>
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
    onShowRecipeModal() {
      this.$refs['recipe-details-modal'].show();
    },
    onCookingClick(){
      this.$router.push({name: 'recipe', params: {id: this.plan.recipe.id, cooking: true}});
    }
  }
}
</script>

<style scoped lang="scss">
  @import "../../../sass/variables";

  .recipe-title {
    cursor: pointer;
  }
  .recipe-title:hover {
    text-decoration: underline;
  }

  .card-title.recipe-title {
    font-size:1.2em;
  }

  .plan-item .card {
    height: 9em;
  }

  .plan-item .card .card-footer,
  .plan-item .card .card-footer .btn {
    border: 0;
    background-color: transparent;
  }

  .withRecipe .card {
    /* #d6f1d4 */
    background-color: $recipe-light;
  }

  .card {
    background-color: $recipe-warning;
    color: $black;
  }
</style>
