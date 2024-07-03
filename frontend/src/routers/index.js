import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/components/home.vue'
import AboutPage from '@/components/about.vue'
import ContactUsPage from '@/components/contact.vue'
import LoginPage from '@/components/userLogin.vue'
import RegisterPage from '@/components/userRegister.vue'
import UserListPage from '@/components/userList.vue'
import PostListPage from '@/components/postList.vue'
import { isLoggedIn } from '@/services/auth'

const routes = [
    {
        path: "/",
        name: "Home",
        component: HomePage,
    },
    {
        path: "/about",
        name: "About",
        component: AboutPage,
    },
    {
        path: "/contact",
        name: "Contact",
        component: ContactUsPage,
    },
    {
        path: "/login",
        name: "Login",
        component: LoginPage,
    },
    {
        path: "/register",
        name: "Register",
        component: RegisterPage,
    },
    {
        path: '/user-list',
        name: 'UserList',
        component: UserListPage,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/post',
        name: 'PostList',
        component: PostListPage,
        meta: { requiresAuth: true, requiresAdmin: true }
    }


];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

router.beforeEach((to, from, next) => {
    const loggedInUser = localStorage.getItem('loggedInUser');
    let parsedUser = JSON.parse(loggedInUser);
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isLoggedIn()) {
        next({ path: '/login', query: { redirect: to.fullPath } });
        } else if (to.matched.some(record => record.meta.requiresAdmin)) {
            if (parsedUser.role === 'admin') {
                next();
            } else {
                alert('Access denied. Admin only.');
                next('/');
            }
        }
        else {
            next();
        }
    } else {
        next();
    }
});



export default router;
