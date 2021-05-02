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
          v-if="!$store.state.meal.assign.enabled && !$store.state.meal.movePlan.enabled && !isPast"
          variant="light"
          class="plan-recipe"
        >
          <b-icon-plus-circle @click="onAddPlan" />
        </b-button>
        <b-button
          v-else-if="!isPast"
          class="assign-recipe"
          variant="primary"
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
      centered
      hide-footer
    >
      <b-card
        no-body
        class="mb-1"
      >
        <b-card-header
          header-tag="header"
          class="p-1"
          role="tab"
        >
          <b-button
            v-b-toggle.accordion-1
            block
            variant="primary"
            @click="focusDescriptionInput"
          >
            Freitext
          </b-button>
        </b-card-header>
        <b-collapse
          id="accordion-1"
          accordion="my-accordion"
          role="tabpanel"
        >
          <b-card-body>
            <b-card-text>
              <b-form @submit.prevent="onAddPlanSubmit">
                <b-form-group label="Freitext">
                  <b-input
                    id="plan-to-add-description"
                    ref="description-input"
                    v-model="planToAdd.description"
                    placeholder="Freitext"
                  />
                </b-form-group>
                <div class="text-center">
                  <b-button
                    class="select-button"
                    @click="onAddPlanSubmit"
                  >
                    Zur Planung hinzufügen
                  </b-button>
                </div>
              </b-form>
            </b-card-text>
          </b-card-body>
        </b-collapse>
      </b-card>

      <b-card
        no-body
        class="mb-1"
      >
        <b-card-header
          header-tag="header"
          class="p-1"
          role="tab"
        >
          <b-button
            v-b-toggle.accordion-2
            block
            variant="primary"
          >
            Rezept auswählen
          </b-button>
        </b-card-header>
        <b-collapse
          id="accordion-2"
          accordion="my-accordion"
          role="tabpanel"
        >
          <b-card-body>
            <b-card-text>
              <RecipeSearch
                select-mode
                @selected="onAddRecipeSubmit"
              />
            </b-card-text>
          </b-card-body>
        </b-collapse>
      </b-card>
    </b-modal>

    <b-modal
      id="portion-selector-modal"
      ref="portion-selector-modal"
      :title="portionSelectorTitle"
      @ok="onPortionSelectorSubmit"
    >
      <PortionSelector
        v-if="planToAdd.recipe"
        v-model="planToAdd.portion"
        :recipe="planToAdd.recipe"
      />
    </b-modal>
  </div>
</template>

<script>
import DayPlan from "./DayPlan";
import RecipeSearch from "../recipe/RecipeSearch";
import PortionSelector from "./PortionSelector";

export default {
  name: "Meal",
  components: {DayPlan, RecipeSearch, PortionSelector},
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
    isPast: {
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      planToAdd: {
        description: null,
        recipe: null,
        portion: null
      }
    }
  },
  computed: {
    portionSelectorTitle() {
      return (this.planToAdd.recipe) ? "Portionen für Rezept " + this.planToAdd.recipe.title + " anpassen" : "";
    }
  },
  mounted() {
  },
  methods: {
    focusDescriptionInput() {
      setTimeout(() => {
        this.$refs['description-input'].$el.focus();
      });
    },
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
    onAddRecipeSubmit(recipe){
      this.planToAdd.recipe = recipe;
      this.planToAdd.portion = recipe.portion;
      this.$refs['assign-plan-modal'].hide();
      this.$refs['portion-selector-modal'].show();
    },
    onPortionSelectorSubmit(){
      const data = {
        meal: this.meal,
        day: this.day,
        recipe: this.planToAdd.recipe,
        portion: this.planToAdd.portion
      }
      this.$store.dispatch('meal/planRecipeForDay', data).then(res => {
        this.$store.dispatch('meal/refreshDay', {id: data.day.id})
      });
      this.clearPlanToAdd();
    },
    clearPlanToAdd() {
      this.planToAdd = {
        description: null,
        recipe: null,
        portion: null
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

  .select-button {
    border-radius: 25px;
    width: 15em;
    height: 3em;
  }
</style>
