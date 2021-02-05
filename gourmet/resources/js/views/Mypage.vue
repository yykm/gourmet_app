<template>
  <div id="mypage">
    <Header />
    <b-container fluid id="wrapper" class="mt-4">
      <b-row
        class="content__wrapper justify-content-around flex-column flex-lg-row"
      >
        <b-col
          cols="10"
          lg="4"
          xl="3"
          class="bg__white shadow-sm mb-5 mb-lg-0 mb-ml-0"
          ><aside class="account__info">アカウント情報<br>{{ user }}</aside></b-col
        >
        <b-col
          cols="10"
          lg="7"
          xl="8"
          class="d-flex flex-column justify-content-between"
        >
          <b-row>
            <b-col cols="12" class="reserve__info bg__white shadow-sm mb-5"
              >予約</b-col
            >
          </b-row>
          <b-row>
            <b-col cols="12" class="favorite__info bg__white shadow-sm mb-5"
              >お気に入り</b-col
            >
          </b-row>
          <b-row>
            <b-col cols="12" class="review__info bg__white shadow-sm"
              >過去の口コミ投稿</b-col
            >
          </b-row>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";

export default {
  name: "Mypage",
  components: {
    Header,
  },
  data() {
    return {
      user: null,
    };
  },
  methods(){
    
  },
  created() {
    this.user = this.$store.getters['App/getUser'];
  },
};
</script>

<style scoped>
#wrapper {
  max-width: 90vw;
}

.bg__white {
  background-color: #fff;
}

.account__info {
  height: 40vh;
}

.reserve__info,
.favorite__info,
.review__info {
  height: 20vh;
}

@media (max-width: 991.98px) {
  .content__wrapper {
    align-items: center;
  }
}
</style>
