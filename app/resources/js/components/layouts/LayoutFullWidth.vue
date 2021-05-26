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
        </b-collapse>
      </b-navbar>
      <main class="LayoutDefault__main h-100">
        <b-row>
          <b-col>
            <slot />
          </b-col>
        </b-row>
      </main>
    </div>
  </div>
</template>

<script>
export default {
name: "LayoutDefault"
}
</script>

<style scoped>

</style>
