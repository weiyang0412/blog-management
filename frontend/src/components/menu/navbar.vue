<template>
    <nav>
        <div class="header-content">
            <img src="../../assets/logo.png" alt="Logo" class="logo" />
            <div class="menu-item"><a><router-link to="/">Home</router-link></a></div>
            <div v-if="isAdmin" class="menu-item"><a><router-link to="/user">User</router-link></a></div>
            <div v-if="isAdmin" class="menu-item"><a><router-link to="/post">Blog</router-link></a></div>
            <div v-if="isAdmin" class="menu-item"><a><router-link to="/contact/list">Contact</router-link></a></div>
            <div v-if="!isAdmin" class="menu-item"><a><router-link to="/contact">Contact Us</router-link></a></div>
            <!-- <div class="menu-item"><a><router-link to="/about">About</router-link></a></div> -->
        </div>
        <div class="dropdown-container">
            <Dropdown v-if="loggedInUser" :title="userName" :items="services" @item-click="handleDropdownClick" />
            <div v-else class="login-item">
                <a><router-link to="/login">Login</router-link></a>
            </div>
        </div>
    </nav>
</template>

<script>
import Dropdown from './Dropdown.vue';

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
    components: {
        Dropdown
    },
    data() {
        return {
            users: JSON.stringify(this.user || {}),
            searchQuery: '',
            services: [
                // {
                //     title: 'example',
                //     action: ''
                // },
                {
                    title: 'Logout',
                    action: 'logout'
                }
            ],
        };
    },
    computed: {
        loggedInUser() {
            return !!this.user;
        },
        userName() {
            return this.user ? this.user.name : '';
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
        },
        handleDropdownClick(item) {
            console.log('Dropdown item clicked:', item);
            if (item.action === 'logout') {
                console.log('Executing logout action');
                this.logout();
            } else {
                console.log('Clicked on:', item.title);
            }
        }
    }
};
</script>

<style>
.header-content {
    display: flex;
    align-items: center;
}

nav {
    display: flex;
    align-items: center;
    justify-content: center;
}

nav .menu-item {
    color: #FFF;
    padding: 10px 20px;
    position: relative;
    text-align: center;
    border-bottom: 3px solid transparent;
    display: flex;
    transition: 0.4s;
}

nav .menu-item.active,
nav .menu-item:hover {
    background-color: #444;
    border-bottom-color: #FF5858;

}

nav .menu-item a {
    color: inherit;
    text-decoration: none;
}

.dropdown-container {
    margin-left: auto;
    padding-right: 10px;
}

nav .login-item {
    color: #FFF;
    padding: 10px 20px;
    position: relative;
    text-align: center;
    border-bottom: 3px solid transparent;
    display: flex;
    transition: 0.4s;
}

nav .login-item a {
    color: inherit;
    text-decoration: none;
}

nav .login-item.active,
nav .login-item:hover {
    background-color: #444;
    border-bottom-color: #FF5858;

}
</style>
