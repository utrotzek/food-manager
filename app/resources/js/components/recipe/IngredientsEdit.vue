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
    <b-button @click="addIngredient">
      Zutat hinzufügen
    </b-button>
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
