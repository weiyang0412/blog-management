<template>
    <div class="navbar">
        <div class="icon">
            <h2 class="logo">W.Tech.</h2>
            <div class="menu">
                <ul>
                    <li><router-link to="/">HOME</router-link></li>                   
                    <li v-if="isAdmin"><router-link to="/user-list">USER</router-link></li>
                    <!-- <li v-if="isAdmin"><router-link to="/course-list">BLOG</router-link></li> -->
                    <li v-if="isAdmin"><router-link to="/post">BLOG</router-link></li>
                    <!-- <li><router-link to="/about">ABOUT</router-link></li> -->
                    <!-- <li><router-link to="/contact">CONTACT US</router-link></li> -->
                </ul>
            </div>
        </div>

        <div class="search">
            <!-- <input class="srch" type="search" v-model="searchQuery" placeholder="Type To Find">
            <button class="btn" @click="find">Find</button> -->
            
            <div class="user">
            <ul>
                <li v-if="loggedInUser" class="nav-item">
                    <span>{{ user.name }}</span>
                    <button @click="logout" class="btn btn-logout">Logout</button>
                </li>
                <li v-else class="nav-item">
                    <div class="btn2">
                        <!-- <router-link to="/register" class="nav-link">REGISTER</router-link> -->
                        <router-link to="/login" class="nav-link">LOGIN</router-link>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TopNavbar',
    props: {
        isLoggedIn: {
            type: Boolean,
            required: true
        },
        user: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            users: JSON.stringify(this.user || {}),
            searchQuery: ''
        };
    },
    computed: {
        loggedInUser() {
            return !!this.user;
        },
        isAdmin() {
            let parsedUser;
            const loggedInUser = localStorage.getItem('loggedInUser');
            console.log('loggedInUser', loggedInUser);
            try {
                parsedUser = JSON.parse(loggedInUser);
                console.log(parsedUser)
            } catch (e) {
                console.error("Failed to parse users:", e);
                return false;
            }
            
            return this.user && parsedUser.role === 'admin';
        }
    },
    methods: {
        find() {
            console.log('Searching for:', this.searchQuery);
        },
        logout() {
            localStorage.removeItem('token');
            this.$emit('logout');
            this.$router.push('/login');
        }
    }
};
</script>

<style>
body,
html {
    margin: 0;
    padding: 0;
}

.navbar {
    background-color: rgb(3, 3, 3);
    color: #fc8d1e;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.icon {
    display: flex;
    align-items: center;
}

.icon-img {
    width: 32px;
    height: 32px;
    margin-right: 10px;
}

.logo {
    font-size: 24px;
    margin: 0;
    margin-right: 30px;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
}

.menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    transition: 0.4s ease-in-out;
}

.menu ul li {
    margin-right: 20px;
}

.menu ul li a {
    color: #fff;
    text-decoration: none;
}

.menu ul li a:hover {
    color: #ff7200;
    /* text-decoration: underline; */
}

.navbar-end ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    transition: 0.4s ease-in-out;
}

.navbar-end ul li {
    margin-right: 20px;
}

.navbar-end ul li a {
    color: #fff;
    text-decoration: none;
}

.navbar-end ul li a:hover {
    color: #ff7200;
    /* text-decoration: underline; */
}

.navbar .search,
.navbar .user {
    display: flex;
    align-items: center;
}

.navbar .search .srch {
    margin-right: 10px;
    padding: 5px;
    border-radius: 5px;
}

.navbar .search .btn {
    background-color: #ff7200;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 5px;
}

.navbar .search .btn:hover {
    background-color: #ff9500;
}

.navbar .user ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center; /* 让用户名和按钮在水平方向对齐 */
}

.navbar .user ul li span {
    font-weight: bold;
    color: #ffc107;
    margin-right: 10px; /* 如果需要，可以调整用户名后面的间距 */
}

.navbar .user ul li .btn-logout {
    background-color: #ff0000;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.navbar .user ul li .btn-logout:hover {
    background-color: #c82333;
}

.navbar .user ul li .nav-link {
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    margin-left: 10px;
    color: #fff;
}

.navbar .user ul li .btn2 {
    display: flex;
}
</style>
