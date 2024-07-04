<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">{{ form.id ? `Edit Post: ${originalTitle}` : 'Create New Post' }}</h1>
            </div>
            <div class="card-body">
                <form @submit.prevent="savePost">
                    <div class="mb-3">
                        <label>Title</label>
                        <input v-model="form.title" class="form-control" placeholder="Title" required />
                    </div>
                    <div class="mb-3">
                        <label>Excerpt</label>
                        <input v-model="form.excerpt" class="form-control" placeholder="Excerpt" required />
                    </div>
                    <div class="mb-3">
                        <label>Body</label>
                        <input v-model="form.body" class="form-control" placeholder="Body" required />
                    </div>
                    <div class="mb-3">
                        <label>Category</label>
                        <select v-model="form.category" name="category" id="category" class="form-control" required>
                            <option value="">Select a category</option>
                            <option value="food">Food</option>
                            <option value="lifestyle">Lifestyle</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">{{ form.id ? 'Update' : 'Create' }}</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { getPostById, updatePost, createPost } from '@/services/api';

export default {
    data() {
        return {
            form: {
                id: null,
                title: '',
                excerpt: '',
                body: '',
                category: '',
            },
            originalTitle: '',
        };
    },
    methods: {
        fetchPost() {
            const postId = this.$route.params.id;
            if (postId) {
                getPostById(postId).then(response => {
                    this.form = response.data;
                    this.originalTitle = response.data.title;
                });
            }
        },
        savePost() {
            if (this.form.id) {
                updatePost(this.form.id, this.form).then(() => {
                    this.$router.go(-1);
                });
            } else {
                createPost(this.form).then(() => {
                    this.$router.go(-1);
                });
            }
        }
    },
    created() {
        this.fetchPost();
    }
}
</script>

<style>
.card {
    margin: 20px auto;
    max-width: 600px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 15px;
    background-color: #f0f0f0;
    border-bottom: 1px solid #ddd;
}

.card-title {
    margin: 0;
    font-size: 1.5rem;
}

.card-body {
    padding: 20px;
}

.form-control {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-success {
    background-color: #5cb85c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success:hover {
    background-color: #4cae4c;
}

select.form-control {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>