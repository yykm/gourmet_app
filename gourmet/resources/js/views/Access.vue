<template>
  <div id="access">
    <div class="wrapper mt-5 mb-4">
      <b-container class="bv-example-row">
        <b-row class="flex-column align-items-center">
          <b-col cols="10" md="8"
            ><div class="access__title">
              <p class="shop__address text-center">
                {{ shop.name }}<br />{{ shop.address }}
              </p>
            </div></b-col
          >
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
          <!-- アクセス 電車 -->
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
          <!-- アクセス 車 -->
          <b-col cols="10">
            <div class="transportation text-left my-3">
              <span class="ml-3">車でお越しの方</span>
            </div>
          </b-col>
          <b-col cols="10">
            <div class="text-left mt-3 mb-5">
              <p class="ml-3">
                駐車場&nbsp;-&nbsp;{{ shop.parking }}
              </p>
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
      shop_id: this.$route.params.id, // 店舗ID
      shop: null, // 店舗オブジェクト
      position: null,
      center: null,
      initPosition: null,
    };
  },
  computed: {
    ...mapGetters("App", ["getShop"]),
  },
  // クリックでマーカ位置に画面を中央に戻す
  methods: {
    markerClick($event) {
      this.position = this.center = {
        lat: $event.latLng.lat(),
        lng: $event.latLng.lng(),
      };
    },
    markerMove($event) {
      this.position = {
        lat: $event.latLng.lat(),
        lng: $event.latLng.lng(),
      };
    },
    mapMove($event) {
      this.center = {
        lat: $event.lat(),
        lng: $event.lng(),
      };
    },
  },
  created() {
    // 店舗オブジェクトの取得及び、googleMapの表示位置設定
    this.shop = this.getShop(this.shop_id);
    // 初期位置の設定と保存
    this.center = this.position = this.initPosition = {
      lat: this.shop.lat,
      lng: this.shop.lng,
    };

    // マップ中央位置の取得を遅延させる
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
