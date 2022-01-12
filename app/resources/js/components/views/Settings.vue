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
              :key="entry.name"
              variant="light"
              class="text-left"
              @click="currentEntry = entry.name"
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
          v-if="currentEntry != null"
          cols="12"
        >
          <component :is="currentEntry" />
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
import Calender from "../settings/Calender";
import Goods from "../settings/Goods";

export default {
  name: "Settings",
  components: {LayoutDefaultDynamic, Calender, Goods},
  data() {
    return {
      currentEntry: null,
      menu: [
        { name: "Calender", title: "Kalender", icon: "calendar"},
        { name: "Goods", title: "Zutaten verwalten", icon: "bag"}
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
