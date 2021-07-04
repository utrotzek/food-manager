<template>
  <div
    class="plan-item"
    :class="{withRecipe: plan.recipe}"
  >
    <div class="card ">
      <div class="card-body">
        <h4
          class="card-title recipe-title"
          @click="onDayPlanClick"
        >
          <span
            v-if="plan.done"
          >
            <strike>{{ planTitle }}</strike>
          </span>
          <span
            v-else
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

    <b-modal
      id="edit-free-text-modal"
      ref="edit-free-text-modal"
      centered
      title="Eintrag ändern"
      hide-footer
    >
      <ValidationObserver
        ref="free-text-observer"
        v-slot="{ invalid, handleSubmit }"
      >
        <b-form @submit.stop.prevent="handleSubmit(onEditFreeTextSubmit)">
          <b-row>
            <b-col>
              <validation-provider
                v-slot="validationContext"
                name="Freitext"
                :rules="{ required: true }"
              >
                <b-form-group label="Freitext">
                  <b-input
                    ref="plan-description-input"
                    v-model="form.planNewDescription"
                    autofocus
                    :state="getValidationState(validationContext)"
                    @focus="$event.target.select()"
                  />
                  <b-form-invalid-feedback id="freetext-feedback">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row>
            <b-col class="text-right">
              <b-button-group>
                <b-button @click="onEditFreeTextAbort">
                  Abbrechen
                </b-button>
                <b-button
                  variant="primary"
                  type="submit"
                  :disabled="invalid"
                >
                  Speichern
                </b-button>
              </b-button-group>
            </b-col>
          </b-row>
        </b-form>
      </ValidationObserver>
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
      form: {
        planNewDescription: ""
      }
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
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
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
    onDayPlanClick() {
      if (this.plan.recipe) {
        this.$refs['recipe-details-modal'].show();
      }else {
        this.form.planNewDescription = this.plan.description;
        this.$refs['edit-free-text-modal'].show();
      }
    },
    onCookingClick(){
      this.$router.push({name: 'recipe', params: {id: this.plan.recipe.id, cooking: true, portion: this.plan.portion}});
    },
    onEditFreeTextAbort() {
      this.$refs['edit-free-text-modal'].hide();
      this.form.planNewDescription = "";
    },
    onEditFreeTextSubmit() {
      this.$emit('change-plan-description', this.form.planNewDescription);
      this.$refs['edit-free-text-modal'].hide();
      this.form.planNewDescription = "";
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
