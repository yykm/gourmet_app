<template>
  <main role="main">
    <!-- 検索結果の表示領域 -->
    <Search></Search>
  </main>
</template>

<script>
import Search from './../components/Search.vue';

export default {
  name: 'Main',
  components: {
    Search
  }
};
</script>

<style scoped></style>
