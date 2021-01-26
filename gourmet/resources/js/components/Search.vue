<template>
  <div class="search">
    <div class="search-form">
      <b-container>
        <div class="wrapper py-4">
          <b-form inline @submit.prevent="getGourmet" class="flex-column">
            <div class="control d-flex flex-column flex-md-row flex-md-nowrap">
              <b-form-input
                class="keyword"
                placeholder="フリーワード検索（店名 地名、駅名など）"
                v-model="keyword"
              ></b-form-input>
              <!-- v-model="keyword" -->

              <b-button
                variant="light"
                @click.prevent="getGeo"
                class="buttons ml-md-4 text-nowrap"
                >現在地から検索</b-button
              >
              <b-form-select
                v-model="range"
                :options="options"
                @change="changeGeo"
                required
                v-show="geoActive"
                class="mt-3 mt-md-0 ml-md-2"
              ></b-form-select>
            </div>
            <div>
              <b-button type="submit" class="px-4 py-2 mt-md-5 mt-4">検索する</b-button>
            </div>
          </b-form>
        </div>
      </b-container>
    </div>
    <div class="spin" v-show="loading">
      <Loader height="10rem" width="10rem" label="Large Spinner" />
    </div>
  </div>
</template>

<script>
import { createNamespacedHelpers } from "vuex";
const { mapGetters, mapActions } = createNamespacedHelpers("App");
import Loader from "./../components/Loader.vue";

export default {
  name: "Search",
  components: {
    Loader
  },
  data() {
    return {
      keyword: "", // キーワード
      range: 3, //範囲
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
      geoActive: false, // 範囲表示
      loading: false
    };
  },

  computed: {
    ...mapGetters(["getURLs"])
  },

  methods: {
    ...mapActions(["updateShops"]),

    // 現在位置の取得
    getGeo: function() {
      // 成功時の処理
      const successCallback = position => {
        this.geoActive = true;

        // 範囲を初期化
        this.range = 3;

        (this.lat = position.coords.latitude), // 緯度
          (this.lon = position.coords.longitude); // 経度
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

    // 範囲変更
    changeGeo: function(selected) {
      // 範囲を検索条件に指定しない時
      if (selected === "0") {
        this.geoActive = false;
      } else {
        // 範囲を検索条件に指定した時
        this.geoActive = true;
      }
    },

    getGourmet: async function() {
      // ローダー表示フラグ
      this.loading = true;

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
          // ローダ非表示
          this.loading = false;
        });

      // ローダ非表示
      this.loading = false;
      // 検索結果画面へ
      this.$router.push("/result");
    }
  }
};
</script>

<style scoped>
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
  background: linear-gradient(transparent 60%, #d0d6dcb8 0%);
}
@media (max-width: 767.98px) {
  .buttons,
  .selects {
    margin-top: 1rem;
  }

  .control {
    width: 90%;
  }
}
@media (min-width: 768px) {
  .keyword {
    width: 400px !important;
  }
}

.spin {
  background: white;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100vw;
  position: absolute;
  top: 0;
  left: 0;
}
</style>
