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
        <div>
          <PlanItem :recipe="recipe" />
        </div>
        <div>
          <PlanItem :recipe="recipe" />
        </div>
      </b-col>
    </b-row>
    <b-row class="mt-3">
      <b-col>
        <b-button class="plan-recipe">
          Gericht einplanen
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
  },
  data(){
    return {
      recipe: null
    }
  },
  mounted() {
    axios.get('/api/recipes/1').then(res => {
      this.recipe = res.data;
    });
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
