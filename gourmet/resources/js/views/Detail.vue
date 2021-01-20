<template>
  <div id="detail">
    <Header :site_name="'Gourmet'"></Header>
    <b-container>
      <!-- 店舗概要表示部分 -->
      <b-row class="text-center bg-light mt-4 info">
        <b-col>店舗概要</b-col>
      </b-row>

      <!-- タブ -->
      <b-tabs
        v-model="tabIndex"
        @activate-tab="onChange"
        :fill="true"
        class="nav-row"
      >
        <b-tab title="店舗詳細"></b-tab>
        <b-tab title="メニュー" disabled></b-tab>
        <b-tab title="写真"></b-tab>
        <b-tab title="口コミ"></b-tab>
        <b-tab title="地図" disabled></b-tab>
      </b-tabs>
{{ this.tabIndex }}
      <!-- コンテンツ -->
      <router-view name="content"></router-view>
    </b-container>
  </div>
</template>

<script>
import Header from "./../components/Header.vue";

export default {
  name: "Detail",
  data(){
    return {
      tabIndex: Number(this.tab)
    };
  },
  components: {
    Header
  },
  props: {
    // 店舗ID
    id: {
      type: String,
      required: true
    },
    tab: {
      type: Number,
      required: true
    }
  },
  // リンク移動
  methods: {
    onChange(newTabIndex, prevTabIndex, bvEvent) {
      // 移動先
      var prefix = "/detail/" + this.id + '/' + this.tab;

      switch (newTabIndex) {
        case 0:
          prefix += "/info";
          break;
        case 1:
          prefix += "/menu";
          break;
        case 2:
          prefix += "/photoList";
          break;
        case 3:
          prefix += "/review";
          break;
        case 4:
          prefix += "/map";
          break;
        default:
          prefix += "/";
      }

      this.$router.push(prefix);
    }
  }
};
</script>

<style scoped>
.info {
  height: 300px;
}

.nav-row {
  margin-right: -15px;
  margin-left: -15px;
}
</style>
