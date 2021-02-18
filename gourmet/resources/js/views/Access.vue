<template>
  <!-- アクセス画面 -->
  <div id="access">
    <div class="wrapper mt-5 mb-4">
      <b-container class="bv-example-row">
        <b-row class="flex-column align-items-center">
          <!-- 店舗住所 -->
          <b-col cols="10" md="8">
            <div>
              <p class="shop__address text-center">
                {{ shop.name }}<br />{{ shop.address }}
              </p>
            </div></b-col
          >

          <!-- Google Map -->
          <b-col cols="10">
            <GmapMap
              :center="center"
              :zoom="15"
              map-type-id="roadmap"
              style="
                width: 550px;
                height: 350px;
                max-height: 100%;
                max-width: 100%;
              "
              class="mx-auto my-4 mb-5"
              @center_changed="delayFunc"
            >
              <!-- 地図上のマーカー -->
              <GmapMarker
                :position="position"
                :clickable="true"
                :draggable="true"
                @click="markerClick"
                @dragend="markerMove"
              />
            </GmapMap>
            <b-button
              @click="center = position = initPosition"
              variant="primary"
              class="px-3 py-2 d-block mx-auto mb-4 mb-md-5"
              pill
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-geo-alt-fill mr-2"
                viewBox="0 0 16 16"
              >
                <path
                  d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"
                /></svg
              >店舗の位置へ戻る</b-button
            ></b-col
          >

          <!-- 交通手段：電車 -->
          <b-col cols="10">
            <div class="transportation text-left my-3">
              <span class="ml-3">電車でお越しの方</span>
            </div>
          </b-col>
          <b-col cols="10">
            <div class="text-left mt-3 mb-5">
              <p class="ml-3">
                最寄り駅&nbsp;-&nbsp;{{ shop.station }}<br />{{ shop.access }}
              </p>
            </div>
          </b-col>

          <!-- 交通手段：車 -->
          <b-col cols="10">
            <div class="transportation text-left my-3">
              <span class="ml-3">車でお越しの方</span>
            </div>
          </b-col>
          <b-col cols="10">
            <div class="text-left mt-3 mb-5">
              <p class="ml-3">駐車場&nbsp;-&nbsp;{{ shop.parking }}</p>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import _ from "lodash";

export default {
  name: "Access",
  data() {
    return {
      shop_id: this.$route.params.shop_id, // 店舗ID
      shop: null, // 店舗情報
      position: null, // マーカーの座標
      initPosition: null, // マーカーの初期位置
      center: null, // 地図の中央座標
    };
  },
  computed: {
    ...mapGetters("App", ["getShop"]),
  },
  methods: {
    // マーカークリックで地図の中央座標をその地点へ設定
    markerClick($event) {
      this.position = this.center = {
        lat: $event.latLng.lat(),
        lng: $event.latLng.lng(),
      };
    },
    // マーカー移動でマーカ―座標を再設定
    markerMove($event) {
      this.position = {
        lat: $event.latLng.lat(),
        lng: $event.latLng.lng(),
      };
    },
    // 地図の中央位置移動で中央座標を再設定
    mapMove($event) {
      this.center = {
        lat: $event.lat(),
        lng: $event.lng(),
      };
    },
  },
  created() {
    // 店舗情報の取得
    this.shop = this.getShop(this.shop_id);

    // 地図の中央座標とマーカーの座標を店舗の座標へ初期設定
    this.center = this.position = this.initPosition = {
      lat: this.shop.lat,
      lng: this.shop.lng,
    };

    // 連続して発生するマップ移動イベントを1500msごとに遅延させるため、遅延関数に設定
    this.delayFunc = _.debounce(this.mapMove, 1500);
  },
};
</script>

<style scoped>
#id {
  height: 400px;
  width: 400px;
}

.shop__address {
  color: #74899afc;
}

.transportation {
  height: 50px;
  width: 100%;
  font-size: 1.3rem;
  position: relative;
  background-color: aliceblue;
  border-radius: 3px;
}

.transportation span {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
}
</style>
