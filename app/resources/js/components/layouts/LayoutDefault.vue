<template>
  <div class="LayoutDefault">
    <div class="container-fluid h-100">
      <b-navbar toggleable="lg">
        <b-navbar-brand
          href="#"
          @click="$router.push({ name: 'home'})"
        >
          Food manager
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse" />

        <b-collapse
          id="nav-collapse"
          is-nav
        >
          <b-navbar-nav>
            <b-nav-item
              href="#"
              @click="$router.push({ name: 'recipes'})"
            >
              Rezepte
            </b-nav-item>
            <b-nav-item
              href="#"
              @click="$router.push({ name: 'meal-plan'})"
            >
              Essensplan
            </b-nav-item>
          </b-navbar-nav>
          <b-navbar-nav class="ml-auto">
            <b-nav-item right>
              <b-button-group>
                <b-button
                  v-b-modal:modal-remember-list
                  variant="link"
                  class="icon-button"
                  size="lg"
                  :disabled="rememberList.length === 0"
                >
                  <b-icon-bookmark />
                  <b-badge v-if="rememberList.length >= 0">
                    {{ rememberList.length }}
                  </b-badge>
                </b-button>
                <b-button
                  variant="link"
                  class="icon-button"
                  size="lg"
                >
                  <b-icon-cart />
                  <b-badge>9</b-badge>
                </b-button>
              </b-button-group>
            </b-nav-item>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>
      <main class="LayoutDefault__main h-100">
        <b-row>
          <b-col
            offset-xl="1"
            xl="10"
          >
            <slot />
          </b-col>
        </b-row>
      </main>
      <b-modal
        id="modal-remember-list"
        ref="modal-remember-list"
        centered
      >
        <RememberList />
      </b-modal>
    </div>
  </div>
</template>

<script>
import RememberList from "../recipe/RememberList";
export default {
  name: "LayoutDefault",
  components: {RememberList},
  data() {
    return {
      loading: true
    }
  },
  computed: {
    rememberList() {
      return this.$store.state.recipe.recipeRemembered;
    }
  },
  mounted() {
    this.$store.dispatch('recipe/fetchRemembered').finally(() => {
      this.loading = false;
    })
  }
}
</script>

<style scoped>

</style>
