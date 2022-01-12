<template>
  <div class="calendar">
    <h3>Kalender</h3>
    <p>Hier werden Kalender registriert und verwaltet.</p>

    <b-button
      variant="secondary"
      @click="authGoogleAccount"
    >
      <b-icon-plus-circle-fill />
      Google Account hinzufügen
    </b-button>
  </div>
</template>

<script>
export default {
  name: "Calender",
  methods: {
    authGoogleAccount() {
      axios.get('/api/calendar/auth-url').then(res => {
        window.location.href = res.data.auth_url;
      });
    }
  }
}
</script>

<style scoped>

</style>
