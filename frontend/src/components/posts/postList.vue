<template>
    <div class="post-list-container">
        <h1 class="title">Manage Posts</h1>
        <div class="container">
            <div class="links">
                <a style="color: black">Links</a>
                <router-link to="/all-posts">All Post</router-link>
                <router-link to="/post/create">New Post</router-link>
            </div>
            <div class="card">
                <div class="posts">
                    <div v-for="post in posts" :key="post.id" class="post-card">
                        <div class="post-item">
                            <span>{{ post.title }}</span>
                            <div class="actions">
                                <button @click="editPost(post.id)">Edit</button>
                                <button @click="deletePost(post.id)">Delete</button>
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
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
    padding-left: 25%;
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
    color: blue;
}

.posts {
    /* display: flex; */
    /* flex-direction: column; */
}

.post-card {
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    padding: 10px;
    margin: 5px;
}

.post-item {
    display: flex;
    /* justify-content: space-between; */
    /* align-items: center; */
    margin-right: 100px;
}

.actions button {
    /* margin-left: 10px; */
    background-color: transparent;
    border: none;
    color: blue;
    cursor: pointer;
    margin-left: 100px;
}

.actions button:hover {
    text-decoration: underline;
}

.post-card:last-child {
    border-bottom: none;
}
</style>