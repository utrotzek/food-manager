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
