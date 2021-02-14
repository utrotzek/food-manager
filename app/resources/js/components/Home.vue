<template>
    <div class="container">
        <h4>Food Manager hello world</h4>
        <button @click="login()">Login</button>
    </div>
</template>

<script>
export default {
    data () {
        return {

        };
    },
    components: {
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
