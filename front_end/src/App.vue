<template>
  <div id="app">
    <Header :site_name="'Gourmet'"></Header>
    <Search></Search>
    <Main></Main>
  </div>
</template>

<script>
// @ is an alias to /src
import Header from '@/components/Header.vue';
import Search from '@/components/Search.vue';
import Main from '@/components/Main.vue';

export default {
  name: 'App',
  components: {
    Header,
    Search,
    Main
  }
};
</script>

<style></style>
