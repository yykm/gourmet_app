<template>
  <div class="search" :class="{ 'is-fix': isFix }">
    <b-container>
      <div class="wrapper py-4">
        <b-form inline class="justify-content-center flex-column flex-md-row">
          <b-form-input
            class="keyword"
            placeholder="フリーワード検索（店名 地名、駅名など）"
            v-model="form.keyword"
            @input="onInput"
          ></b-form-input>

          <b-button variant="primary" class="buttons ml-md-3">Save</b-button>
        </b-form>
      </div>
    </b-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { mapActions } from 'vuex';
import { UPDATE_SHOPS } from '@/store/mutation-types';

export default {
  name: 'Search',
  data() {
    return {
      form: {
        keyword: '' // キーワード
      },
      getting: true, // 取得中アイコン
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
      if (this.curPosition > this.iniPosition) return true;
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
      if (this.timer === null) {
        this.timer = setTimeout(
          function() {
            this.curPosition = window.pageYOffset;
            clearTimeout(this.timer);
            this.timer = null;
          }.bind(this),
          50
        );
      }
    },
    // ウィンドウのサイズを変える度に検索フォームの初期位置を取得
    onResize: function() {
      this.setIniPosition();
      clearTimeout(this.timer);
      this.timer = null;
    },
    // 検索処理
    onInput: function() {
      this.getting = false;

      if (this.timer === null) {
        this.timer = setTimeout(
          function() {
            this.getGourmet(this.keyword, this.lat, this.lon, this.range);

            clearTimeout(this.timer);
            this.timer = null;
          }.bind(this),
          500
        );
      }
    },

    getGourmet: function() {
      this.getting = false;

      const params = new URLSearchParams();
      params.append('keyword', this.form.keyword);
      // params.append('lat', this.lat);
      // params.append('lon', this.lon);
      // params.append('range', this.range);
      const url = this.getURLs.baseURL + this.getURLs.relativeURL.search;
      let $this = this;

      this.axios
        .post(url, params)
        .then(function(response) {
          // 成功時
          $this.getting = true;
          let result = response.data.results;

          const shops = result.shop.map(getted_shop => ({
            id: getted_shop.id, // お店ID
            name: getted_shop.name, // 店名
            url: getted_shop.urls.pc, // 店舗url
            logo: getted_shop.logo_image, // ロゴ
            category: getted_shop.genre.name, // カテゴリ
            catch: getted_shop.genre.catch, // キャッチコピー
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
    }
  }
};
</script>

<style scoped>
.is-fix {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  margin: auto;
  z-index: 1000;
}
.search {
  background-color: white;
  box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2);
}

@media (max-width: 767.98px) {
  .keyword {
    width: 90% !important;
  }
  .buttons {
    margin-top: 0.75rem;
  }
}
@media (min-width: 768px) {
  .keyword {
    width: 400px !important;
  }
}
</style>
