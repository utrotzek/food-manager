<template>
  <div
    class="ingredients"
    :class="{checklist: enableChecklist}"
  >
    <b-row v-if="enableChecklist">
      <b-col class="text-right">
        <b-button
          variant="link"
          @click="allChecked=!allChecked"
        >
          <span v-if="!allChecked">Alle anhaken</span>
          <span v-else>Alle abhaken</span>
        </b-button>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <div
          v-for="ingredient in uncategorized"
          :key="ingredient.id"
          class="ingredient"
        >
          <Ingredient
            :enable-checklist="enableChecklist"
            :ingredient="ingredient"
            :all-checked="allChecked"
          />
        </div>


        <div
          v-for="category in categories"
          :key="category.id"
        >
          <h4>{{ category.title }}</h4>
          <div
            v-for="ingredient in byCategoriy(category.id)"
            :key="ingredient.id"
            class="ingredient"
          >
            <Ingredient
              :enable-checklist="enableChecklist"
              :ingredient="ingredient"
              :all-checked="allChecked"
            />
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Ingredient from "./Ingredient";

export default {
  name: "Ingredients",
  components: {Ingredient},
  props: {
    categories: {
      type: Array,
      default: null
    },
    ingredients: {
      type: Array,
      required: true
    },
    enableChecklist: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      allChecked: false
    }
  },
  computed: {
    checkVariant() {
      return (this.checked ? 'success' : 'info');
    },
    uncategorized(){
      return this.ingredients.filter(el => { return el.category === null; });
    }
  },
  methods: {
    toggleChecked() {
      this.checked = !this.checked;
    },
    byCategoriy(categoryId){
      return this.ingredients.filter(el => { return el.category === categoryId; });
    }
  }
}
</script>

<style scoped>
</style>
