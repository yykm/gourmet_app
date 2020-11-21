<template>
  <div id="app">
    <Header></Header>
    <Search></Search>
    <div>aaa</div>
  </div>
</template>

<script>
// @ is an alias to /src
import Header from '@/components/Header.vue';
import Search from '@/components/Search.vue';

export default {
  name: 'App',
  components: {
    Header,
    Search
  }
};
</script>

<style></style>
