<template>
  <div class="ingredients-list-edit">
    <IngredientsSingleEdit
      v-for="(item, index) in form.ingredients"
      :key="`ingredient-${index}`"
      :index="index"
      :amount="item.amount"
      :unit-id="item.unitId"
      :good-id="item.goodId"
      @changed="onChange"
    />
    <b-alert
      variant="info"
      :show="form.ingredients.length === 0"
    >
      Das Rezept enthält noch keine Zutaten. So wird das nichts.
    </b-alert>
    <div class="text-center">
      <b-button
        class="add-button"
        @click="addIngredient"
      >
        <b-icon-plus-circle /> Zutat hinzufügen
      </b-button>
    </div>
  </div>
</template>

<script>
import IngredientsSingleEdit from "./IngredientsSingleEdit";

export default {
  name: "IngredientsEdit",
  components: {IngredientsSingleEdit},
  model: {
    prop: 'ingredientList',
    event: 'changed'
  },
  props: {
    ingredientList: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      form: {
        ingredients: _.clone(this.ingredientList)
      }
    }
  },
  methods: {
    addIngredient() {
      this.form.ingredients.push({
        amount: null,
        unitId: null,
        goodId: null
      });
    },
    onChange(values){
      this.form.ingredients[values.index] = {
        amount: values.data.amount,
        unitId: values.data.unitId,
        goodId: values.data.goodId
      };
      this.$emit('changed', this.form.ingredients);
    }
  }
}
</script>

<style scoped>

</style>
