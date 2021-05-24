<template>
  <div class="day">
    <b-row no-gutters>
      <b-col
        cols="12"
        md="2"
        class="d-none d-md-block pr-md-2"
      >
        <div class="card bg-light">
          <div class="card-header">
            Termine
          </div>
          <div class="card-body">
            <ul class="appointment-list">
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
        md="10"
      >
        <div
          class="card bg-light"
          :class="todayClass"
        >
          <div class="card-header">
            <b-row no-gutters>
              <b-col
                cols="2"
                class="d-md-none"
              >
                <b-button
                  class="icon-button small"
                  variant="light"
                  @click="toggleVisibility"
                >
                  <b-icon-caret-right v-if="!visible" />
                  <b-icon-caret-down v-else />
                </b-button>
              </b-col>
              <b-col
                cols="6"
                md="6"
                class="title"
                @click="toggleVisibility"
              >
                {{ title }}
              </b-col>
              <b-col
                cols="4"
                md="6"
                class="text-right"
              >
                <span v-if="day.pendingCount > 0">
                  <b-button
                    variant="light"
                    disabled
                  >
                    <b-icon-cart />
                    <b-badge>{{ day.pendingCount }}</b-badge>
                  </b-button>
                </span>
                <span v-if="!day.done">
                  <b-button
                    variant="light"
                    @click="lockDay"
                  >
                    <b-icon-unlock-fill />
                  </b-button>
                </span>
                <span v-else>
                  <b-button
                    variant="light"
                    @click="unlockDay"
                  >
                    <b-icon-lock-fill />
                  </b-button>
                </span>
              </b-col>
            </b-row>
          </div>
          <b-collapse
            :id="'collapse-' + day.id"
            v-model="visible"
          >
            <div class="card-body">
              <b-row>
                <b-col
                  v-for="meal in meals"
                  :key="meal.id"
                  cols="12"
                  lg="4"
                >
                  <MealForDay
                    :meal="meal"
                    :day="day"
                    :is-past="isPast"
                    :day-plans="$store.getters['meal/getDayPlansByDayAndMeal'](day, meal)"
                  />
                </b-col>
              </b-row>
            </div>
          </b-collapse>
        </div>
      </b-col>
    </b-row>
    <b-modal
      id="shopping-list-selector-modal"
      ref="shopping-list-selector-modal"
      title="Einkauszettel auswählen"
      hide-footer
    >
      <ShoppingListSelector @save="onShoppingListSelected" />
    </b-modal>
    <b-modal
      id="recipe-to-cart-modal"
      ref="recipe-to-cart-modal"
      title="Zutaten auf den Einkauszettel übertragen"
      hide-footer
    >
      <DayPlanToShoppingList
        :day-plans="dayPlansForCart"
        :shopping-list="shoppingListForCart"
        @save="lockDayConfirm"
        @abort="hideDayToCartModal"
      />
    </b-modal>

    <b-modal
      id="shopping-list-modal"
      ref="shopping-list-modal"
      title="Neuen Einkaufszettel erstellen"
      hide-footer
    >
      <p>
        Sie müssen zuerst einen Einkaufszettel erstellen, bevor Sie Zutaten hinzufügen können.
      </p>
      <ShoppingListForm
        @abort="hideShoppingListModal"
        @save="onSaveShoppingList"
      />
    </b-modal>
  </div>
</template>

<script>

import MealForDay from "./MealForDay";
import DayPlanToShoppingList from "../shoppingList/DayPlanToShoppingList";
import ShoppingListSelector from "../shoppingList/ShoppingListSelector";
import ShoppingListForm from "../shoppingList/ShoppingListForm";

export default {
  name: "Day",
  components: {MealForDay, DayPlanToShoppingList, ShoppingListSelector, ShoppingListForm},
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
  data() {
    return {
      visible: false,
      dayPlansForCart: [],
      shoppingListForCart: null
    }
  },
  computed: {
    isVisible() {
      return !(this.$store.state.app.breakpoints.isSm || this.$store.state.app.breakpoints.isXs);
    },
    isPast() {
      return this.day.date.isBefore(this.$dayjs(), 'day');
    },
    isToday() {
      return this.day.date.isSame(this.$dayjs(), 'day');
    },
    todayClass() {
      return {
        'border-warning': this.isToday,
        'disabled': this.isPast
      }
    },
  },
  watch: {
    isVisible(newVal) {
      this.visible = newVal;
    }
  },
  mounted() {
    this.visible = this.isVisible;
  },
  methods: {
    unlockDay() {
      this.$store.dispatch('meal/daySetDone', {day: this.day, done: false});
    },
    hideDayToCartModal() {
      this.$refs['recipe-to-cart-modal'].hide();
    },
    lockDay() {
      this.dayPlansForCart = this.$store.getters['meal/getDayPlansForCart'](this.day);
      if (this.$store.state.shoppingList.shoppingLists.length === 1){
        this.shoppingListForCart = this.$store.state.shoppingList.shoppingLists[0];
        this.showAddToCartModal();
      }else if(this.$store.state.shoppingList.shoppingLists.length > 1){
        this.showShoppingListSelectorModal();
      }else{
        this.showCreateShoppingListModal();
      }
    },
    showShoppingListSelectorModal() {
      this.$refs['shopping-list-selector-modal'].show();
    },
    onSaveShoppingList() {
      this.hideShoppingListModal();
      this.lockDay();
    },
    showCreateShoppingListModal() {
      this.$refs['shopping-list-modal'].show();
    },
    hideShoppingListModal() {
      this.$refs['shopping-list-modal'].hide();
    },
    onShoppingListSelected(shoppingList) {
      this.shoppingListForCart = shoppingList;
      this.$refs['shopping-list-selector-modal'].hide();
      this.showAddToCartModal();
    },
    showAddToCartModal() {
      this.$refs['recipe-to-cart-modal'].show();
    },
    lockDayConfirm(){
      this.$refs['recipe-to-cart-modal'].hide();
    },
    toggleVisibility() {
      if (!this.isVisible) {
        this.visible=!this.visible;
      }
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

  .day .card.disabled {
    opacity: 0.4;
  }

  .appointment-list {
    padding: 0 0 0 1em;
  }

  .day .card .card-header {
    padding:0.5rem;
  }
</style>
