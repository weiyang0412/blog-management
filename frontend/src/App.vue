<template>
  <div id="app">
    <TopNavbar :user="loggedInUser" @logout="handleLogout" />
    <br><br>
    <router-view v-slot="{ Component, route }">
      <div :key="route.name">
        <Component :is="Component" />
      </div>
    </router-view>
  </div>
</template>

<script>
import TopNavbar from './components/navbar.vue'
// import HomePage from './components/home.vue'

export default {
  name: 'App',
  components: {
    TopNavbar
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

<style></style>
