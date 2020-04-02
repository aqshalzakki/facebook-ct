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
		components: {Nav, Sidebar}
	}
</script>

<style scoped>

</style>