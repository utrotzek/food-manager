<template>
  <layout-default-dynamic>
    <div class="meal-plan">
      <h1>Essenplan</h1>
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
                @click="onPrevInterval"
              >
                <b-icon-dash-circle />
              </b-button>
              <span v-if="from && to">{{ from.format('DD.MM.') }} - {{ to.format('DD.MM.') }}</span>
              <b-button
                variant="link"
                class="icon-button"
                @click="onNextInterval"
              >
                <b-icon-plus-circle />
              </b-button>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row no-gutters>
        <b-col
          cols="12"
          md="10"
        >
          <div
            v-if="loaded"
            class="week-plan"
          >
            <b-row>
              <b-col
                v-for="day in $store.state.meal.days"
                :key="day.id"
                cols="12"
              >
                <Day
                  :title="day.date.format('dddd - DD.MM.YYYY')"
                  :day="day"
                  :meals="$store.state.meal.meals"
                />
              </b-col>
            </b-row>
          </div>
        </b-col>
        <b-col
          md="2"
          class="d-none d-md-block"
        >
          <div
            id="remember-list"
            ref="remember"
            :style="{height: 'calc(97vh - ' + rememberTop + 'px)'}"
          >
            <b-row>
              <b-col>
                <b-card>
                  <template #header>
                    <b-icon-bookmark-fill /> <b>Merkliste</b>
                  </template>
                  <b-card-body>
                    <RememberList
                      :image-height="6"
                      @assign="onAssign"
                    />
                  </b-card-body>
                </b-card>
              </b-col>
            </b-row>
          </div>
        </b-col>
      </b-row>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";
import Day from "../mealPlan/Day";
import RememberList from "../recipe/RememberList";
import { mapState } from 'vuex'

export default {
  name: "MealPlan",
  components: {RememberList, LayoutDefaultDynamic, Day},
  data() {
    return {
      loaded: false,
      recipes: null,
      mounted: false
    }
  },
  computed: {
    rememberTop() {
      if (this.$refs.remember){
        return this.$refs.remember.getBoundingClientRect().top;
      }
      return 0;
    },
    from() {
      return this.$store.state.meal.mealPlan.range.from;
    },
    to() {
      return this.$store.state.meal.mealPlan.range.to;
    }
  },
  created () {
    window.addEventListener('scroll', this.handleScroll);
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll);
  },
  async mounted() {
    this.initializeMealPlanRange();
    const rememberedHandle = this.fetchRemembered();
    const daysHandle = this.fetchMealPlan();
    const mealHandle = this.fetchMeals();

    await daysHandle;
    await rememberedHandle;
    await mealHandle;
    this.loaded = true;
  },
  methods: {
    fetchMealPlan() {
      return this.$store.dispatch('meal/loadMealPlanRange', {from: this.from, to: this.to});
    },
    fetchMeals() {
      return this.$store.dispatch('meal/loadMeals');
    },
    initializeMealPlanRange() {
      this.$store.dispatch('meal/initializeMealPlanRange');
    },
    fetchRemembered() {
      return axios.get('/api/recipes/remembered').then(res => {
        return new Promise((resolve, reject) => {
          //why slice?
          this.recipes = res.data.slice(0, 2);
          resolve();
        })
      });
    },
    onNextInterval(){
      this.$store.dispatch('meal/changeMealPlanRange', {mode: 'forward'});
      this.fetchMealPlan();
    },
    onPrevInterval(){
      this.$store.dispatch('meal/changeMealPlanRange', {mode: 'backwards'});
      this.fetchMealPlan();
    },
    onAssign(recipe){
      this.$store.commit('meal/enableRecipeAssignMode', {recipe: recipe});
    }
  }
}
</script>

<style scoped>
  #remember-list {
    margin-left: 1em;
    position: -webkit-sticky; /* Required for Safari */
    position: sticky;
    overflow-y: scroll;
    overflow-x: hidden;
    top: 2vh;
  }
</style>

<style>
  #remember-list .card-body{
    padding: 0.5em;
  }
</style>
