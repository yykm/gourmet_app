<template>
  <!-- お気に入り履歴 -->
  <div id="myPageFavorite">
    <div class="wrapper p-3">
      <!-- タイトル -->
      <h2 class="mb-3 title">お気に入りのお店</h2>
      <div v-if="favorites.length" class="wrapper pb-2">
        <!-- お気に入り店舗一覧表示 -->
        <carousel :per-page="getPerPage" :navigation-enabled="true">
          <slide v-for="(favorite, i) in favorites" :key="i">
            <div class="items px-2">
              <b-card
                no-body
                :img-src="favorite.shop.image"
                :img-alt="favorite.shop.name"
                img-top
                tag="article"
                class="mb-2"
              >
                <b-card-body>
                  <b-card-title>{{ favorite.shop.name }}</b-card-title>
                  <b-card-text>
                    <span class="mt-2">{{ favorite.shop.access }}</span
                    ><br />
                    <span class="mt-2">{{ favorite.shop.catch }}</span
                    ><br />
                  </b-card-text>
                </b-card-body>
              </b-card>
            </div>
          </slide>
        </carousel>
      </div>

      <!-- お気に入り登録が一つもない場合 -->
      <div v-else class="nothing">
        <p>登録したお気に入りのお店はありません</p>
      </div>
    </div>
  </div>
</template>

<script>
import { Carousel, Slide } from "vue-carousel";
import _ from "lodash";

export default {
  name: "MypageFavorite",
  data() {
    return {
      size: 0,
    };
  },
  components: {
    Carousel,
    Slide,
  },
  props: {
    // お気に入り情報
    favorites: {
      type: Array,
      required: false,
    },
  },
  computed: {
    // 画面サイズごとにカルーセルの設定枚数を返却
    getPerPage() {
      // 600px,1250,1600,それ以上が閾値
      if (this.size < 600) {
        return 1;
      } else if (this.size < 1250) {
        return 2;
      } else if (this.size < 1600) {
        return 3;
      } else {
        return 4;
      }
    },
  },
  methods: {
    // ウィンドウの横幅を取得
    getWindowSize() {
      this.size = window.innerWidth;
    },
  },
  created() {
    // 初期値設定
    this.getWindowSize();

    // 4000ms以上リサイズイベントの感覚が空いたときにウィンドウの横幅を取得
    this.delayFunc = _.debounce(this.getWindowSize, 200);
    window.addEventListener("resize", this.delayFunc);
  },
};
</script>

<style scoped>
.title {
  font-size: 1.5rem;
}

.card-title {
  color: #1e4888;
  margin-bottom: 1rem !important;
}

.card img {
  max-width: 100%;
  height: 250px;
}

.card,
.items {
  height: 100%;
}

/deep/ .VueCarousel-navigation--disabled {
  display: none;
}
</style>
