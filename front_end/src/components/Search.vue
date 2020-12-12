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
              @input="onInput"
            ></b-form-input>
            <!-- v-model="form.keyword" -->

            <b-button
              variant="light"
              class="buttons ml-md-4 text-nowrap"
              @click.prevent="getGeo"
              >現在地から検索</b-button
            >
            <div class="selects ml-md-4" v-show="geoActive">
              <b-form-select
                v-model="form.range"
                :options="options"
                required
                @change="changeGeo"
              ></b-form-select>
            </div>
          </b-form>

          <!-- 検索結果表示領域 -->

          <div
            class="d-flex align-items-center justify-content-center flex-column flex-md-row mt-4"
          >
            <p class="search-conditions mb-0 mx-2 px-1" v-if="form.keyword">
              キーワード：『{{ form.keyword }}』
            </p>
            <p class="search-conditions mb-0 mx-2 px-1" v-if="form.range == 1">
              現在地から300m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1"
              v-else-if="form.range == 2"
            >
              現在地から500m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1"
              v-else-if="form.range == 4"
            >
              現在地から2000m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1"
              v-else-if="form.range == 5"
            >
              現在地から3000m圏内
            </p>
            <p
              class="search-conditions mb-0 mx-2 px-1"
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
    <Result></Result>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { mapActions } from 'vuex';
import { UPDATE_SHOPS } from '@/store/mutation-types';
import Result from '@/components/Result.vue';

export default {
  name: 'Search',
  components: {
    Result
  },
  data() {
    return {
      form: {
        keyword: '', // キーワード
        range: 3 //範囲
      },
      options: [
        { value: '0', text: '範囲を指定しない' },
        { value: '1', text: '300m圏内' },
        { value: '2', text: '500m圏内' },
        { value: '3', text: '1000m圏内' },
        { value: '4', text: '2000m圏内' },
        { value: '5', text: '3000m圏内' }
      ],
      lat: '', // 緯度
      lon: '', // 経度
      geoActive: false, //現在地が有効かどうか
      timer: null, // タイマー
      iniPosition: 0, // 検索フォーム初期位置
      curPosition: 0, // 検索フォーム現在位置
      message: 'キーワードを入力や、現在地を計測してお店を検索してください！' // message
    };
  },

  mounted: function() {
    // イベント登録
    window.addEventListener('scroll', this.onScroll);
    window.addEventListener('resize', this.onResize);
    // 検索フォームの初期位置を取得
    this.setIniPosition();
  },

  beforeDestroy: function() {
    // イベント解除
    window.removeEventListener('scroll', this.onScroll);
    window.removeEventListener('resize', this.onResize);
  },

  computed: {
    ...mapGetters(['getURLs']),

    // 検索フォームがウィンドウ内かを判定して返す
    isFix: function() {
      if (this.iniPosition < this.curPosition) return true;
      else return false;
    }
  },

  methods: {
    ...mapActions([UPDATE_SHOPS]),

    setIniPosition() {
      // 検索フォームの位置を取得
      const rect = document.querySelector('.search').getBoundingClientRect();
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
      clearTimeout(this.timer);

      this.timer = setTimeout(
        function() {
          // 遅れて表示に反映させたい為、v-modelは使わない
          this.form.keyword = value;
          this.geoActive
            ? this.getGourmet(this.keyword, this.lat, this.lon, this.form.range)
            : this.getGourmet(this.keyword);
        }.bind(this),
        500
      );
    },

    getGourmet: function() {
      const params = new URLSearchParams();
      if (this.form.keyword !== '') {
        params.append('keyword', this.form.keyword);
      }
      if (this.geoActive === true) {
        params.append('lat', this.lat);
        params.append('lon', this.lon);
        params.append('range', this.form.range);
      }
      // 検索条件が無い場合は問い合わせを実行しない
      if (params.toString() === '') {
        this[UPDATE_SHOPS](null);
        return;
      }

      const url =
        this.getURLs.baseURL +
        this.getURLs.prefix +
        this.getURLs.relativeURL.search;
      let $this = this;

      this.axios
        .post(url, params)
        .then(function(response) {
          // 成功時
          let result = response.data.results;

          const shops = result.shop.map(getted_shop => ({
            id: getted_shop.id, // お店ID
            name: getted_shop.name, // 掲載店名
            url: getted_shop.urls.pc, // 店舗url
            logo: getted_shop.logo_image, // 	ロゴ画像
            category: getted_shop.genre.name, // お店ジャンル名
            catch: getted_shop.genre.catch, // お店ジャンルキャッチ
            photo: getted_shop.photo.pc.l, // メイン画像
            address: getted_shop.address, // 住所
            map:
              'https://maps.google.co.jp/maps?q=' + encodeURI(getted_shop.name), // マップ
            access: getted_shop.mobile_access, // アクセス
            open: getted_shop.open, // 営業時間
            lunch: getted_shop.lunch, // ランチ
            card: getted_shop.card, // クレジットカード利用可能か不可か
            smoking: getted_shop.non_smoking, // 喫煙・禁煙
            wifi: getted_shop.wifi, // wifi
            parking: getted_shop.parking, // 駐車場
            average: getted_shop.budget.average, // 平均予算
            memo: getted_shop.budget_memo, // 料金備考
            coupon: getted_shop.coupon_urls.pc // クーポン
          }));

          // ストアへ更新
          $this[UPDATE_SHOPS](shops);
        })
        .catch(function(error) {
          // 失敗時
          $this.getting = true;
          $this.message = error;
        });
    },
    // 現在位置の取得
    getGeo: function() {
      // 成功時の処理
      const successCallback = position => {
        // 範囲を初期化
        this.form.range = 3;
        this.geoActive = true;

        (this.lat = position.coords.latitude), // 緯度
          (this.lon = position.coords.longitude); // 経度

        this.getGourmet(this.keyword, this.lat, this.lon, this.form.range);
      };
      // 失敗時の処理
      const errorCallback = error => {
        this.geoActive = false;

        if (error.code == 1) {
          alert('位置情報取得が許可されていません。');
        } else if (error.code == 2) {
          alert('位置情報取得に失敗しました。');
        } else {
          alert('タイムアウトしました。');
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
        alert('この端末では位置情報取得ができません。');
      }
    },
    changeGeo: function(selected) {
      if (selected === '0') {
        this.geoActive = false;
        this.getGourmet(this.keyword);
      } else {
        this.geoActive = true;
        this.getGourmet(this.keyword, this.lat, this.lon, this.form.range);
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
