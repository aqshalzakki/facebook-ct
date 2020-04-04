<template>
  <div class="flex flex-col items-center">
      <div class="relative mb-8">
          <div class="w-200 h-64 overflow-hidden z-10">
            <img src="https://i0.wp.com/www.saveseva.com/wp-content/uploads/2015/06/Landscape.jpg?fit=640%2C324" alt="User post image" class="object-cover w-full">
          </div>

          <div class="absolute flex items-center bottom-0 left-0 -mb-8 ml-12 z-20">
              <div class="w-32">
                  <img src="https://cdn.pixabay.com/photo/2014/07/09/10/04/man-388104_960_720.jpg" alt="User profile image" class="object-cover w-32 h-32 border-4 border-gray-200 rounded-full shadow-lg">
              </div>              

              <p class="text-2xl text-gray-100 ml-4">{{ user.data.attributes.name }}</p>
          </div>
      </div>

      <p v-if="postsLoading">Loading posts...</p>
      <Post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"/>

      <p v-if=" !postsLoading && !hasPosts() ">
          Oops :( You've got no posts. Get started...
      </p>
  </div>
</template>

<script>
import Post from '../../components/Post' 
export default {
    name: 'Show',
    components: { Post },
    data() {
        return {
            user: null,
            posts: null,
            userLoading: true,
            postsLoading: true,
        }
    },

    async created() {
        try {
            const res = await axios.get(`users/${this.$route.params.userId}`)
            this.user = res.data
        } catch (error) {
            console.log(`Cannot fetch user with id of ${this.$route.params.userId}!`);
 
        } finally { this.userLoading = false }

        try {
            const response = await axios.get(`users/${this.$route.params.userId}/posts`)
            this.posts = response.data
        } catch (error) {
            console.log('Unable to Fetch The Posts!');
        } finally { this.postsLoading = false } 
    },

    methods: {

        hasPosts() {
            return this.posts.data.length > 0
        }

    }
}
</script>

<style>

</style>
