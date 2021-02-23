<template>
  <ul class="steps">
    <li
      v-for="(step, i) in steps"
      :key="i"
    >
      {{ step.description }}
    </li>
  </ul>
</template>

<script>
export default {
  name: "Steps",
  props: {
    steps: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';

  .steps {
    counter-reset: step;
  }

  .steps {
    padding: 0;
  }

  .steps li::before {
    counter-increment: step;
    content: 'Schritt ' counter(step);
    display: block;
    height: 2em;
    line-height: 2em;
    width: 6em;
    white-space: pre-wrap;
    background-color: $gray-300;
    border-radius: 45%;
    text-align: center;
    font-weight: bold;
    margin-bottom: 0.5em;
  }

  .steps li {
    list-style: none;
    margin-bottom: 1.5em;
  }

</style>
