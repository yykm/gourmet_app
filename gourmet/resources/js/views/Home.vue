<template>
  <div id="home">
    <Jumbotron :site_name="'Gourmet'"></Jumbotron>
    <Main></Main>
  </div>
</template>

<script>
// @ is an alias to /src
import Jumbotron from './../components/Jumbotron.vue';
import Main from './../components/Main.vue';

export default {
  name: 'Home',
  components: {
    Jumbotron,
    Main
  }
};
</script>

<style></style>
