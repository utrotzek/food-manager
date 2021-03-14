<template>
  <div class="breakpoints">
    <div
      v-b-visible="handleXs"
      class="position-fixed d-block d-sm-none"
      style="z-index: 20000; height: 1px;"
    />

    <div
      v-b-visible="handleSm"
      class="position-fixed d-none d-sm-block d-md-none"
      style="z-index: 20000; height: 1px;"
    />

    <div
      v-b-visible="handleMd"
      class="position-fixed d-none d-md-block d-lg-none"
      style="z-index: 20000; height: 1px;"
    />

    <div
      v-b-visible="handleLg"
      class="position-fixed d-none d-lg-block d-xl-none"
      style="z-index: 20000; height: 1px;"
    />

    <div
      v-b-visible="handleXl"
      class="position-fixed d-none d-xl-block"
      style="z-index: 20000; height: 1px;"
    />
  </div>
</template>

<script>
export default {
  name: "Breakpoints",
  props: {
    value: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      breakpoints: {
        isXs: this.value.isXs ?? false,
        isSm: this.value.isSm ?? false,
        isMd: this.value.isMd ?? false,
        isLg: this.value.isLg ?? false,
        isXl: this.value.isXl ?? false
      }
    }
  },
  watch: {
    breakpoints: {
      deep: true,
      handler() {
        this.$emit('input', this.breakpoints);
      }
    }
  },
  methods: {
    handleXs(isVisible) {
      this.breakpoints.isXs = isVisible
    },
    handleSm(isVisible) {
      this.breakpoints.isSm = isVisible
    },
    handleMd(isVisible) {
      this.breakpoints.isMd = isVisible
    },
    handleLg(isVisible) {
      this.breakpoints.isLg = isVisible
    },
    handleXl(isVisible) {
      this.breakpoints.isXl = isVisible
    }
  }
}
</script>

<style scoped>

</style>
