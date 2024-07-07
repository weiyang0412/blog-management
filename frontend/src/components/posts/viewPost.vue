<template>
    <div class="post-detail" v-if="post">
        <router-link to="/" class="back-link">← Back to Posts</router-link>
        <div class="post-header">
            <div class="post-image-author">
                <img :src="getThumbnailUrl(post.thumbnail)" alt="Post Thumbnail" class="post-thumbnail" />
                <div class="post-footer">
                    <p class="post-date">Published {{ timeSince(post.created_at) }} ago</p>
                    <div class="author-info">
                        <img :src="getRandomAuthorImage()" alt="Author" class="author-image" />
                        <span>{{ post.author_name }}</span>
                    </div>
                </div>
            </div>
            <div class="post-content">
                <span class="label">{{ post.category }}</span>
                <h1 class="post-title">{{ post.title }}</h1>
                <p class="post-body">{{ post.body }}</p>
            </div>
        </div>
    </div>
    <div v-else>
        <p>Loading...</p>
    </div>
</template>

<script>
import { getPostById } from '@/services/api';

export default {
    name: 'ViewPost',
    data() {
        return {
            post: null
        };
    },
    methods: {
        fetchPost() {
            const postId = this.$route.params.id;
            getPostById(postId).then(response => {
                this.post = response.data;
            }).catch(error => {
                console.error(error);
            });
        },
        getThumbnailUrl(filename) {
            return require(`@/assets/thumbnails/${filename}`);
        },
        getRandomAuthorImage() {
            const randomId = Math.floor(Math.random() * 1000);
            return `https://picsum.photos/seed/${randomId}/50/50`;
        },
        timeSince(date) {
            const now = new Date();
            const past = new Date(date);
            const seconds = Math.floor((now - past) / 1000);

            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) {
                return interval + (interval === 1 ? " year" : " years");
            }

            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                return interval + (interval === 1 ? " month" : " months");
            }

            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                return interval + (interval === 1 ? " day" : " days");
            }

            interval = Math.floor(seconds / 3600);
            if (interval >= 1) {
                return interval + (interval === 1 ? " hour" : " hours");
            }

            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
                return interval + (interval === 1 ? " minute" : " minutes");
            }

            return Math.floor(seconds) + (seconds === 1 ? " second" : " seconds");
        }
    },
    created() {
        this.fetchPost();
    }
};
</script>

<style scoped>
.post-detail {
    max-width: 800px;
    margin: auto;
    padding: 50px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.back-link {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    color: #007bff;
}

.post-header {
    display: flex;
    align-items: flex-start;
}

.post-image-author {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 20px;
}

.post-thumbnail {
    width: 300px;
    height: auto;
    border-radius: 8px;
    margin-bottom: 10px;
}

.post-content {
    flex: 1;
}

.label {
    background: #fff;
    padding: 5px 10px;
    border-radius: 50px;
    font-size: 0.85rem;
    color: rgba(147, 197, 253, var(--tw-text-opacity));
    border: 1px solid rgba(147, 197, 253, var(--tw-text-opacity));
    text-align: center;
}

.post-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 10px;
    margin-top: 10px;
}

.post-body {
    font-size: 15px;
    margin-bottom: 20px;
}

.post-footer {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.author-info {
    display: flex;
    align-items: center;
    margin-right: 200px;
    margin-top: 10px;
}

.author-image {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.post-date {
    font-size: 1rem;
    color: #6c757d;
    text-align: center;
}
</style>