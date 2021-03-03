<template>
  <!-- 写真一覧画面 -->
  <div id="photoList">
    <div class="wrapper mt-5 mb-4">
      <b-container fluid>
        <!-- 写真の投稿が１つもない場合にメッセージ表示 -->
        <div v-if="!photos" class="text-center no-photos">
          <p>まだ投稿された写真がありません。</p>
        </div>

        <!-- 写真投稿フォーム -->
        <b-row>
          <b-col>
            <div class="photo__form text-center mb-2">
              <PhotoForm @photoPost="onPost" :shop_id="shop_id" /></div
          ></b-col>
        </b-row>

        <!-- API通信途中に表示するローダー -->
        <div v-if="loading" class="loader text-center mt-5">
          <Loader height="5rem" width="5rem" />
        </div>

        <div v-else-if="photos" class="photos py-4 mx-auto">
          <!-- 写真一覧表示部 -->
          <b-row class="mb-5">
            <b-col
              cols="6"
              md="4"
              lg="3"
              class="photos__wrapper"
              v-for="photo in photos"
              :key="photo.id"
              ><Photo :photo="photo"
            /></b-col>
          </b-row>

          <!-- ペジネーションリンク -->
          <Pagination :last-page="lastPage" />
        </div>
      </b-container>
    </div>
  </div>
</template>

<script>
import Photo from "./../components/Photo.vue";
import Loader from "./../components/Loader.vue";
import PhotoForm from "./../components/PhotoForm.vue";
import Pagination from "./../components/Pagination.vue";
import { mapMutations, mapGetters } from "vuex";
import { STATUS } from "./../util.js";

export default {
  name: "PhotoList",
  components: {
    Photo,
    PhotoForm,
    Loader,
    Pagination,
  },
  computed: {
    ...mapGetters("App", ["isLogin"]),
  },
  data() {
    return {
      shop_id: this.$route.params.shop_id, // 店舗ID
      photos: null, // 写真情報（オブジェクト）の配列
      loading: null, // ローダー表示フラグ
      lastPage: 1, // ページングの最終ページ番号
    };
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),

    // 写真情報取得処理
    async fetchPhotos() {
      // ローダー表示
      this.loading = true;
      // クエリパラメータのページ番号を取得
      const page = Number(
        /^[1-9][0-9]*$/.test(this.$route.query.page)
          ? this.$route.query.page * 1
          : 1
      );

      // 店舗IDとページ番号を元に対応する写真情報を取得
      const response = await axios
        .get("/api/photos", {
          params: {
            shop_id: this.shop_id,
            page,
          },
        })
        .catch((err) => err.response || err);

      // ローダー非表示
      this.loading = false;

      // ステ－タスコード200以外エラー
      if (response.status !== STATUS.OK) {
        this.setCode(response.status);

        return;
      }

      // 写真情報を取得
      this.photos = response.data.data.length === 0 ? null : response.data.data;
      // ページング最終ページ番号を取得
      this.lastPage = response.data.last_page;
    },

    // 写真投稿をトリガーに写真一覧を更新
    async onPost() {
      // 1ページ目じゃなければ1ページ目へ遷移し画面更新
      if (Number(this.$route.query.page) !== 1) {
        this.$router.push({ path: "", query: { page: 1 } });
      } else {
        await this.fetchPhotos();
      }
    },
  },
  
  // ペジネーションリンク遷移だとインスタンスが使いまわされるためcreatedではなく、
  // ルート情報の変更を監視するwatchで都度対応するページの写真情報の取得処理を呼び出す
  watch: {
    $route: {
      async handler() {
        await this.fetchPhotos();
      },
      immediate: true,
    },
  },
};
</script>

<style scoped>
@media (min-width: 768px) {
  .photos {
    width: 90%;
  }
}

.no-photos {
  margin-bottom: 2rem;
}

.photos__wrapper {
  margin-bottom: 15px;
  margin-top: 15px;
}
</style>
