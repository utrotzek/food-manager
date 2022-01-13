<template>
  <div>
    Ich authentifiziere Calendar

    <b-button @click="authCalendar">
      Authentifizieren
    </b-button>
  </div>
</template>

<script>
export default {
  name: "Auth",

  mounted() {
    if (this.$route.query.code){
      const data = {
        'code': this.$route.query.code
      };

      axios.post('/api/calendar/auth-confirm', data)
        .then(res => {
          console.log(data);
          console.log('token successfully obtained');
        })
        .catch(err => {
          console.log('error while obtaining access token');
        })
    }else{
      console.log('code is not present');
    }
  },
  methods: {
    authCalendar(){
      axios.get('/api/calendar/auth-url').then(res => {
        window.location.href = res.data.auth_url;
      });
    }
  }

}
</script>

<style scoped>

</style>
