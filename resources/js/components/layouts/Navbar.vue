<template>
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <router-link class="navbar-brand" :to="{ name: 'home' }">
            Facebook CT
        </router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <!-- <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'login'}">Login</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name: 'register'}">Register</router-link>
                </li>
            </ul> -->
        </div>
    </div>
</nav>
</template>

<script>
    export default {

    }
</script>

<style scoped>

</style>