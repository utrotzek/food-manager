<template>
  <div class="ingredients">
    <div
      v-for="ingredient in ingredients"
      :key="ingredient.id"
    >
      {{ ingredient.unit_amount }} {{ ingredient.unit.title }} {{ ingredient.good.title }}
    </div>
  </div>
</template>

<script>
export default {
  name: "Ingredients",
  props: {
    ingredients: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>

</style>
