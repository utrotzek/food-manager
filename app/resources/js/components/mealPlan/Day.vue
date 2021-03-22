<template>
  <div class="day">
    <b-row>
      <b-col
        cols="12"
        md="2"
      >
        <b-row>
          <b-col>
            <h3>{{ title }}</h3>
          </b-col>
          <b-col>
            Termine usw.
          </b-col>
        </b-row>
      </b-col>
      <b-col
        cos="12"
        md="10"
      >
        <b-row>
          <b-col
            cols="12"
            md="4"
          >
            <Meal title="Frühstück" />
          </b-col>
          <b-col
            cols="12"
            md="4"
          >
            <Meal title="Kaffee & Kuchen" />
          </b-col>
          <b-col
            cols="12"
            md="4"
          >
            <Meal title="Abendessen" />
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </div>
</template>

<script>

import Meal from "./Meal";

export default {
  name: "Day",
  components: {Meal},
  props: {
    title: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
  .day {
    border: solid red 1px;
    padding: 1em;
    margin-bottom: 2em;
  }
</style>
