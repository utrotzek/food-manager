<template>
  <div class="day">
    <b-row>
      <b-col
        cols="12"
        md="3"
        class="d-none d-md-block"
      >
        <div class="card bg-light">
          <div class="card-header">
            Termine
          </div>
          <div class="card-body">
            <ul>
              <li>Termin 1</li>
              <li>Termin 2</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
              <li>Termin 3</li>
            </ul>
          </div>
        </div>
      </b-col>
      <b-col
        cols="12"
        md="9"
      >
        <div class="card bg-light">
          <div class="card-header">
            {{ title }}
          </div>
          <div class="card-body">
            <b-row>
              <b-col
                cols="12"
                lg="4"
              >
                <Meal title="Frühstück" />
              </b-col>
              <b-col
                cols="12"
                lg="4"
              >
                <Meal title="Kaffee" />
              </b-col>
              <b-col
                cols="12"
                lg="4"
              >
                <Meal title="Abendessen" />
              </b-col>
            </b-row>
          </div>
        </div>
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

<style scoped lang="scss">
@import '../../../sass/variables';

  .day {
    margin-bottom: 2em;
  }

  .day .header .title,
  .day .meal .inner {
    padding: 1em;
  }

  .day .meal.odd {
    background-color: green;
  }
</style>
