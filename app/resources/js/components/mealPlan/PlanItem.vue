<template>
  <div class="plan-item">
    <h5>{{ recipe.title }}</h5>
  </div>
</template>

<script>
export default {
  name: "PlanItem",
  props: {
    recipe: {
      type: Object,
      required: true
    }
  },
  data() {
  },
  mounted() {

  }
}
</script>

<style scoped>

</style>
