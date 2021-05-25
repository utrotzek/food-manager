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
        <div
          v-if="plan.recipe"
          class="card-subtitle text-muted"
        >
          <b-icon-cart v-if="!plan.addedToCart" />
          {{ plan.portion }} Portionen
        </div>
      </div>
      <div class="card-footer plan-menu">
        <b-button-group
          v-if="day.done"
          class="button-group-full-width"
          size="sm"
        >
          <b-button
            variant="light"
            class="small"
            @click="onDayPlanDone"
          >
            <b-icon-check-square-fill v-if="plan.done" />
            <b-icon-square v-else />
          </b-button>
          <b-button
            v-if="!plan.done && plan.recipe"
            variant="light"
            class="small"
            @click="onCookingClick"
          >
            <inline-svg
              name="chef-hat"
              class="svg-icon"
              width="29px"
              height="25px"
            />
          </b-button>
        </b-button-group>

        <b-button-group
          v-else
          class="button-group-full-width"
          size="sm"
        >
          <b-button
            variant="light"
            class="small"
          >
            <b-icon-arrows-move @click="onMove" />
          </b-button>
          <b-button
            variant="light"
            class="small"
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
        :portion-override="plan.portion"
      />
    </b-modal>
  </div>
</template>

<script>
import Recipe from '../views/Recipe';

export default {
  name: "DayPlan",
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
      this.$store.dispatch('meal/deletePlanItem', {dayPlanId: this.plan.id}).then(res => {
        this.$store.dispatch('meal/refreshDay', {id: this.plan.day.id})
      });
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
      this.$router.push({name: 'recipe', params: {id: this.plan.recipe.id, cooking: true, portion: this.plan.portion}});
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

  .plan-menu {
    padding: 0 0 0.4rem 0;
  }
</style>
