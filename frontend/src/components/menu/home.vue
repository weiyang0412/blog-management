<template>
    <div class="home">
        <div class="buttons">
            <select v-model="selectedCategory">
                <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
            </select>
            <input v-model="searchQuery" placeholder="Find something" class="search-input" @keyup.enter="performSearch" @input="updateSearchQuery" />
        </div>
        <!-- <p>Selected Category: {{ selectedCategory }}</p> -->
        <div class="latest-article" v-if="filteredArticles.length">
            <img :src="getThumbnailUrl(filteredArticles[0].thumbnail)" alt="Latest Article Image"
                class="latest-article-image" />
            <div class="latest-article-content">
                <span class="label">{{ filteredArticles[0].category }}</span>
                <h2 class="article-title">{{ filteredArticles[0].title }}</h2>
                <p class="article-date">Published {{ timeSince(filteredArticles[0].created_at) }}</p>
                <p class="latest-article-description">{{ filteredArticles[0].excerpt }}</p>
                <div class="latest-article-footer">
                    <div class="author">
                        <img :src="getRandomAuthorImage()" alt="Author" class="author-image" />
                        <span>{{ filteredArticles[0].author }}</span>
                    </div>
                    <button class="read-more">Read More</button>
                </div>
            </div>
        </div>
        <div class="articles">
            <div class="article" v-for="(article) in filteredArticles.slice(1)" :key="article.id">
                <img :src="getThumbnailUrl(article.thumbnail)" alt="Article Image" class="article-image" />
                <div class="article-content">
                    <span class="label">{{ article.category }}</span>
                    <h2 class="article-title">{{ article.title }}</h2>
                    <p class="article-date">Published {{ timeSince(article.created_at) }}</p>
                    <p class="article-description">{{ article.excerpt }}</p>
                    <div class="article-footer">
                        <div class="author">
                            <img :src="getRandomAuthorImage()" alt="Author" class="author-image" />
                            <span>{{ article.author }}</span>
                        </div>
                        <button class="read-more">Read More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { getPosts } from '@/services/api';

export default {
    name: 'HomePage',
    data() {
        return {
            articles: [],
            categories: ['All', 'food', 'lifestyle'],
            selectedCategory: 'All',
            searchQuery: '',
            searchPerformed: false
        };
    },
    computed: {
        filteredArticles() {
            console.log('Filtering articles for category:', this.selectedCategory);
            let filtered = this.articles;

            if (this.selectedCategory !== 'All') {
                filtered = filtered.filter(article => article.category === this.selectedCategory);
            }

            if (this.searchPerformed && this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(article =>
                    article.title.toLowerCase().includes(query) ||
                    article.excerpt.toLowerCase().includes(query) ||
                    article.body.toLowerCase().includes(query)
                );
            }

            return filtered;
        }
    },
    methods: {
        getRandomAuthorImage() {
            const randomId = Math.floor(Math.random() * 1000);
            return `https://picsum.photos/seed/${randomId}/50/50`;
        },
        getThumbnailUrl(filename) {
            return require(`@/assets/thumbnails/${filename}`);
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        fetchArticles() {
            getPosts().then(response => {
                console.log('Fetched articles:', response.data);
                this.articles = response.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            }).catch(error => {
                console.error(error);
            });
        },
        updateSearchQuery(event) {
            this.searchQuery = event.target.value;
            this.searchPerformed = false;
        },
        performSearch() {
            this.searchPerformed = true;
            console.log('Search performed with query:', this.searchQuery);
        },
        timeSince(date) {
            const now = new Date();
            const past = new Date(date);
            const seconds = Math.floor((now - past) / 1000);

            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) {
                return interval + (interval === 1 ? " year" : " years") + " ago";
            }

            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                return interval + (interval === 1 ? " month" : " months") + " ago";
            }

            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                return interval + (interval === 1 ? " day" : " days") + " ago";
            }

            interval = Math.floor(seconds / 3600);
            if (interval >= 1) {
                return interval + (interval === 1 ? " hour" : " hours") + " ago";
            }

            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
                return interval + (interval === 1 ? " minute" : " minutes") + " ago";
            }

            return Math.floor(seconds) + (seconds === 1 ? " second" : " seconds") + " ago";
        }
    },
    created() {
        this.fetchArticles();
    }
};
</script>

<style>
:root {
    --tw-text-opacity: 1; /* 默认透明度为1（不透明） */
}

.home {
    text-align: center;
    padding: 20px;
}

.header h1 {
    font-size: 2.5rem;
    color: #000;
}

.header span {
    color: #007bff;
}

.buttons {
    margin: 20px 0;
}

select {
    padding: 10px;
    margin: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background: #f0f0f0;
    cursor: pointer;
    /* height: auto; */
}

.search-input {
    padding: 10px;
    margin: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background: #f0f0f0;
    cursor: pointer;
    height: 42px;
}

.category-button,
.find-button {
    background: #f0f0f0;
    border: none;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    border-radius: 5px;
}

.latest-article {
    display: flex;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
    overflow: hidden;
    width: 90%;
    margin: auto;
}

.latest-article-image {
    width: 50%;
    height: auto;
    object-fit: cover;
    border-radius: 10px;
}

.latest-article-content {
    padding: 15px;
    text-align: left;
    width: 50%;
}

.label {
    background: #fff;
    padding: 5px 10px;
    border-radius: 50px;
    font-size: 0.85rem;
    /* font-weight: bold; */
    color: rgba(147, 197, 253, var(--tw-text-opacity));
    border: 1px solid rgba(147, 197, 253, var(--tw-text-opacity));
}

.article-title {
    font-size: 1.25rem;
    margin: 10px 0;
}

.article-date {
    margin: 5px 0;
    color: rgb(196, 190, 190);
}

.article-description {
    margin: 5px 0;
}

.author {
    display: flex;
    align-items: center;
    height: auto;
}

.author-image {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.read-more {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    font-size: 0.8rem;
    cursor: pointer;
    border-radius: 10px;
}

.articles {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.article {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
    overflow: hidden;
    width: 304px;
}

.article-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.article-content {
    padding: 15px;
    text-align: left;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 10px;
}

.latest-article-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 280px;
}

.latest-article-description {
    margin: 100px 10px;
}
</style>