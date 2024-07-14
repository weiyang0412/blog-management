import { createRouter, createWebHistory } from 'vue-router'
import { isLoggedIn } from '@/services/auth'
import HomePage from '@/components/menu/home.vue'
import AboutPage from '@/components/menu/about.vue'
import ContactUsPage from '@/components/contacts/ContactUs.vue'
import EditContactPage from '@/components/contacts/EditContact.vue'
import ViewFormPage from '@/components/contacts/ViewForm.vue'
import ContactFormListPage from '@/components/contacts/contactFormList.vue'
import LoginPage from '@/components/users/userLogin.vue'
import RegisterPage from '@/components/users/userRegister.vue'
import UserListPage from '@/components/users/userList.vue'
import PostListPage from '@/components/posts/postList.vue'
import EditPostPage from '@/components/posts/editPost.vue'
import CreatePostPage from '@/components/posts/createPost.vue'
import ViewPostPage from '@/components/posts/viewPost.vue'
import EditUserPage from '@/components/users/editUser.vue'
import CreateUserPage from '@/components/users/addUser.vue'


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
        path: '/user',
        name: 'UserList',
        component: UserListPage,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/user/:id',
        name: 'EditUser',
        component: EditUserPage,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/user/create',
        name: 'createUser',
        component: CreateUserPage,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/post',
        name: 'PostList',
        component: PostListPage,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/post/:id',
        name: 'EditPost',
        component: EditPostPage,
    },
    {
        path: '/post/create',
        name: 'CreatePost',
        component: CreatePostPage,
    },
    {
        path: '/view/:id',
        name: 'ViewPost',
        component: ViewPostPage,
    },
    {
        path: "/contact",
        name: "ContactUs",
        component: ContactUsPage,
    },
    {
        path: "/contact/view/:id",
        name: "ViewForm",
        component: ViewFormPage,
    },
    {
        path: "/contact/edit/:id",
        name: "EditContactUs",
        component: EditContactPage,
    },
    {
        path: "/contact/form/list",
        name: "ContactFormList",
        component: ContactFormListPage,
    },
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
