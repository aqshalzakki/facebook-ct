<template>
	<div id="app" class="h-screen flex flex-col flex-1 overflow-y-hidden">
		<Nav />

		<div class="flex overflow-y-hidden flex-1">
			<Sidebar />

			<div class="overflow-x-hidden w-2/3">
				<router-view/>
			</div>
		</div>
	</div>
</template>

<script>
	import Nav from './layouts/Navbar'
	import Sidebar from './layouts/Sidebar'

	export default {
		components: {Nav, Sidebar},

		created() {
			this.$store.dispatch('fetchAuthUser')
			this.changeTitle(this.$route.meta.title)
		},

		watch : {
			$route(to, from) {
				this.changeTitle(to.meta.title)
			}
		},

		methods: {
			changeTitle(title) {
				this.$store.dispatch('setPageTitle', title)
			}
		}
	}
</script>

<style scoped>

</style>