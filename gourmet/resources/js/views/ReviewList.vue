<template>
  <div id="reviews">
    <div v-if="isLogin && reviews" class="review__header mt-5 mb-4">
      <div class="text-center">
        <b-button @click="scrollToForm" variant="primary" class="px-3 py-2" pill
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            width="20"
            height="20"
            fill="currentColor"
            class="bi bi-chat-square-text-fill mr-2"
            viewBox="0 0 16 16"
          >
            <path
              d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.5a1 1 0 0 0-.8.4l-1.9 2.533a1 1 0 0 1-1.6 0L5.3 12.4a1 1 0 0 0-.8-.4H2a2 2 0 0 1-2-2V2zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"
            /></svg
          >口コミを投稿する</b-button
        >
      </div>
    </div>
    <div v-if="reviews" class="review__body p-4">
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
    <div v-else class="text-center">
      <p >まだ投稿された口コミがありません。</p>
    </div>
    <div v-if="isLogin" class="form__area mt-4">
      <ReviewForm @reviewPost="onPost" :shop="shop" />
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
      shop: null, // 店舗情報
      reviews: null, // 口コミ一覧オブジェクトの配列
      loading: null, // ローディング表示フラグ
      lastPage: 1 // ページングの最終ページ番号
    };
  },
  computed: {
    isLogin() {
      return this.$store.getters["App/isLogin"];
    },
    getShop() {
      return this.$store.getters["App/getShop"];
    }
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),
    
    // フォームへスクロール
    scrollToForm() {
      document
        .getElementsByClassName("form__area")[0]
        .scrollIntoView({
          behavior: "smooth",
          block: "center",
          inline: "nearest"
        });
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
  },
  created(){
    this.shop = this.getShop(this.shopId);
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
