<template>
  <div class="recipe-to-cart">
    <div
      v-if="loaded"
      class="outer-wrapper"
    >
      <b-row>
        <b-col>
          <h4 v-if="dayPlans.length > 1">
            {{ currentRecipe.title }} ({{ currentIndex + 1 }} von {{ dayPlans.length }})
          </h4>
          <h4 v-else>
            {{ currentRecipe.title }}
          </h4>
          <h5>{{ currentRecipe.ingredients.length }} Zutaten für {{ currentDayPlan.portion }} Portionen</h5>
          <p>
            Folgende Zutaten werden auf dem Einkaufszettel hinzugefügt. Du kannst einzelne Zutaten, die z.B. noch im Haus vorhanden sind, löschen. Diese werden dann auf den Einkaufszettel übertragen.
          </p>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <IngredientsEdit
            :key="currentIndex"
            v-model="currentIngredients"
            :categories="currentRecipe.ingredientCategories"
            :portion-original="currentRecipe.portion"
            :portion-override="currentDayPlan.portion"
            create-disabled
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col class="text-right">
          <b-button-group>
            <b-button
              class="mr-2"
              variant="secondary"
              @click="onAbort"
            >
              Abbrechen
            </b-button>
            <b-button
              class="mr-2"
              variant="warning"
              @click="onSkip"
            >
              <b-icon-trash-fill /> Nicht hinzufügen
            </b-button>
            <b-button
              variant="primary"
              @click="onSave"
            >
              <b-icon-cart-plus /> Hinzufügen
            </b-button>
          </b-button-group>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import IngredientsEdit from "../recipe/IngredientsEdit";

export default {
  name: "RecipeToCart",
  components: {
    IngredientsEdit
  },
  props: {
    shoppingList: {
      type: Object,
      required: true
    },
    dayPlans: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentDayPlan: null,
      currentRecipe: null,
      currentIndex: 0,
      loaded: false,
      currentIngredients: []
    }
  },
  computed: {
    isLast() {
      return this.currentIndex === (this.dayPlans.length -1);
    }
  },
  mounted() {
    this.loadIngredientsForPlanIndex(this.currentIndex);
  },
  methods: {
    async loadIngredientsForPlanIndex(index) {
      this.loaded = false;
      this.currentDayPlan = this.dayPlans[index];

      const recipePromise = axios.get('/api/recipes/'+ this.currentDayPlan.recipe.id);
      const ingredientPromise = this.$store.dispatch('recipe/fetchIngredientItems');

      const recipeResp = await recipePromise;
      await ingredientPromise;

      this.currentRecipe = recipeResp.data;

      const ingredientsCopy = JSON.parse(JSON.stringify(this.currentRecipe.ingredients));

      this.currentIngredients = [];
      ingredientsCopy.forEach(item => {
        this.currentIngredients.push({
          id: item.id,
          amount: item.unit_amount.toString().replace('.', ','),
          unitId: item.unit.id,
          goodId: item.good.id,
          category: item.category,

        });
      });
      this.loaded = true;
    },
    onSave() {
      const data = {
        ingredients: this.currentIngredients,
        shoppingListId: this.shoppingList.id,
        dayPlanId: this.currentDayPlan.id
      }
      this.$store.dispatch('shoppingList/addMultipleItemsForDay', data)
        .then(() => this.$store.dispatch('meal/dayPlanAddedToCart', {dayPlanId: this.currentDayPlan.id}))
        .then(() => this.saveAndLoadNext());
    },
    onAbort() {
      this.$emit('abort');
    },
    onDeleted(id) {
      const ingredientIndex = this.currentRecipe.ingredients.findIndex(el => el.id === id);
      this.currentRecipe.ingredients.splice(ingredientIndex, 1);
    },
    onSkip(){
      this.$store.dispatch('meal/dayPlanAddedToCart', {dayPlanId: this.currentDayPlan.id})
        .then(() => this.saveAndLoadNext());
    },
    saveAndLoadNext() {
      if (this.isLast){
        this.$emit('save');
      }else{
        this.currentIndex++;
        this.loadIngredientsForPlanIndex(this.currentIndex);
      }
    }
  }
}
</script>

<style scoped>

</style>
