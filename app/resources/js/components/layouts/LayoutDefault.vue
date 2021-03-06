<template>
  <div class="LayoutDefault h-100">
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
              @click="$router.push({ name: 'plan'})"
            >
              Essensplan
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
