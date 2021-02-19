<template>
  <!-- 検索結果の店舗一覧 -->
  <div class="search-result" v-if="shopsCount !== null">
    <div class="container shadow-sm mt-4 p-4">
      <!-- 検索結果の件数 -->
      <div v-if="shopsCount > 0 && !loading">
        <p class="result-count text-right">{{ shopsCount }}&thinsp;件表示中</p>

        <!-- 店舗情報   -->
        <ul class="shops-list p-0 mb-5">
          <li v-for="shop of shops" v-bind:key="shop.id">
            <!-- 店舗情報ヘッダー部 -->
            <div class="p-name-wrap">
              <div class="d-flex align-items-center">
                <!-- ロゴ画像 -->
                <div class="p-shop-logo">
                  <img :src="shop.logo" alt="" />
                </div>

                <!-- 	店舗ジャンル名・お店ジャンルキャッチ・掲載店名 -->
                <div class="p-name-content ml-4">
                  <p class="p-category mr-3 text-center">{{ shop.category }}</p>
                  <p class="p-catch">{{ shop.catch }}</p>
                  <p class="p-name">
                    <router-link
                      :to="{
                        path: `detail/${shop.id}`,
                        query: { tab: 0 },
                      }"
                      rel="noopener noreferrer"
                    >
                      <span>{{ shop.name }}</span>
                    </router-link>
                  </p>
                </div>
              </div>
            </div>

            <!-- 店舗情報ボディー部 -->
            <b-row class="p-content mt-4" align-h="between">
              <b-col cols="12" md="3">
                <!-- 店舗の画像  -->
                <div class="p-main-img text-center">
                  <p>
                    <b-link
                      :to="{
                        path: `detail/${shop.id}`,
                        query: { tab: 2 },
                      }"
                      rel="noopener noreferrer"
                    >
                      <img :src="shop.photo" :alt="shop.name" /><br />
                      <span class="mt-2 m-blank">口コミを見る</span>
                    </b-link>
                  </p>
                </div>

                <!-- 予約 -->
                <div v-if="isLogin" class="p-reserve text-center my-4">
                  <Reserve :shop="shop" />
                </div>
              </b-col>

              <!-- 店舗の詳細情報 -->
              <b-col cols="12" md="9">
                <table class="p-table">
                  <tr>
                    <th>住所</th>
                    <td>
                      {{ shop.address }}<br />
                      <a
                        :href="shop.map"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="m-txt-hover p-map-link"
                        >Google Mapで見る</a
                      >
                    </td>
                  </tr>
                  <tr>
                    <th>アクセス</th>
                    <td>{{ shop.access }}</td>
                  </tr>
                  <tr>
                    <th>営業時間</th>
                    <td>{{ shop.open }}</td>
                  </tr>
                  <tr>
                    <th>ランチ</th>
                    <td>{{ shop.lunch }}</td>
                  </tr>
                  <tr>
                    <th>クレジット<br class="nopc" />カード</th>
                    <td>{{ shop.card }}</td>
                  </tr>
                  <tr>
                    <th>禁煙・喫煙</th>
                    <td>{{ shop.smoking }}</td>
                  </tr>
                  <tr>
                    <th>Wi-Fi</th>
                    <td>{{ shop.wifi }}</td>
                  </tr>
                  <tr>
                    <th>駐車場</th>
                    <td>{{ shop.parking }}</td>
                  </tr>
                  <tr>
                    <th>平均予算</th>
                    <td>{{ shop.average }}</td>
                  </tr>
                  <tr v-if="shop.memo">
                    <th>料金備考</th>
                    <td>{{ shop.memo }}</td>
                  </tr>
                </table>
              </b-col>
            </b-row>
          </li>
        </ul>

        <!-- ペジネーションリンク -->
        <div class="pagination__area d-flex justify-content-center">
          <Pagination :lastPage="lastPage" />
        </div>
      </div>

      <!-- 検索結果が１件もない場合 -->
      <div v-else-if="!loading">
        <p class="result-count text-center text-danger">
          検索結果に該当するお店はありません。別のキーワードでお試しください。
        </p>
      </div>

      <!-- ローダー -->
      <div v-else class="spin mt-5">
        <Loader height="5rem" width="5rem" label="Medium Spinner" />
      </div>
    </div>
  </div>
</template>

<script>
import { createNamespacedHelpers } from "vuex";
const { mapGetters } = createNamespacedHelpers("App");
import Loader from "./../components/Loader.vue";
import Reserve from "./../components/Reserve.vue";
import Pagination from "./../components/Pagination.vue";

export default {
  name: "Result",
  components: {
    Loader,
    Reserve,
    Pagination,
  },
  computed: {
    ...mapGetters(["shopsCount", "getShopsByPage", "getShops", "isLogin"]),

    // ページング最終番号を返却
    lastPage() {
      return Math.ceil(this.shopsCount / this.perPage);
    },
  },
  data() {
    return {
      shops: [], // 店舗情報
      perPage: 5, // ページ毎に表示する店舗数
    };
  },
  props: {
    // ローダー表示フラグ
    loading: {
      type: Boolean,
      default: false,
      required: false,
    },
    // ペジネーションのページ番号
    page: {
      type: Number,
      required: false,
    },
  },
  watch: {
    // 検索しなおしの度1ページ目の店舗情報取得し
    // ページ最上部へ遷移
    getShops: function () {
      this.onChange(true);
      window.scrollTo(0, 0);
    },

    // ページ遷移時に対応するページの検索結果を表示
    $route: {
      async handler() {
        this.onChange(false);
      },
      immediate: true,
    },
  },
  methods: {
    // 店舗情報の再取得処理
    onChange(research_flag) {
      // 再検索の場合1ページ目を設定
      var curPage = 1;
      // ページ遷移の場合クエリパラメータよりページ数を設定
      if (!research_flag) {
        curPage = this.$route.query.page ?? 1;
      }
      // ページ番号に対応する店舗情報取得
      this.shops = this.getShopsByPage(curPage, this.perPage);
    },
  },
};
</script>

<style scoped>
.container {
  background-color: #fff;
  min-height: 90vh;
}

p {
  margin-bottom: 0px;
}
img {
  max-width: 100%;
  height: auto;
}
a {
  color: #072840;
}
a:hover {
  color: initial;
  text-decoration: none;
}
table {
  border-collapse: collapse;
  vertical-align: baseline;
}
th,
td {
  vertical-align: baseline;
  padding-bottom: 0.75rem;
}
.result-count {
  font-size: 1.1rem;
  line-height: 1.7;
  letter-spacing: 0.03em;
}

.p-category {
  display: inline-block;
  border: 1px solid currentColor;
  padding: 3px 6px;
  border-radius: 3px;
  font-size: 0.8rem;
}
.p-catch {
  font-size: 1rem;
  display: inline-block;
}
.p-name {
  font-size: 1.3rem;
  font-weight: bolder;
  margin-top: 9px;
}
.p-main-img a:hover {
  opacity: 0.6;
}
.p-main-img a {
  display: block;
  transition: opacity 0.3s ease-in-out;
}
.m-blank {
  display: inline-block;
  padding-right: 1.6em;
  background-image: url(/img/icon/icn_blank_link.svg);
  background-repeat: no-repeat;
  background-position: right 0.2em center;
  background-size: 0.86em;
}
.p-table th {
  width: 120px;
  font-size: 1rem;
}
.p-map-link {
  display: inline-block;
  padding: 0.1em 2em 0 0;
  background-image: url(/img/icon/icn_map.svg);
  background-repeat: no-repeat;
  background-position: right 0.1em center;
  background-size: 1.3em;
}
.m-txt-hover {
  text-decoration: underline;
}

@media (max-width: 767.98px) {
  .p-main-img {
    text-align: center;
  }

  .p-main-img img {
    max-width: 60%;
  }

  .p-name,
  .result-count {
    font-size: 1rem;
  }

  .p-catch,
  .p-table th,
  td {
    font-size: 0.9rem;
  }

  li + li {
    margin-top: 34px !important;
    padding-top: 34px !important;
  }

  .p-category {
    font-size: 0.67rem;
  }

  /deep/ .reserve__btn {
    width: 200px !important;
  }
}
.shops-list {
  list-style: none;
}

li + li {
  border-width: 0 0 1px 0;
  border-top: 1px solid rgba(0, 0, 0, 0.125);
  margin-top: 50px;
  padding-top: 50px;
}

.spin {
  display: flex;
  justify-content: center;
  align-items: center;
}

/deep/ .reserve__btn {
  width: 90%;
}
</style>
