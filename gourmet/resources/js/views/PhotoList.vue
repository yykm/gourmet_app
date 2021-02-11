<template>
  <div id="photoList">
    <div class="wrapper mt-5 mb-4">
      <b-container fluid>
        <div v-if="!photos" class="text-center no-photos">
          <p>まだ投稿された写真がありません。</p>
        </div>
        <b-row v-if="isLogin">
          <b-col>
            <div class="photo__form text-center mb-2">
              <PhotoForm @photoPost="onPost" :shopId="shopId" /></div
          ></b-col>
        </b-row>
        <div v-if="loading" class="loader text-center mt-5">
          <Loader height="5rem" width="5rem" />
        </div>
        <div v-else-if="photos" class="photos py-4 mx-auto">
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
import { ERR } from "./../store/const.js";

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
      shopId: this.$route.params.id, // 店舗ID
      photos: null, // 写真一覧オブジェクトの配列
      loading: null, // ローディング表示フラグ
      lastPage: 1, // ページングの最終ページ番号
    };
  },
  methods: {
    ...mapMutations("Err", ["setCode"]),

    // 写真一覧取得
    async fetchPhotos() {
      // ローディング表示
      this.loading = true;
      // クエリパラメータのページ番号を取得
      const page = Number(
        /^[1-9][0-9]*$/.test(this.$route.query.page)
          ? this.$route.query.page * 1
          : 1
      );
      // shop_idをクエリパラメータに
      const response = await axios
        .get("/api/photos", {
          params: {
            shop_id: this.shopId,
            page,
          },
        })
        .catch((err) => err.response || err);

      // ステ－タスコード200以外エラー
      if (response.status !== ERR.OK) {
        this.setCode("setCode", response.status);
        // ローダー非表示
        this.loading = false;
        return;
      }
      // ローダー非表示
      this.loading = false;
      // レスポンス各値を取得
      this.photos = response.data.data.length === 0 ? null : response.data.data;
      this.lastPage = response.data.last_page;
    },
    // 投稿されたら写真一覧を更新
    async onPost() {
      if (Number(this.$route.query.page) !== 1) {
        this.$router.push({ path: "", query: { page: 1 } });
      } else {
        await this.fetchPhotos();
      }
    },
  },
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
