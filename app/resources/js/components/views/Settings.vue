<template>
  <layout-default-dynamic>
    <div class="settings">
      <h2>Einstellungen</h2>
      <b-row>
        <b-col cols="12">
          <b-button-group
            size="lg"
            class="flex-wrap"
          >
            <b-button
              v-for="entry in menu"
              :key="entry.route"
              variant="light"
              class="text-left"
              :to="entry.route"
            >
              <b-icon
                v-if="entry.icon"
                :icon="entry.icon"
              />
              {{ entry.title }}
            </b-button>
          </b-button-group>
        </b-col>
        <b-col
          v-if="$route.meta.settingModule != null"
          cols="12"
        >
          <router-view :key="$route.path" />
        </b-col>
        <b-col
          v-else
          cols="12"
        >
          <b-alert show>
            Bitte wählen Sie eine Kategorie.
          </b-alert>
        </b-col>
      </b-row>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";
import Calender from "../settings/Calendar";
import Goods from "../settings/Goods";

export default {
  name: "Settings",
  components: {LayoutDefaultDynamic},
  data() {
    return {
      currentEntry: null,
      menu: [
        { title: "Kalender", icon: "calendar", route: "/settings/calendar"},
        { title: "Zutaten verwalten", icon: "bag", route: "/settings/goods"}
      ]
    }
  },
  computed: {
  },
  mounted() {
  },
  methods: {
  }
}
</script>

<style scoped>
</style>
