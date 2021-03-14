<template>
  <div class="recipe-filter">
    <b-button
      variant="link"
      class="m-1 icon-button-default-color"
      @click="filterVisible=!filterVisible"
    >
      <b-icon-filter-circle-fill v-if="activeFilter" />
      Erweiterte Suche
      <b-icon-caret-right v-if="!filterVisible" />
      <b-icon-caret-down v-else />
    </b-button>
    <b-collapse
      id="collapse-filter"
      v-model="filterVisible"
      class="mb-4"
    >
      <b-card>
        <b-row>
          <b-col
            id="filter-controls"
            cols="12"
          >
            <b-row>
              <b-col
                cols="12"
                sm="6"
              >
                <b-row>
                  <b-col cols="6">
                    <label>Favoriten</label>
                  </b-col>
                  <b-col cols="6">
                    <toggle-button
                      id="filter-favorites"
                      v-model="filter.favorites"
                      :width="70"
                      :font-size="15"
                      :labels="{checked: 'an', unchecked: 'aus'}"
                      sync
                    />
                  </b-col>
                </b-row>
              </b-col>
              <b-col
                cols="12"
                sm="6"
              >
                <b-row>
                  <b-col cols="6">
                    <label>gemerkte</label>
                  </b-col>
                  <b-col cols="6">
                    <toggle-button
                      id="filter-remembered"
                      v-model="filter.remembered"
                      :width="70"
                      :font-size="15"
                      :labels="{checked: 'an', unchecked: 'aus'}"
                      sync
                    />
                  </b-col>
                </b-row>
              </b-col>
              <b-col
                cols="12"
                sm="6"
              >
                <b-row>
                  <b-col cols="6">
                    <label>unbewerted</label>
                  </b-col>
                  <b-col cols="6">
                    <toggle-button
                      id="filter-unrated"
                      v-model="filter.unrated"
                      :width="70"
                      :font-size="15"
                      :labels="{checked: 'an', unchecked: 'aus'}"
                      sync
                    />
                  </b-col>
                </b-row>
              </b-col>
              <b-col
                cols="12"
                sm="6"
              >
                <b-row>
                  <b-col cols="6">
                    <label>Bewertung</label>
                  </b-col>
                  <b-col
                    cols="4"
                    lg="3"
                  >
                    <b-input
                      id="filter-rating"
                      v-model="filter.rating"
                      size="sm"
                      placeholder="1-5 (>3)"
                    />
                  </b-col>
                </b-row>
              </b-col>
              <b-col
                cols="12"
                sm="6"
              >
                <b-row>
                  <b-col cols="6">
                    <label>zufällig</label>
                  </b-col>
                  <b-col cols="6">
                    <toggle-button
                      id="filter-random"
                      v-model="filter.random"
                      :width="70"
                      :font-size="15"
                      :labels="{checked: 'an', unchecked: 'aus'}"
                      sync
                    />
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
            <b-row class="mt-4">
              <b-col
                cols="12"
                sm="6"
                lg="3"
                offset-lg="2"
                class="mb-1"
              >
                <b-button
                  block
                  variant="warning"
                  @click="resetFilter"
                >
                  <b-icon-x-circle /> Filter löschen
                </b-button>
              </b-col>
              <b-col
                cols="12"
                sm="6"
                lg="3"
                offset-lg="2"
                class="mb-1"
              >
                <b-button
                  block
                  variant="secondary"
                  @click="$emit('reload')"
                >
                  <b-icon-arrow-repeat /> Neu laden
                </b-button>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
      </b-card>
    </b-collapse>
  </div>
</template>

<script>
export default {
  name: "RecipeFilter",
  props: {
    value: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      filterVisible: false,
      filter: {
        remembered: this.value.remembered,
        favorites: this.value.favorites,
        random: this.value.random,
        rating: this.value.rating,
        unrated: this.value.unrated,
      }
    }
  },
  computed: {
    activeFilter() {
      return !!(
        this.filter.remembered ||
        this.filter.favorites ||
        this.filter.rating ||
        this.filter.unrated ||
        this.filter.random
      );
    }
  },
  watch: {
    filter: {
      deep: true,
      handler() {
        this.triggerInput();
      }
    }
  },
  methods: {
    resetFilter() {
      this.filter = {
        remembered: false,
        favorites: false,
        random: false,
        rating: null,
        unrated: false,
      };
      this.triggerInput();
    },
    triggerInput(){
      this.$emit('input', this.filter);
    }
  }
}
</script>

<style scoped>

</style>
