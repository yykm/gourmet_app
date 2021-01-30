<template>
  <div id="reviews">
    <div class="review__header mt-5 mb-4">
      <div class="text-center">
        <b-button @click="scrollToForm" variant="primary" class="px-3 py-2" pill
          >口コミを投稿する</b-button
        >
      </div>
    </div>
    <div class="review__body p-4">
      <div class="paginate__area mb-4">
        <Pagination :lastPage="lastPage" />
      </div>
      <div class="review__wraper py-4">
        <ul class="review__list p-0">
          <li v-for="review in reviews" :key="review.id">
            <Review :review="review" />
          </li>
        </ul>
      </div>
      <div class="paginate__area mt-4">
        <Pagination :lastPage="lastPage" />
      </div>
    </div>
    <div class="form__area mt-4">
      <ReviewForm @reviewPost="onPost" :shopId="shopId" />
    </div>
  </div>
</template>

<script>
import ReviewForm from "./../components/ReviewForm.vue";
import Loader from "./../components/Loader.vue";
import Pagination from "./../components/Pagination.vue";
import Review from "./../components/Review.vue";
import { mapMutations } from "vuex";
import { ERR } from "./../store/const.js";

export default {
  name: "ReviewList",
  components: {
    ReviewForm,
    Pagination,
    Review
  },
  data() {
    return {
      shopId: this.$route.params.id, // 店舗ID
      reviews: null, // 口コミ一覧オブジェクトの配列
      loading: null, // ローディング表示フラグ
      lastPage: 1 // ページングの最終ページ番号
    };
  },
  methods: {
    // フォームへスクロール
    scrollToForm() {
      document.getElementsByClassName("form__area")[0].scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
    },

    // 口コミ一覧取得
    async fetchReviews() {
      // ローディング表示
      this.loading = true;

      // クエリパラメータのページ番号を取得
      const page = Number(
        /^[1-9][0-9]*$/.test(this.$route.query.page)
          ? this.$route.query.page * 1
          : 1
      );
      // shop_idをクエリパラメータに
      const response = await axios
        .get("/api/comments", {
          params: {
            shop_id: this.shopId,
            page
          }
        })
        .catch(err => err.response || err);

      // ステ－タスコード200以外エラー
      if (response.status !== ERR.OK) {
        this.setCode("setCode", response.status);
        // ローダー非表示
        this.loading = false;
        return;
      }
      // ローダー非表示
      this.loading = false;
      // レスポンス各値を取得
      this.reviews =
        response.data.data.length === 0 ? null : response.data.data;
      this.lastPage = response.data.last_page;
    },

    // 投稿されたら口コミ一覧を更新
    async onPost() {
      if (Number(this.$route.query.page) !== 1) {
        this.$router.push({ path: "", query: { page: 1 } });
      } else {
        await this.fetchReviews();
      }
    }
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchReviews();
      },
      immediate: true
    }
  }
};
</script>

<style scoped>
.review__body {
  border-bottom: 1px dashed #ccc;
}

.review__list {
  list-style: none;
}
</style>
