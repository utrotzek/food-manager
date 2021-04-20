<template>
  <div class="plan-item">
    <span v-if="plan.recipe">
      {{ plan.recipe.title }}
      <b-button-group size="sm">
        <b-button
          v-if="!done"
          variant="light"
        >
          <b-icon-arrows-move />
        </b-button>
        <b-button
          v-if="!done"
          variant="light"
        >
          <b-icon-trash @click="onDelete" />
        </b-button>
      </b-button-group>
    </span>
    <span v-if="plan.description">
      {{ plan.description }}
      <b-button-group size="sm">
        <b-button
          v-if="!done"
          variant="light"
        >
          <b-icon-arrows-move />
        </b-button>
        <b-button
          v-if="!done"
          variant="light"
        >
          <b-icon-trash @click="onDelete" />
        </b-button>
      </b-button-group>
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
    },
    done: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {

    }
  },
  mounted() {

  },
  methods: {
    onDelete(){
      this.$store.dispatch('meal/deletePlanItem', {dayPlanId: this.plan.id});
    }
  }
}
</script>

<style scoped>

</style>
