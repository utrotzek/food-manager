<template>
  <div
    class="ingredient"
    :class="{checklist: enableChecklist}"
  >
    <b-button
      v-if="enableChecklist"
      size="xs"
      :variant="checkVariant"
      @click="toggleChecked"
    >
      <b-icon-check />
    </b-button>
    <b>{{ amount }} {{ ingredient.unit.title }}</b> {{ ingredient.good.title }}
  </div>
</template>

<script>
export default {
  name: "Ingredient",
  props: {
    ingredient: {
      type: Object,
      required: true
    },
    enableChecklist: {
      type: Boolean,
      default: false
    },
    allChecked: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      checked: false
    }
  },
  computed: {
    checkVariant() {
      return (this.checked ? 'success' : 'secondary');
    },
    amount() {
      return this.ingredient.unit_amount.toString().replace('.', ',');
    }
  },
  watch: {
    allChecked(newValue) {
      this.checked = newValue;
    }
  },
  methods: {
    toggleChecked() {
      this.checked = !this.checked;
    }
  }
}
</script>

<style scoped>
  .ingredient  {
    margin-bottom: 1em;
  }

</style>
