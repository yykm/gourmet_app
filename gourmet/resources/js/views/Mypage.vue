<template>
  <!-- マイページ画面 -->
  <div id="mypage">
    <!-- ヘッダー -->
    <Header />

    <b-container fluid id="wrapper" class="mt-4">
      <b-row
        no-gutters
        class="content__wrapper justify-content-around flex-column flex-lg-row"
      >
        <!-- サイドメニュー -->
        <b-col
          cols="10"
          md="8"
          lg="4"
          xl="3"
          class="bg__white shadow-sm mb-sm-5 mb-ml-0"
        >
          <aside class="account__info">
            <MypageAccount @componentShow="componentShow" :user="user" /></aside
        ></b-col>

        <!-- コンテンツ切り替え表示部 -->
        <b-col
          cols="10"
          md="8"
          lg="7"
          xl="8"
          class="d-flex flex-column justify-content-between"
        >
          <!-- 予約履歴 -->
          <b-row no-gutters v-if="flag === 'reservation'">
            <b-col cols="12" class="reserve__info bg__white shadow-sm mb-5"
              ><MypageReservation :reservations="reservations"
            /></b-col>
          </b-row>

          <!-- お気に入りのお店 -->
          <b-row no-gutters v-else-if="flag === 'favorite'">
            <b-col cols="12" class="favorite__info bg__white shadow-sm mb-5">
              <MypageFavorite :favorites="favorites" />
            </b-col>
          </b-row>

          <!-- 投稿した写真 -->
          <b-row no-gutters v-else-if="flag === 'photo'">
            <b-col cols="12" class="photo__info bg__white shadow-sm mb-5">
              <MypagePhoto :photos="photos" />
            </b-col>
          </b-row>

          <!-- 投稿したレビュー -->
          <b-row no-gutters v-else-if="flag === 'comment'">
            <b-col cols="12" class="review__info bg__white shadow-sm mb-5"
              ><MypageComment :comments="comments"
            /></b-col>
          </b-row>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";
import MypageAccount from "./../components/MypageAccount.vue";
import MypagePhoto from "./../components/MypagePhoto.vue";
import MypageComment from "./../components/MypageComment.vue";
import MypageFavorite from "./../components/MypageFavorite.vue";
import MypageReservation from "./../components/MypageReservation.vue";
import { mapMutations } from "vuex";
import { ERR } from "./../store/const.js";

export default {
  name: "Mypage",
  components: {
    Header,
    MypageAccount,
    MypagePhoto,
    MypageComment,
    MypageFavorite,
    MypageReservation,
  },
  data() {
    return {
      user: null, // ユーザ情報
      reservations: null, // 予約情報
      favorites: null, // お気に入り情報
      comments: null, // 口コミ情報
      photos: null, // 写真情報
      flag: null, // コンテンツ表示切替フラグ
    };
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),

    // 表示コンテンツ切り替え
    componentShow(flag) {
      this.flag = flag;
    },

    // 「予約」「予約」「お気に入り」「写真」いずれかの情報を取得する共通処理
    async fetchInfo(path) {
      // 問い合わせ用URL作成
      let url = ["", "api", path, "byUser"].join("/");
      // ユーザIDをキーにして各種情報を取得するため、クエリパラメータに設定
      const response = await axios
        .get(url, {
          params: {
            user_id: this.user.id,
          },
        })
        .catch((err) => err.response || err);

      // ステ－タスコード200以外エラー
      if (response.status !== ERR.OK) {
        this.setCode("setCode", response.status);
        return;
      }

      // 取得結果がfalsyな値であればnullを返却
      return response.data ?? null;
    },

    // 予約情報取得処理
    async fetchReseveInfo() {
      this.reservations = await this.fetchInfo("reserve");

      // 初期表示項目の切り替え
      if (this.tab === "reservation") {
        this.componentShow("reservation");
      }
    },

    // お気に入り情報取得処理
    async fetchFavoriteInfo() {
      this.favorites = await this.fetchInfo("favorites");

      // 初期表示項目の切り替え
      if (this.tab === "favorite") {
        this.componentShow("favorite");
      }
    },

    // レビュー情報取得処理
    async fetchReviewInfo() {
      this.comments = await this.fetchInfo("comments");

      // 初期表示項目の切り替え
      if (this.tab === "comment") {
        this.componentShow("comment");
      }
    },

    // 写真情報取得処理
    async fetchPhotoInfo() {
      this.photos = await this.fetchInfo("photos");

      // 初期表示項目の切り替え
      if (this.tab === "photo") {
        this.componentShow("photo");
      }
    },
  },
  created() {
    // ユーザ情報を初期設定
    this.user = this.$store.getters["App/getUser"];
    // 予約情報取得
    this.fetchReseveInfo();
    // お気に入り情報取得
    this.fetchFavoriteInfo();
    // 口コミ情報取得
    this.fetchReviewInfo();
    // 写真情報取得
    this.fetchPhotoInfo();
  },
  props: {
    // 他ページより遷移した場合に、初期表示コンテンツ切り替え用
    tab: {
      type: String,
      required: false,
    },
  },
};
</script>

<style scoped>
.bg__white {
  background-color: #fff;
}

@media (max-width: 991.98px) {
  .content__wrapper {
    align-items: center;
  }
}

@media (max-width: 575.98px) {
  .container-fluid {
    padding: 0px;
  }

  .col-10 {
    width: 100%;
    max-width: 100%;
  }

  /deep/ .account__info .card-body {
    border-bottom-color: rgba(0, 0, 0, 0.125);
    border-bottom-style: solid;
    border-bottom-width: 1px;
  }
}
</style>
