<template>
  <div class="jumbotron jumbotron-extend text-center m-0">
    <div class="jumbotron-container p-4 p-lg-5">
      <h1 class="display-4 site-name">{{ site_name }}</h1>
      <p>
        <b-button class="btn-black px-3 px-lg-4">LOGIN</b-button>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Header',
  props: {
    site_name: {
      type: String,
      default: 'site_name'
    }
  }
};
</script>

<style scoped>
.jumbotron-extend {
  position: relative;
  height: 50vh;
  min-height: 300px;
  background: url(/img/pizza.jpg) no-repeat center center;
  background-size: cover;
  border-radius: 0%;
}

.jumbotron-container {
  /* ヘッダー文字中央揃え */
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  /* ヘッダー背景 */
  background-color: rgba(255, 255, 255, 0.5);
  display: inline-block;
  border-radius: 50%;
}

.site-name {
  margin-bottom: 35px;
  font-family: 'Playfair Display', serif;
}

.btn-black {
  /* border-radius: 0; */
  background-color: #000;
  color: #fff;
  font-family: 'Avenir', serif;
}
.btn-black:hover {
  background-color: #fff;
  color: #000;
}
</style>
