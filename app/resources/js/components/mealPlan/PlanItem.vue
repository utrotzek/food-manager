<template>
  <div class="plan-item">
    <span v-if="plan.recipe">
      {{ plan.recipe.title }}
      <b-button
        variant="link"
        class="icon-button"
      >
        <b-icon-trash />
      </b-button>
    </span>
    <span v-if="plan.description">
      {{ plan.description }}
      <b-button
        variant="link"
        class="icon-button"
      >
        <b-icon-trash />
      </b-button>
    </span>
  </div>
</template>

<script>
export default {
  name: "PlanItem",
  props: {
    plan: {
      type: Object,
      required: true
    }
  },
  data() {
    return {

    }
  },
  mounted() {

  }
}
</script>

<style scoped>

</style>
