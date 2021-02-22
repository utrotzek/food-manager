<template>
  <layout-default-dynamic>
    <div class="recipes">
      <h2>Rezepte</h2>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from './layouts/LayoutDefaultDynamic.js';
export default {
    name: 'Recipes',
    components: {LayoutDefaultDynamic},
    data () {
        return {
        };
    },
    mounted() {
    },
    methods: {
        login() {
            this.$store.dispatch('auth/LOGIN', {userName: "Testuser"})
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '../../sass/_variables.scss';
</style>
