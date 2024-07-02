<template>
    <div class="home">
        <h1>SECJ3483 <br><span>Web Technology</span> <br>Course</h1>
        <p class="par">Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Sunt neque
            expedita atque eveniet <br> quis nesciunt. Quos nulla vero
            consequuntur, fugit nemo ad delectus
            <br> a quae totam ipsa illum minus laudantium?
        </p>
        <div v-if="loggedInUser">
            <router-link to="/"><button class="cn"><a href="#" @click="participate">WELCOME</a></button></router-link>
        </div>
        <div v-else>
            <router-link to="/login"><button class="cn"><a href="#" @click="participate">PARTICIPATE</a></button></router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'HomePage',
    props: {
        isLoggedIn: {
            type: Boolean,
            required: true
        },
    },
    computed: {
        loggedInUser() {
            return this.isLoggedIn;
        },
    }
};
</script>

<style>
.home {
    /* text-align: center; */
    padding: 50px;
    background-image: linear-gradient(to top, rgba(0, 0, 0, 0.5)50%, rgba(0, 0, 0, 0.5)50%),
                    url(../assets/background.jpg);
    height: 100vh;
}

.home h1 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 55px;
    padding-left: 100px;
    margin-top: 15%;
    letter-spacing: 2px;
    color: #fff;
}

.home h1 span {
    color: #ff7200;
}

.home .par {
    font-size: 18px;
    line-height: 1.6;
    color: #fff;
    margin-bottom: 30px;
    padding-left: 100px;
    font-family: Arial, Helvetica, sans-serif;
}

.home .cn {
    width: 200px;
    height: 40px;
    background: #ff7200;
    border: none;
    margin-bottom: 10px;
    margin-left: 100px;
    font-size: 18px;
    border-radius: 10px;
    cursor: pointer;
    transition: .4s ease;
    font-family: Arial, Helvetica, sans-serif;
}

.home .cn a {
    text-decoration: none;
    color: #000;
    transition: .3s ease;
}

.home .cn:hover {
    background-color: #fff;
    transform: scale(0.9) translateX(15px) translateY(15px);
    font-weight: bold;
}
</style>