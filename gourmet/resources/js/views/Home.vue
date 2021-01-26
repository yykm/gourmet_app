<template>
  <b-jumbotron fluid container-fluid>
    <div class="heading-wrapper">
      <div class="heading text-center">
        <h1>{{ site_name }}</h1>
        <p>
          <slot>お近くの飲食店をフリーワードで検索</slot>
        </p>
      </div>
    </div>
    <!-- 検索フォーム -->
    <Search></Search>
  </b-jumbotron>
</template>

<script>
import Search from "./../components/Search.vue";

export default {
  name: "Home",
  components: {
    Search
  },
  props: {
    site_name: {
      default: 'Gourmet',
      type: String,
    },
  },
};
</script>

<style scoped>
.jumbotron .container-fluid {
  background: rgba(255, 255, 255, 0.3);
  height: 100%;
  width: 100%;
}

.jumbotron .heading-wrapper {
  padding-top: 22vh;
}

/* タイトル */
.jumbotron .heading h1 {
  font-size: 3.5rem;
  font-weight: 330;
  line-height: 1.2;
}
/* リード文 */
.jumbotron .heading p {
  font-weight: 300;
}
/* 背景画像 */
.jumbotron {
  min-height: 300px;
  height: 100vh;
  background: url(/img/louis-hansel-shotsoflouis-4FY6xU05qaQ-unsplash.jpg)
    no-repeat center center;
  background-size: cover;
  border-radius: 0%;
  margin: 0;
  padding: 0;
}
@media (min-width: 768px) {
  .jumbotron .heading h1 {
    font-size: 5.4rem;
  }

  .jumbotron .heading p {
    font-size: 1.25rem;
  }
}
</style>
