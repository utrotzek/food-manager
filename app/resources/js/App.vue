<template>
  <component :is="layout">
    <router-view :layout.sync="layout" />
  </component>
</template>


<script>
export default {
    data() {
        return {
            layout: 'div',
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
