<template>
  <div class="flex flex-col items-center" v-if="statuses.user === 'success' && user">
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
              <button v-if="friendButtonText && friendButtonText !== 'Accept'" @click="addFriend" class="py-1 px-3 bg-gray-400 rounded">
                  {{ friendButtonText }}
              </button>
              <button v-if="friendButtonText && friendButtonText === 'Accept'" @click="acceptFriendRequest" class="mr-2 py-1 px-3 bg-blue-500 rounded">
                  Accept
              </button>
              <button v-if="friendButtonText && friendButtonText === 'Accept'" @click="ignoreFriendRequest" class="py-1 px-3 bg-gray-400 rounded">
                  Ignore
              </button>
          </div>
          

      </div>

      <div v-if="statuses.posts === 'loading'">Loading posts...</div>

      <div v-else-if="!hasPosts() ">
          Oops :( You've got no posts. Get started...
      </div>

      <Post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"/>
  </div>
</template>

<script>
import Post from '../../components/Post'
import { mapGetters, mapActions } from 'vuex'

export default {
    name: 'Show',
    components: { Post },

    async created() {
        this.fetchUser(this.userId)
        this.fetchUserPosts(this.userId)
    },

    methods: {
        ...mapActions({
            fetchUser : 'fetchUser',
            fetchUserPosts: 'fetchUserPosts'
        }),
        hasPosts() {
            return this.posts.data.length > 0
        },
        addFriend(event) {
            console.log(`${this.userId} will be added!`);
            
            this.$store.dispatch('sendFriendRequest', this.userId)
        },
        acceptFriendRequest() {
            console.log(`${this.userId} will be accepted...`)

            this.$store.dispatch('acceptFriendRequest', this.userId)
        },
        ignoreFriendRequest() {
            console.log(`${this.userId} will be accepted...`)

            this.$store.dispatch('ignoreFriendRequest', this.userId)
        },
    },

    computed: {
        ...mapGetters({
            user: 'user',
            posts: 'posts',
            statuses: 'statuses',
            friendButtonText: 'friendButtonText',

        }),
        userId() {
            return this.$route.params.userId
        },
    },

    watch: {
        $route(to, from) {
            this.fetchUser(to.params.userId)
            this.fetchUserPosts(to.params.userId)
        }
    }
}
</script>

<style>

</style>
