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
      <b-row no-gutters>
        <b-col
          cols="12"
          md="9"
        >
          <div
            v-if="loaded"
            class="week-plan"
          >
            <b-row>
              <b-col cols="12">
                <Day
                  title="Montag"
                  :recipes="recipes"
                />
              </b-col>
              <b-col cols="12">
                <Day
                  title="Dienstag"
                  :recipes="recipes"
                />
              </b-col>
              <b-col cols="12">
                <Day
                  title="Mittwoch"
                  :recipes="recipes"
                />
              </b-col>
              <b-col cols="12">
                <Day
                  title="Donerstag"
                  :recipes="recipes"
                />
              </b-col>
              <b-col cols="12">
                <Day
                  title="Freitag"
                  :recipes="recipes"
                />
              </b-col>
              <b-col cols="12">
                <Day
                  title="Samstag"
                  :recipes="recipes"
                />
              </b-col>
              <b-col cols="12">
                <Day
                  title="Sonntag"
                  :recipes="recipes"
                />
              </b-col>
            </b-row>
          </div>
        </b-col>
        <b-col
          md="3"
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
                    <RememberList />
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

export default {
  name: "MealPlan",
  components: {RememberList, LayoutDefaultDynamic, Day},
  data() {
    return {
      loaded: false,
      recipes: null,
      mounted: false,
      rememberTop: 0
    }
  },
  computed: {
  },
  created () {
    window.addEventListener('scroll', this.handleScroll);
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll);
  },
  mounted() {
    this.rememberTop = this.$refs.remember.getBoundingClientRect().top;
    axios.get('/api/recipes/remembered').then(res => {
      this.recipes = res.data.slice(0, 2);
      this.loaded = true;
    });
  },
  methods: {
    handleScroll(){
      this.rememberTop = this.$refs.remember.getBoundingClientRect().top;
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
