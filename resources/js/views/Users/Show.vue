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

              <p v-if="user" class="text-2xl text-gray-100 ml-4">{{ user.data.attributes.name }}</p>
          </div>
          
          <div class="absolute flex items-center bottom-0 right-0 mb-4 mr-12 z-20">
              <button v-if="friendButtonText" @click="addFriend" class="py-1 px-3 bg-gray-400 rounded">
                  {{ friendButtonText }}
              </button>
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
import { mapGetters, mapActions } from 'vuex'

export default {
    name: 'Show',
    components: { Post },
    data() {
        return {
            posts: null,
            postsLoading: true,
        }
    },

    async created() {

        this.fetchUser(this.userId)

        this.fetchPosts(this.userId)
    },

    methods: {
        ...mapActions({
            fetchUser : 'fetchUser'
        }),
        hasPosts() {
            return this.posts.data.length > 0
        },
        addFriend(event) {
            console.log(`${this.userId} will be added!`);
            
            this.$store.dispatch('sendFriendRequest', this.userId)
        },
        async fetchPosts(userId) {
            try {
                const response = await axios.get(`users/${userId}/posts`)
                this.posts = response.data
            } catch (error) {
                console.log('Unable to Fetch The Posts!');
            } finally { this.postsLoading = false }
        }

    },

    computed: {
        ...mapGetters({
            user: 'user',
            friendButtonText: 'friendButtonText',
        }),
        userId() {
            return this.$route.params.userId
        },
    },

    watch: {
        $route(to, from) {
            this.fetchUser(to.params.userId)
            this.fetchPosts(to.params.userId)
        }
    }
}
</script>

<style>

</style>
