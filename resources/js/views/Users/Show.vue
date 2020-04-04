<template>
  <div>
      <div class="w-100 h-64 overflow-hidden">
        <img src="https://images.pexels.com/photos/414171/pexels-photo-414171.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500" alt="User profile image" class="object-cover w-full">
      </div>
  </div>
</template>

<script>
export default {
    name: 'Show',

    data() {
        return {
            user: null,
            loading: true
        }
    },

    async created() {
        try {
            const res = await axios.get(`users/${this.$route.params.userId}`)
            this.user = res.data
        } catch (error) {
            console.log(`Cannot fetch user with id of ${this.$route.params.userId}!`);

        } finally { this.loading = false }

        try {
            const response = await axios.get('posts')
            this.posts = response.data
        } catch (error) {
            console.log('Unable to Fetch The Posts!');
        }finally { this.loading = false }
    }
}
</script>

<style>

</style>
