<template>
  <!-- 検索フォーム及び検索結果表示領域 -->
  <div id="research">
    <!-- ヘッダー -->
    <Header />

    <div class="research-form" :class="{ 'is-fix': isFix }">
      <b-container>
        <div class="pt-4 pb-3 pb-md-4">
          <b-form
            inline
            class="justify-content-center flex-column flex-md-row flex-md-nowrap"
          >
            <!-- フリーワード入力 -->
            <b-form-input
              class="keyword"
              placeholder="フリーワード検索（店名 地名、駅名など）"
              v-model="keyword"
            ></b-form-input>

            <!-- 現在地からの範囲入力 -->
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

          <!-- 検索条件表示領域 -->
          <div
            class="d-flex align-items-center justify-content-center flex-column flex-md-row"
          >
            <p class="research-conditions mb-0 mx-2 px-1 mt-3" v-if="keyword">
              キーワード：『{{ keyword }}』
            </p>
            <p
              class="research-conditions mb-0 mx-2 px-1 mt-3"
              v-show="geoActive"
            >
              現在地から{{ options[range].text }}
            </p>
          </div>
        </div>
      </b-container>

      <!-- クレジット表示 -->
      <div class="credit text-center pb-2 text-md-right mr-md-2">
        Powered by
        <a href="http://webservice.recruit.co.jp/"
          >ホットペッパー Webサービス</a
        >
      </div>
    </div>

    <!-- 検索結果表示領域 -->
    <ShopList :loading="loading" />
  </div>
</template>

<script>
import { mapActions, mapMutations } from "vuex";
import ShopList from "./../components/ShopList.vue";
import Header from "./../components/Header.vue";
import _ from "lodash";

export default {
  name: "Search",
  components: {
    ShopList,
    Header,
  },
  data() {
    return {
      keyword: "", // キーワード
      range: 3, // 範囲
      // 現在地からの範囲指定の選択ボックス入力オプション
      options: [
        { value: "0", text: "範囲を指定しない" },
        { value: "1", text: "300m圏内" },
        { value: "2", text: "500m圏内" },
        { value: "3", text: "1000m圏内" },
        { value: "4", text: "2000m圏内" },
        { value: "5", text: "3000m圏内" },
      ],
      lat: "", // 緯度
      lon: "", // 経度
      geoActive: false, // 範囲検索が有効か否か
      iniPosition: 0, // 検索フォーム初期位置
      scrollY: 0, // Y方向のスクロール量
      loading: false, // ローダー表示フラグ
    };
  },

  watch: {
    // フリーワード入力ごとに遅延関数に登録された検索処理を発火
    keyword: function () {
      this.loading = true;
      this.delayFunc();
    },
  },

  computed: {
    // 下へスクロールした際に、検索フォームをウィンドウ上部へ固定するスタイルクラスの付与フラグ
    isFix: function () {
      if (this.iniPosition < this.scrollY) return true;
      else return false;
    },
  },

  mounted() {
    // 入力イベントの度に発火される連続する検索処理をキャンセルし、
    // 800ms間隔が空いた時に処理するよう遅延関数に登録する
    this.delayFunc = _.debounce(this.getGourmet, 800);

    // スクロール、リサイズイベントのハンドラ登録
    window.addEventListener("scroll", this.onScroll);
    window.addEventListener("resize", this.onResize);
  },

  methods: {
    ...mapActions("App", ["updateShops"]),
    ...mapMutations("Message", ["setContent"]),
    ...mapMutations("Err", ["setCode"]),

    // ウィンドウのリサイズの度に検索フォームの初期位置を再設定
    onResize() {
      const rect = document.querySelector("#research").getBoundingClientRect();
      this.iniPosition = window.pageYOffset + rect.top;
    },

    // Y方向のスクロール量を取得
    onScroll() {
      this.scrollY = window.pageYOffset;
    },

    // 検索処理本体
    async getGourmet() {
      const params = new URLSearchParams();

      // キーワードが入力されている場合条件に含める
      if (this.keyword !== "") {
        params.append("keyword", this.keyword);
      }
      // 範囲検索が有効な場合条件に含める
      if (this.geoActive === true) {
        params.append("lat", this.lat);
        params.append("lon", this.lon);
        params.append("range", this.range);
      }
      // いずれの検索条件も指定されていない場合は問い合わせを実行しない
      if (params.toString() === "") {
        this.updateShops(null);
        this.loading = false;
        return;
      }

      await axios
        .post("/api/search", params)
        .then((response) => {
          // 成功時
          let result = response.data.results;

          const shops = result.shop.map((getted_shop) => ({
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
            lat: getted_shop.lat, // 緯度
            lng: getted_shop.lng, // 軽度
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
            lunch: getted_shop.lunch, // ランチ
          }));

          // ストアへ検索情報を保存
          this.updateShops(shops);
        })
        .catch((error) => {
          // 失敗メッセージ表示
          this.setContent({
            success: false,
            content: error.response.data,
            timeout: 5000,
          });

          // ステータスコードをせってお
          this.setCode(error.response.status);
        });

      this.loading = false;
    },

    // 現在位置の取得
    getGeo() {
      // 成功時の処理
      const successCallback = (position) => {
        this.loading = true;
        // 範囲を初期化
        this.range = 3;
        // 範囲検索有効化
        this.geoActive = true;

        (this.lat = position.coords.latitude), // 緯度取得
          (this.lon = position.coords.longitude); // 経度取得

        this.getGourmet();
      };
      // 失敗時の処理
      const errorCallback = (error) => {
        // 範囲検索無効化
        this.geoActive = false;

        // 失敗事由を通知
        if (error.code == 1) {
          alert("位置情報取得が許可されていません。");
        } else if (error.code == 2) {
          alert("位置情報取得に失敗しました。");
        } else {
          alert("タイムアウトしました。");
        }
      };

      /* Geolocation APIを利用できる環境の場合 */
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          successCallback,
          errorCallback
        );
      } else {
        /* 利用できない環境の場合 */
        this.geoActive = false;
        alert("この端末では位置情報取得ができません。");
      }
    },

    // 範囲検索の範囲が変更された場合
    changeGeo(selected) {
      this.loading = true;

      // 範囲を指定しない、へ変更した場合
      if (selected === "0") {
        this.geoActive = false;
      } else {
        // 指定範囲を変更した場合
        this.geoActive = true;
      }

      this.getGourmet();
    },
  },

  // 後処理としてイベント解除
  beforeDestroy: function () {
    window.removeEventListener("scroll", this.onScroll);
    window.removeEventListener("resize", this.onResize);
  },
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
.research-form {
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
.research-conditions {
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

.credit {
  font-size: 0.85rem;
}
</style>
