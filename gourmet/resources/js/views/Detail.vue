<template>
  <div id="detail">
    <Header :site_name="'Gourmet'"></Header>
    <b-container v-if="shop" class="shadow-sm py-1 mt-4">
      <!-- 店舗トップ写真 -->
      <b-card no-body class="overflow-hidden mt-4 w-100 border-0 ">
        <b-row no-gutters align-h="between">
          <b-col
            sm="12"
            md="6"
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

          <!-- 店舗概要 -->
          <b-col sm="12" md="6" lg="8">
            <b-card-body>
              <div class="title__wrapper">
                <div class="title__inner d-flex flex-column-reverse flex-md-row align-items-center align-items-md-start justify-content-between">
                  <b-card-title>{{ shop.name }}</b-card-title>
                  <FavoriteBtn :shop="shop" class="flex-shrink-0 ml-md-2 mb-3 mb-md-0" />
                </div>
                <small class="mb-2">{{ shop.kana }}</small>
              </div>
              <b-card-text class="mt-2">
                <!-- アクセス -->
                <span>アクセス：{{ shop.access }}</span
                ><br />
                <!-- 平均予算 -->
                <span>平均予算：{{ shop.average }}</span>
              </b-card-text>

              <!-- 利用可能サービス -->
              <ul class="tag-list px-0">
                <li
                  v-for="(message, i) in services"
                  :key="i"
                  class="tag-list__item"
                >
                  <span class="tag-list__item-inner">{{ message }}</span>
                </li>
              </ul>

              <b-card-footer class="clear-float reservation py-2">
                <span>営業時間：{{ shop.open }}</span
                ><br />
                <span>定休日：{{ shop.close }}</span
                ><br />
                <div class="text-center my-2">
                  <Reserve :shop="shop" />
                </div>
              </b-card-footer>
            </b-card-body>
          </b-col>
        </b-row>
      </b-card>

      <!-- タブ -->
      <b-tabs
        v-model="tabIndex"
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

      <!-- コンテンツ -->
      <router-view name="content"></router-view>
    </b-container>
    <div v-else class="text-center mt-4 text-danger">
      <span>該当のお店は存在しません。</span>
    </div>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";
import FavoriteBtn from "./../components/FavoriteBtn.vue";
import Reserve from "./../components/Reserve.vue";
import { mapGetters } from "vuex";

export default {
  name: "Detail",
  data() {
    return {
      tabIndex: Number(this.tab),
      shop: null,
    };
  },
  components: {
    Header,
    FavoriteBtn,
    Reserve,
  },
  computed: {
    ...mapGetters("App", ["getShop"]),

    // 各種サービスの有無
    services() {
      // 使用可能なサービスのメッセージ配列
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
  props: {
    // 店舗ID
    id: {
      type: String,
      required: true,
    },
    // タブ番号
    tab: {
      type: Number,
      required: true,
    },
  },
  // リンク移動
  methods: {
    onChange(newTabIndex, prevTabIndex, bvEvent) {
      // 移動先
      var prefix = ["", "detail", this.id, newTabIndex].join("/");

      switch (newTabIndex) {
        case 0:
          prefix += "/info";
          break;
        case 1:
          prefix += "/photoList";
          break;
        case 2:
          prefix += "/review";
          break;
        case 3:
          prefix += "/access";
          break;
        default:
          prefix += "/";
      }

      this.$router.push(prefix);
    },
  },
  created() {
    this.shop = this.getShop(this.id) ?? null;
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
  max-height: 280px;
  max-width: 280px;
}

.bg-wrapper {
  padding: 1.25rem;
}
</style>
