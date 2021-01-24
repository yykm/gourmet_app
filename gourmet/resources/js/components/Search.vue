<template>
  <div class="search">
    <div class="search-form" :class="{ 'is-fix': isFix }">
      <b-container>
        <div class="wrapper py-4">
          <b-form
            inline
            class="justify-content-center flex-column flex-md-row flex-md-nowrap"
          >
            <b-form-input
              class="keyword"
              placeholder="フリーワード検索（店名 地名、駅名など）"
              v-model="keyword"
            ></b-form-input>
            <!-- v-model="keyword" -->

            <b-button
              variant="light"
              class="buttons ml-md-4 text-nowrap"
              @click.prevent="getGeo"
              >現在地から検索</b-button
            >
            <div class="selects ml-md-4" v-show="geoActive">
              <b-form-select
                v-model="range"
                :options="options"
                required
                @change="changeGeo"
              ></b-form-select>
            </div>
          </b-form>

          <!-- 検索結果表示領域 -->

          <div
            class="d-flex align-items-center justify-content-center flex-column flex-md-row"
          >
            <p class="search-conditions mb-0 mx-2 px-1 mt-3" v-if="keyword">
              キーワード：『{{ keyword }}』
            </p>
            <p class="search-conditions mb-0 mx-2 px-1 mt-3" v-if="range == 1">
              現在地から300m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1 mt-3"
              v-else-if="range == 2"
            >
              現在地から500m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1 mt-3"
              v-else-if="range == 4"
            >
              現在地から2000m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1 mt-3"
              v-else-if="range == 5"
            >
              現在地から3000m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1 mt-3"
              v-show="geoActive"
              v-else
            >
              現在地から1000m圏内
            </p>
          </div>
        </div>
      </b-container>
    </div>
    <!-- 検索結果表示領域 -->
    <Result :loading="loading"></Result>
  </div>
</template>

<script>
import { createNamespacedHelpers } from "vuex";
const { mapGetters, mapActions } = createNamespacedHelpers("App");
import Result from "./../components/Result.vue";
import _ from "lodash";

export default {
  name: "Search",
  components: {
    Result
  },
  data() {
    return {
      keyword: "", // キーワード
      range: 3, //範囲
      loading: false,
      options: [
        { value: "0", text: "範囲を指定しない" },
        { value: "1", text: "300m圏内" },
        { value: "2", text: "500m圏内" },
        { value: "3", text: "1000m圏内" },
        { value: "4", text: "2000m圏内" },
        { value: "5", text: "3000m圏内" }
      ],
      lat: "", // 緯度
      lon: "", // 経度
      geoActive: false, //現在地が有効かどうか
      timer: null, // タイマー
      iniPosition: 0, // 検索フォーム初期位置
      curPosition: 0, // 検索フォーム現在位置
      message: "キーワードを入力や、現在地を計測してお店を検索してください！" // message
    };
  },

  watch: {
    keyword: function(keyWord) {
      this.loading = true;
      this.delayFunc(keyWord);
    },
    range: function() {
      this.loading = true;
    }
  },

  mounted: function() {
    // イベント登録
    window.addEventListener("scroll", this.onScroll);
    window.addEventListener("resize", this.onResize);
    // 検索フォームの初期位置を取得
    this.setIniPosition();
  },

  created() {
    this.delayFunc = _.debounce(this.onInput, 800);
  },

  beforeDestroy: function() {
    // イベント解除
    window.removeEventListener("scroll", this.onScroll);
    window.removeEventListener("resize", this.onResize);
  },

  computed: {
    ...mapGetters(["getURLs"]),

    // 検索フォームがウィンドウ内かを判定して返す
    isFix: function() {
      if (this.iniPosition < this.curPosition) return true;
      else return false;
    }
  },

  methods: {
    ...mapActions(["updateShops"]),

    setIniPosition() {
      // 検索フォームの位置を取得
      const rect = document.querySelector(".search").getBoundingClientRect();
      this.iniPosition = window.pageYOffset + rect.top;
    },
    // 検索フォームの現在位置を取得
    onScroll: function() {
      this.curPosition = window.pageYOffset;
    },
    // ウィンドウのサイズを変える度に検索フォームの初期位置を取得
    onResize: function() {
      clearTimeout(this.timer);

      this.timer = setTimeout(
        function() {
          this.setIniPosition();
        }.bind(this),
        50
      );
    },
    // 検索処理
    onInput: function(value) {
      this.geoActive
        ? this.getGourmet(this.keyword, this.lat, this.lon, this.range)
        : this.getGourmet(this.keyword);
    },

    getGourmet: async function() {
      const params = new URLSearchParams();
      if (this.keyword !== "") {
        params.append("keyword", this.keyword);
      }
      if (this.geoActive === true) {
        params.append("lat", this.lat);
        params.append("lon", this.lon);
        params.append("range", this.range);
      }
      // 検索条件が無い場合は問い合わせを実行しない
      if (params.toString() === "") {
        this.updateShops(null);
        // ローダ非表示
        this.loading = false;
        return;
      }

      const url = this.getURLs("search");
      let $this = this;

      await axios
        .post(url, params)
        .then(function(response) {
          // 成功時
          let result = response.data.results;

          const shops = result.shop.map(getted_shop => ({
            id: getted_shop.id, // お店ID
            name: getted_shop.name, // 掲載店名
            kana: getted_shop.name_kana, // 店名かな
            url: getted_shop.urls.pc, // 店舗url
            logo: getted_shop.logo_image, // 	ロゴ画像
            image_l: getted_shop.photo.pc.l, // 	店舗トップ写真(小）画像URL
            category: getted_shop.genre.name, // お店ジャンル名
            catch: getted_shop.genre.catch, // お店ジャンルキャッチ
            feat: getted_shop.catch, // お店キャッチ
            photo: getted_shop.photo.pc.l, // メイン画像
            address: getted_shop.address, // 住所
            station: getted_shop.station_name, // 最寄り駅
            map:
              "https://maps.google.co.jp/maps?q=" + encodeURI(getted_shop.name), // マップ
            access: getted_shop.mobile_access, // アクセス
            open: getted_shop.open, // 営業時間
            close: getted_shop.close,
            card: getted_shop.card, // クレジットカード利用可能か不可か
            smoking: getted_shop.non_smoking, // 喫煙・禁煙
            parking: getted_shop.parking, // 駐車場
            average: getted_shop.budget.average, // 平均予算
            memo: getted_shop.budget_memo, // 料金備考
            // サービス有無
            wifi: getted_shop.wifi, // wifi
            cource: getted_shop.cource, // コース有無
            free_drink: getted_shop.free_drink, // 飲み放題
            free_food: getted_shop.free_food, // 食べ放題
            private_room: getted_shop.private_room, // 個室
            card: getted_shop.card, // カード利用
            non_smoking: getted_shop.non_smoking, // 禁煙席
            parking: getted_shop.parking, // 駐車場
            pet: getted_shop.pet, // ペット連れ込み
            lunch: getted_shop.lunch // ランチ
          }));

          // ストアへ更新
          $this.updateShops(shops);
        })
        .catch(function(error) {
          // 失敗時
          $this.message = error;
        });

      // ローダ非表示
      this.loading = false;
    },
    // 現在位置の取得
    getGeo: function() {
      // 成功時の処理
      const successCallback = position => {
        // 範囲を初期化
        this.range = 3;
        this.geoActive = true;

        (this.lat = position.coords.latitude), // 緯度
          (this.lon = position.coords.longitude); // 経度

        this.getGourmet(this.keyword, this.lat, this.lon, this.range);
      };
      // 失敗時の処理
      const errorCallback = error => {
        this.geoActive = false;

        if (error.code == 1) {
          alert("位置情報取得が許可されていません。");
        } else if (error.code == 2) {
          alert("位置情報取得に失敗しました。");
        } else {
          alert("タイムアウトしました。");
        }
      };

      /* Geolocation APIを利用できる環境向けの処理 */
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          successCallback,
          errorCallback
        );
      } else {
        this.geoActive = false;

        /* Geolocation APIを利用できない環境向けの処理 */
        alert("この端末では位置情報取得ができません。");
      }
    },
    changeGeo: function(selected) {
      if (selected === "0") {
        this.geoActive = false;
        this.getGourmet(this.keyword);
      } else {
        this.geoActive = true;
        this.getGourmet(this.keyword, this.lat, this.lon, this.range);
      }
    }
  }
};
</script>

<style scoped>
@media (min-width: 768px) {
  .is-fix {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    margin: auto;
    z-index: 1000;
  }
}
.search-form {
  /* background-color: rgb(245 246 249 / 80%); */
  background-color: rgb(255, 255, 255, 0.9);
  box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2);
}
.buttons {
  color: rgb(134 135 136);
  padding-left: 1.9em;
  background-image: url(/img/icon/icn_pin.svg);
  background-repeat: no-repeat;
  background-position: 0.6em center;
  background-size: 1.1em;
  line-height: 1.5;
  border: 1px solid #ced4da;
  background-color: white;
}
.search-conditions {
  background: linear-gradient(transparent 60%, #ddeeff 0%);
}
@media (max-width: 767.98px) {
  .keyword {
    width: 90% !important;
  }
  .buttons,
  .selects {
    margin-top: 1rem;
  }
}
@media (min-width: 768px) {
  .keyword {
    width: 400px !important;
  }
}
</style>
