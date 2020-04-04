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
        mounted() {
            axios.get('posts')
                 .then(res => {
                     this.posts = res.data
                     this.loading = false
                 })
                 .catch(err => {
                     this.loading = false
                     console.log('Unable to Fetch The Posts!');
                 })
        }
	}
</script>

<style>

</style>
