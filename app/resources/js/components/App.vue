<template>
    <div>
        <vue-topprogress ref="topProgress"></vue-topprogress>
        <router-view />
        <button @click="start">Start</button>
    </div>
</template>


<script>
export default {
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
