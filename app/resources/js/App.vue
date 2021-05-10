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
  async mounted() {
    const rememberedHandle = this.$store.dispatch('recipe/fetchRemembered')
    const shoppingListHandle = this.$store.dispatch('shoppingList/fetchShoppingLists');
    const appStateHandle = this.$store.dispatch('app/initializeAppState');

    await rememberedHandle;
    await shoppingListHandle;
    await appStateHandle;
    this.loading = false;
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
