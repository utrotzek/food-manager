<template>
  <layout-default-dynamic>
    <div class="meal-plan">
      <h1>
        Essenplan <b-button
          variant="link"
          class="icon-button"
          size="lg"
        >
          <b-icon-unlock-fill />
        </b-button>
      </h1>
      <b-row class="mb-3">
        <b-col
          class="text-center"
          cols="12"
          md="9"
        >
          <b-row>
            <b-col
              cols="12"
              md="9"
              offset-md="3"
            >
              <b-button
                variant="link"
                class="icon-button"
              >
                <b-icon-dash-circle />
              </b-button>
              <span>22.03. - 27.03</span>
              <b-button
                variant="link"
                class="icon-button"
              >
                <b-icon-plus-circle />
              </b-button>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row>
        <b-col
          cols="12"
          md="9"
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
          md="3"
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
