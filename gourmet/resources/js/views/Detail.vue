<template>
  <!-- 店舗詳細画面 -->
  <div id="detail">
    <!-- ヘッダー -->
    <Header />

    <b-container class="shadow-sm py-1 mt-4">
      <b-card no-body class="overflow-hidden mt-4 w-100 border-0">
        <b-row no-gutters align-h="between">
          <!-- 店舗写真 -->
          <b-col
            sm="12"
            md="5"
            lg="4"
            class="d-flex align-items-center bg-wrapper"
          >
            <b-img
              :src="shop.image_l"
              center
              fluid-grow
              class="background p-2 border"
            ></b-img>
          </b-col>

          <!-- 店舗情報 -->
          <b-col sm="12" md="7" lg="8">
            <b-card-body>
              <!-- 店舗名 -->
              <div class="title__wrapper">
                <div
                  class="title__inner d-flex flex-column-reverse flex-md-row align-items-center align-items-md-start justify-content-between"
                >
                  <b-card-title>{{ shop.name }}</b-card-title>

                  <!-- お気に入りボタン -->
                  <FavoriteBtn
                    :shop="shop"
                    class="flex-shrink-0 ml-md-2 mb-3 mb-md-0"
                  />
                </div>
                <small class="mb-2">{{ shop.kana }}</small>
              </div>

              <!-- アクセス・平均予算 -->
              <b-card-text class="mt-2">
                <span>アクセス：{{ shop.access }}</span
                ><br />
                <span>平均予算：{{ shop.average }}</span>
              </b-card-text>

              <!-- 利用可能サービス -->
              <ul class="tag-list px-0">
                <li
                  v-for="(message, i) in services"
                  :key="i"
                  class="tag-list__item mt-3"
                >
                  <span class="tag-list__item-inner">{{ message }}</span>
                </li>
              </ul>

              <!-- 営業時間・定休日 -->
              <b-card-footer class="reservation border-0 clear-float py-2">
                <span>営業時間：{{ shop.open }}</span
                ><br />
                <span>定休日：{{ shop.close }}</span
                ><br />

                <!-- 予約 -->
                <div v-if="isLogin" class="text-center my-2">
                  <Reserve :shop="shop" />
                </div>
              </b-card-footer>
            </b-card-body>
          </b-col>
        </b-row>
      </b-card>

      <!-- 「店舗詳細」「写真」「口コミ」「地図」タブ -->
      <b-tabs
        v-model="tab_index"
        @activate-tab="onChange"
        nav-class="flex-nowrap"
        :fill="true"
        class="nav-row mt-0"
      >
        <b-tab title="店舗詳細"></b-tab>
        <b-tab title="写真"></b-tab>
        <b-tab title="口コミ"></b-tab>
        <b-tab title="地図"></b-tab>
      </b-tabs>

      <!-- 「店舗詳細」「写真」「口コミ」「地図」コンテンツ表示部 -->
      <router-view name="content" />
    </b-container>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";
import FavoriteBtn from "./../components/FavoriteBtn.vue";
import Reserve from "./../components/Reserve.vue";
import { mapGetters } from "vuex";

export default {
  name: "Detail",
  components: {
    Header,
    FavoriteBtn,
    Reserve,
  },
  data() {
    return {
      tab_index: null, // タブ番号
      shop: null, // 店舗情報
    };
  },
  props: {
    // 店舗ID
    shop_id: {
      type: String,
      required: true,
    },
    // タブ番号
    tab: {
      type: Number,
      required: true,
    },
  },
  computed: {
    ...mapGetters("App", ["getShop", "isLogin"]),

    // 利用可能なサービス一覧を表示メッセージに整形した配列を返却する
    services() {
      var messages = [];
      let message = {
        wifi: "WiFi",
        course: "コース",
        free_drink: "飲み放題",
        free_food: "食べ放題",
        private_room: "個室",
        card: "カード",
        non_smoking: "禁煙席",
        parking: "駐車場",
        pet: "ペット",
        lunch: "ランチ",
      };

      Object.keys(this.shop).forEach((key) => {
        switch (key) {
          // "あり"が表現に含まれるサービス
          case "wifi":
          case "course":
          case "free_drink":
          case "free_food":
          case "private_room":
          case "parking":
          case "lunch":
          case "non_smoking":
            if (this.shop[key].indexOf("あり") !== -1) {
              messages.push(message[key] + "あり");
            }
            break;
          // "可"が表現に含まれるサービス
          case "card":
          case "pet":
            if (
              this.shop[key].indexOf("可") !== -1 &&
              !(this.shop[key].indexOf("不可") >= 0)
            ) {
              messages.push(message[key] + "可");
            }
            break;
          // 利用可能なサービスではない場合何もしない
          default:
            break;
        }
      });

      return messages;
    },
  },
  // タブ押下時のページ遷移
  methods: {
    onChange(newTabIndex) {
      var path = "";

      switch (newTabIndex) {
        case 0:
          path += "info";
          break;
        case 1:
          path += "photoList";
          break;
        case 2:
          path += "review";
          break;
        case 3:
          path += "access";
          break;
        default:
          path += "/";
      }

      // 新しいtab番号に改めて設定
      this.tab_index = newTabIndex;
      // 新しいタブに対応するページへ遷移
      this.$router.replace({ name: path });
    },
  },
  created() {
    // 店舗情報を取得
    this.shop = this.getShop(this.shop_id) ?? null;

    // 初期表示するタブに対応するページを表示
    this.onChange(this.tab);
  },
};
</script>

<style scoped>
.container {
  background-color: #fff;
  min-height: 90vh;
}

.tabs ul {
  flex-wrap: nowrap;
}

.card-title {
  margin-bottom: 0.15rem;
}

.card-body small {
  color: #9b9b9b;
}

.card-text > span {
  font-size: smaller;
  margin-bottom: 0.2rem;
}

.tag-list {
  list-style: none;
}

.tag-list__item {
  float: left;
  margin: 0 5px 5px 0;
}

.tag-list__item-inner {
  display: block;
  padding: 0 10px;
  border: 1px solid #e5e5e5;
  border-radius: 22px;
  height: 24px;
  font-size: 12px;
  line-height: 20px;
  background-color: #fff;
  color: #333;
  text-decoration: none;
}

@media (max-width: 767.98px) {
  .card-img {
    max-width: 65%;
  }

  .tabs,
  .card-footer {
    font-size: 0.9rem;
  }
}
@media (min-width: 768px) {
  .card-img {
    height: 100%;
    width: 100%;
  }
}

.clear-float {
  clear: both;
}

.card-footer {
  margin-top: 3.5rem;
}

.card-footer span {
  font-size: smaller;
}

@media (max-width: 576px) {
  .container {
    padding-left: 0px;
    padding-right: 0px;
  }
}

.background {
  max-height: 265px;
  max-width: 265px;
}

.card-title {
  font-size: 1.3rem;
}
</style>
