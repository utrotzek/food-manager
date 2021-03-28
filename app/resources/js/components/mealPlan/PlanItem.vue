<template>
  <div class="plan-item">
    <span>
      {{ recipe.title }} <b-button
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
    recipe: {
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
