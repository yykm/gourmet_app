<!-- props:shops/shopsCount -->
<template>
  <div class="search-result" v-if="shopsCount !== null">
    <div class="container mt-3">
      <!-- 検索件数表示部 -->
      <div v-if="shopsCount > 0">
        <p class="result-count text-right">{{ shopsCount }}件表示中</p>

        <!-- 店舗情報表示部   -->
        <ul class="shops-list p-0 mb-5">
          <li v-for="shop of shops" v-bind:key="shop.id">
            <!-- 店舗ヘッダー部 -->
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
                      :to="{ id: shop.id, tab: 'info' } | detailURL"
                      rel="noopener noreferrer"
                    >
                      <span>{{ shop.name }}</span>
                    </router-link>
                  </p>
                </div>
              </div>
            </div>

            <!-- お店のメインコンテンツ -->
            <b-row class="p-content mt-4" align-h="between">
              <b-col cols="12" md="3">
                <!-- お店の画像  -->
                <div class="p-main-img">
                  <p>
                    <b-link
                      :to="{ id: shop.id, tab: 'review' } | detailURL"
                      rel="noopener noreferrer"
                    >
                      <img :src="shop.photo" :alt="shop.name" /><br />
                      <span class="mt-2 m-blank">口コミを見る</span>
                    </b-link>
                  </p>
                </div>
              </b-col>

              <b-col cols="12" md="9">
                <!-- お店の詳細情報 -->
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

        <b-pagination
          :value="curPage"
          :total-rows="shopsCount"
          :per-page="perPage"
          align="center"
          @change="onChange"
        ></b-pagination>
      </div>
      <div v-else>
        <p class="result-count text-center text-danger">
          検索結果に該当するお店はありません。別のキーワードでお試しください。
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { createNamespacedHelpers } from "vuex";
const { mapGetters } = createNamespacedHelpers("App");

export default {
  name: "Result",
  computed: {
    ...mapGetters(["shopsCount", "getShopsByPage", "getShops"])
  },
  data() {
    return {
      shops: [],
      perPage: 5, // ページ毎に表示する店舗数
      curPage: 1 // 現在のページ番号
    };
  },
  watch: {
    getShops: function() {
      this.curPage = 1;
      this.shops = this.getShopsByPage(this.curPage, this.perPage);
    },
    shops: function() {
      this.$nextTick(() => {
        // 検索結果更新のたび上部までスクロール
        const scrollY = document.getElementsByTagName("header")[0].clientHeight;
        window.scrollTo(0, scrollY);
      });
    }
  },
  methods: {
    onChange(page) {
      this.curPage = page;
      this.shops = this.getShopsByPage(this.curPage, this.perPage);
    }
  },
  // 店舗詳細ページへのURLを返却
  filters: {
    detailURL: function({ id, tab }) {

      if (typeof id !== "string") {
        return "/";
      }
      // タブ番号
      var tabNo = 0;

      switch (tab) {
        case "info":
          tabNo = 0;
          break;
        case "menu":
          tabNo = 1;
          break;
        case "photoList":
          tabNo = 2;
          break;
        case "review":
          tabNo = 3;
          break;
        case "map":
          tabNo = 4;
          break;
        default:
          tabNo = 0;
      }

      // /detail/店舗id/タブ番号
      return ["", "detail", id ,tabNo, tab].join("/");
    }
  }
};
</script>

<style scoped>
/* pタグのマージンをリセット */
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
/* フォント調整  */
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
    margin-bottom: 4vw;
  }
}
/* リストのマーク除去 */
.shops-list {
  list-style: none;
}
/* リスト間の枠線 */
li + li {
  border-width: 0 0 1px 0;
  border-top: 1px solid rgba(0, 0, 0, 0.125);
  margin-top: 50px !important;
  padding-top: 50px !important;
}
</style>
