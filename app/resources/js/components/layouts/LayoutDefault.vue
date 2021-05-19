<template>
  <div class="LayoutDefault">
    <Breakpoints v-model="breakpoints" />

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
        </b-collapse>
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
                v-b-modal:modal-shopping-cart
                variant="link"
                class="icon-button"
                size="lg"
              >
                <b-icon-cart />
                <b-badge>{{ shoppingCartItems }}</b-badge>
              </b-button>
            </b-button-group>
          </b-nav-item>
        </b-navbar-nav>
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
        hide-footer
      >
        <RememberList
          big-list
          assign-disabled
        />
      </b-modal>
      <b-modal
        id="modal-shopping-cart"
        ref="modal-shopping-cart"
        size="xl"
        hide-footer
      >
        <ShoppingCart @print="onPrintShoppingList" />
      </b-modal>
    </div>
  </div>
</template>

<script>
import RememberList from "../recipe/RememberList";
import ShoppingCart from "../shoppingList/ShoppingCart";
import Breakpoints from "../tools/Breakpoints";
export default {
  name: "LayoutDefault",
  components: {RememberList, ShoppingCart, Breakpoints},
  data() {
    return {
      loading: true,
      breakpoints: {
        isXs: false,
        isSm: false,
        isMd: false,
        isLg: false,
        isXl: false,
      }
    }
  },
  computed: {
    rememberList() {
      return this.$store.state.recipe.recipeRemembered;
    },
    shoppingCartItems() {
      return this.$store.getters['shoppingList/allItemCount'];
    }
  },
  watch: {
    breakpoints: {
      deep: true,
      handler(newBreakpoints) {
        this.$store.commit('app/updateBreakpoints', newBreakpoints)
      }
    }
  },
  mounted() {
  },
  methods: {
    onPrintShoppingList(shoppingList) {
      this.$refs['modal-shopping-cart'].hide();
      this.$router.push({name: 'print-shoppling-list', params: {id: shoppingList.id}})
    }
  }
}
</script>

<style scoped>

</style>
