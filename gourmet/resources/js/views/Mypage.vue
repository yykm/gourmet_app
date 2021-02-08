<template>
  <div id="mypage">
    <Header />
    <b-container fluid id="wrapper" class="mt-4">
      <b-row
        no-gutters
        class="content__wrapper justify-content-around flex-column flex-lg-row"
      >
        <b-col cols="10" lg="4" xl="3" class="bg__white shadow-sm mb-5 mb-ml-0"
          ><aside class="account__info">
            <MypageAccount @componentShow="componentShow" :user="user" /></aside
        ></b-col>
        <b-col
          cols="10"
          lg="7"
          xl="8"
          class="d-flex flex-column justify-content-between"
        >
          <b-row no-gutters v-if="flag === 'reservation'">
            <b-col cols="12" class="reserve__info bg__white shadow-sm mb-5"
              >予約<br />{{ reservations }}</b-col
            >
          </b-row>
          <b-row no-gutters v-else-if="flag === 'favorite'">
            <b-col cols="12" class="favorite__info bg__white shadow-sm mb-5">
              <MypageFavorite :favorites="favorites" />
            </b-col>
          </b-row>
          <b-row no-gutters v-else-if="flag === 'photo'">
            <b-col cols="12" class="photo__info bg__white shadow-sm mb-5">
              <MypagePhoto :photos="photos" />
            </b-col>
          </b-row>
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
  },
  data() {
    return {
      user: null,
      reservations: null,
      favorites: null,
      comments: null,
      photos: null,
      flag: null,
    };
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),

    // 表示コンポーネント切り替え
    componentShow(flag) {
      this.flag = flag;
    },
    async fetchInfo(path) {
      // 問い合わせのURL作成
      let url = ["", "api", path, "byUser"].join("/");
      // 店舗idをクエリパラメータに
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

      console.log(response);
      // 取得結果がfalsyな値であればnullを返却
      return response.data ?? null;
    },
    // 予約情報
    async fetchReseveInfo() {
      this.reservations = await this.fetchInfo("reserve");
    },
    // お気に入り情報
    async fetchFavoriteInfo() {
      this.favorites = await this.fetchInfo("favorites");
    },
    // レビュー情報
    async fetchReviewInfo() {
      this.comments = await this.fetchInfo("comments");
    },
    // 写真情報
    async fetchPhotoInfo() {
      this.photos = await this.fetchInfo("photos");
    },
  },
  created() {
    // ユーザ情報
    this.user = this.$store.getters["App/getUser"];
    // 予約情報
    this.fetchReseveInfo();
    // お気に入り情報
    this.fetchFavoriteInfo();
    // 口コミ情報
    this.fetchReviewInfo();
    // 写真
    this.fetchPhotoInfo();
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
</style>
