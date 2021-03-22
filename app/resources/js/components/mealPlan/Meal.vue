<template>
  <div class="meal">
    <b-row>
      <b-col cols="12">
        {{ title }}
      </b-col>
      <b-col cols="12">
        <b-row class="planned-meal">
          <b-col cols="12">
            <PlanItem />
          </b-col>
          <b-col cols="12">
            <PlanItem />
          </b-col>
        </b-row>
      </b-col>
      <b-col cols="12">
        <b-button
          variant="link"
          class="icon-button"
        >
          <b-icon-plus-circle-fill />
        </b-button>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import PlanItem from "./PlanItem";

export default {
  name: "Meal",
  components: {PlanItem},
  props: {
    title: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
  .planned-meal {

  }
</style>
