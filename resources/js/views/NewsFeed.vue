<template>
	<div class="flex flex-col items-center py-4">
		<NewPost />
        <p v-if="loading">Loading posts...</p>
		<Post v-else v-for="post in posts.data" :post="post" :key="post.data.post_id"/>
	</div>
</template>

<script>
	import NewPost from '../components/NewPost'
	import Post from '../components/Post'

	export default {
		name: 'NewsFeed',
        components: { NewPost, Post },
        data() {
            return {
                posts: null,
                loading: true
            }
        },
        async created() {
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
