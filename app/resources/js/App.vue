<template>
  <component :is="layout">
    <router-view
      v-if="loaded"
      :layout.sync="layout"
    />
  </component>
</template>


<script>
import LayoutDefault from "./components/layouts/LayoutDefault";

//prevent pinchzoom: https://stackoverflow.com/a/51710577
document.addEventListener('touchmove', function (event) {
  if (event.scale !== 1) { event.preventDefault(); }
}, { passive: false });

//Disable double tap zoom on document: https://community.esri.com/t5/arcgis-api-for-javascript/ios-10-user-scalable-no/td-p/382460
var lastTouchEnd = 0;
document.documentElement.addEventListener('touchend', function (event) {
  var now = (new Date()).getTime();
  if (now - lastTouchEnd <= 300) {
    event.preventDefault();
  }
  lastTouchEnd = now;
}, false);

export default {
  name: "App",
  data() {
      return {
        layout: LayoutDefault,
        loaded: false
      };
  },
  async mounted() {
    const rememberedHandle = this.$store.dispatch('recipe/fetchRemembered')
    const shoppingListHandle = this.$store.dispatch('shoppingList/fetchShoppingLists');
    const appStateHandle = this.$store.dispatch('app/initializeAppState');

    await rememberedHandle;
    await shoppingListHandle;
    await appStateHandle;
    this.loaded = true;
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
