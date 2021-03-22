<template>
  <layout-default-dynamic>
    <div class="meal-plan">
      <h1>Essenplan</h1>
      <b-row>
        <b-col
          cols="12"
          md="8"
        >
          <b-row>
            <b-col cols="12">
              <Day title="Montag" />
            </b-col>
            <b-col cols="12">
              <Day title="Dienstag" />
            </b-col>
            <b-col cols="12">
              <Day title="Mittwoch" />
            </b-col>
            <b-col cols="12">
              <Day title="Donerstag" />
            </b-col>
            <b-col cols="12">
              <Day title="Freitag" />
            </b-col>
            <b-col cols="12">
              <Day title="Samstag" />
            </b-col>
            <b-col cols="12">
              <Day title="Sonntag" />
            </b-col>
          </b-row>
        </b-col>
        <b-col
          md="4"
          class="d-none d-md-block"
        >
          <h3>Merkliste</h3>
        </b-col>
      </b-row>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";
import Day from "../mealPlan/Day";

export default {
  name: "MealPlan",
  components: {LayoutDefaultDynamic, Day}
}
</script>

<style scoped>

</style>
