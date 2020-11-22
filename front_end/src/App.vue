<template>
  <div id="app">
    <b-container fluid class="bv-example-row">
      <b-row align-h="around">
        <b-col cols="12" class="px-0"> <Header></Header></b-col>
      </b-row>

      <b-row align-h="around">
        <b-col cols="3"><Search></Search></b-col>
        <b-col cols="8"><History></History></b-col>
      </b-row>

      <b-row align-h="around">
        <b-col cols="12" class="px-0"><Footer></Footer></b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
// @ is an alias to /src
import Header from '@/components/Header.vue';
import Search from '@/components/Search.vue';
import Footer from '@/components/Footer.vue';
import History from '@/components/History.vue';

export default {
  name: 'App',
  components: {
    Header,
    Search,
    Footer,
    History
  }
};
</script>

<style></style>
