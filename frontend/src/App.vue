<template>
  <div id="app">
    <header>
      <Navbar :user="loggedInUser" @logout="handleLogout" />
    </header>
    <router-view v-slot="{ Component, route }">
        <div :key="route.name">
          <Component :is="Component" />
        </div>
      </router-view>
  </div>
</template>

<script>
import Navbar from './components/navbar.vue';

export default {
  name: 'app',
  components: {
    Navbar
  },
  data() {
    return {
      loggedInUser: null
    };
  },
  mounted() {
    this.checkLoggedInUser();
  },
  methods: {
    checkLoggedInUser() {
      const user = JSON.parse(localStorage.getItem('loggedInUser'));
      if (user) {
        this.loggedInUser = user;
      }
    },
    handleLogout() {
      localStorage.removeItem('loggedInUser');
      this.loggedInUser = null;
      this.$router.push('/login');
    },
    handleLoginSuccess(userData) {
      this.loggedInUser = userData.user;
    }
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Courier New', Courier, monospace;
}

header {
  width: 100vw;
  background-color: #222;
  padding: 15px;
}
</style>
