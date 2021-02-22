<template>
  <div class="LayoutDefault">
    <h1>Food manager</h1>
    <nav class="LayoutDefault__nav">
      <router-link to="/">
        Home
      </router-link> |
      <router-link to="/recipes">
        Rezepte
      </router-link>
    </nav>
    <main class="LayoutDefault__main">
      <slot />
    </main>
  </div>
</template>

<script>
export default {
name: "LayoutDefault"
}
</script>

<style scoped>

</style>
