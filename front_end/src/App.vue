<template>
  <div id="app">
    <Header :site_name="'Gourmet'"></Header>
    <Main></Main>
  </div>
</template>

<script>
// @ is an alias to /src
import Header from '@/components/Header.vue';
import Main from '@/components/Main.vue';

export default {
  name: 'App',
  components: {
    Header,
    Main
  }
};
</script>

<style></style>
