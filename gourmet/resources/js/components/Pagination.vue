<template>
  <!-- ペジネーション -->
  <div class="photos__pagination">
    <b-pagination-nav
      :link-gen="linkGen"
      :number-of-pages="lastPage"
      use-router
      align="center"
    ></b-pagination-nav>
  </div>
</template>

<script>
export default {
  name: "Pagination",
  props: {
    // ペジネーションの最後のページ番号
    lastPage: {
      type: Number,
      required: true
    }
  },
  methods: {
    // 遷移先を返却
    linkGen(pageNum) {
      return {
        path: "",
        query: { page: pageNum }
      };
    }
  },
};
</script>

<style scoped>
</style>
