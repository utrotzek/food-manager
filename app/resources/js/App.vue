<template>
  <component :is="layout">
    <router-view :layout.sync="layout" />
  </component>
</template>


<script>
import LayoutDefault from "./components/layouts/LayoutDefault";

export default {
  name: "App",
  data() {
      return {
          layout: LayoutDefault,
      };
  },
  mounted() {
    Vue.nextTick(() => {
      this.$store.dispatch('recipe/fetchRemembered')
        .then(() => this.$store.dispatch('shoppingList/fetchShoppingLists'))
        .then(() => this.$store.dispatch('app/initializeAppState'))
        .then(() => {
          this.loading = false;
        });
    });
  },
  methods: {
      start() {
          this.$refs.topProgress.start();
          // Use setTimeout for demo
          setTimeout(() => {
              this.$refs.topProgress.done()
          }, 2000)
      }
  }
}
</script>
