<template>
	<div id="app">
		<Navbar/>

		<router-view/>
	</div>
</template>

<script>
	import Navbar from './layouts/Navbar'

	export default {
		components: {Navbar}
	}
</script>

<style scoped>

</style>