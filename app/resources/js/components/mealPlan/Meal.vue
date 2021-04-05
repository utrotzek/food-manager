<template>
  <div class="meal">
    <b-row>
      <b-col>
        <h4 class="card-title">
          {{ title }}
        </h4>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <div
          v-for="plan in dayPlans"
          :key="plan.id"
        >
          <PlanItem
            :plan="plan"
            :done="done"
          />
          <hr>
        </div>
      </b-col>
    </b-row>
    <b-row
      v-if="!done"
      class="mt-3 mb-3"
    >
      <b-col class="text-center">
        <b-button class="plan-recipe">
          Gericht einplanen
        </b-button>
      </b-col>
    </b-row>
    <hr class="d-md-none">
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
    },
    dayPlans: {
      type: Array,
      required: true
    },
    done: {
      type: Boolean,
      default: false
    }
  },
  data(){
    return {

    }
  },
  mounted() {
  }
}
</script>

<style scoped>
  .planned-meal {
  }

  .odd {
    background-color: green;
  }

  .plan-recipe {
    border-radius: 20px;
  }
</style>
