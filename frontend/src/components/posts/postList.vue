<template>
    <div class="post-list-container">
        <h1 class="title">Manage Posts</h1>
        <div class="container">
            <div class="links">
                <a style="color: black">Links</a>
                <router-link :to="{ path: '/post' }" :class="{ 'active-link': $route.path === '/post', 'inactive-link': $route.path !== '/post' }">All Posts</router-link>
                <router-link :to="{ path: '/create' }" :class="{ 'active-link': $route.path === '/create', 'inactive-link': $route.path !== '/create' }">New Post</router-link>
            </div>
            <div class="card-container">
                <div class="card">
                    <div class="posts">
                        <div v-for="post in posts" :key="post.id" class="post-card">
                            <div class="post-item">
                                <span class="post-title">{{ post.title }}</span>
                                <div class="actions">
                                    <button @click="editPost(post.id)" class="editButton">Edit</button>
                                    <button @click="deletePost(post.id)" class="deleteButton">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getPosts, deletePost } from '@/services/api';

export default {
    data() {
        return {
            posts: []
        };
    },
    methods: {
        fetchPosts() {
            getPosts().then(response => {
                this.posts = response.data;
            });
        },
        editPost(id) {
            this.$router.push({ name: 'EditPost', params: { id } });
        },
        deletePost(id) {
            deletePost(id).then(() => {
                this.fetchPosts();
            });
        }
    },
    created() {
        this.fetchPosts();
    }
};
</script>

<style scoped>
.post-list-container {
    padding: 20px;
}

.title {
    display: inline-block;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
    margin-left: 25%;
    width: 50%;
}

.card-container {
    margin: 10px auto;
    padding: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card {
    margin: 10px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

}

.container {
    display: flex;
}

.links {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.links a {
    padding-left: 200px;
    text-decoration: none;
    /* color: blue; */
}

.posts {
    display: flex;
    flex-direction: column;
}

.post-card {
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    padding: 10px;
    margin: 5px;
    padding-left: 20px;
    /* width: 10px; */
}

.post-item {
    display: flex;
    /* justify-content: space-between; */
    /* align-items: center; */
    padding-left: 10px;
}

.post-title {
    flex-grow: 1;
    margin-right: 390px;
}

.actions {
    display: flex;
    gap: 10px;
}

.actions button {
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    width: 60px;
}

.actions button:hover {
    /* text-decoration: underline; */
}

.post-card:last-child {
    border-bottom: none;
}

.post-item .editButton {
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    /* margin-left: 280px; */
    /* width: 100px; */
}

.post-item .deleteButton {
    background-color: transparent;
    border: none;
    color: grey;
    cursor: pointer;
    /* margin-left: 50px; */
}

.active-link {
    color: blue;
}

.inactive-link {
    color: black;
}
</style>