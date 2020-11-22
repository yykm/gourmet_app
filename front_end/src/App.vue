<template>
  <div id="app">
    <div class="container-fluid px-0">
      <Header></Header>
      <Search></Search>
      <Footer></Footer>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import Header from '@/components/Header.vue';
import Search from '@/components/Search.vue';
import Footer from '@/components/Footer.vue';

export default {
  name: 'App',
  components: {
    Header,
    Search,
    Footer
  }
};
</script>

<style></style>
