<template>
  <div id="access">
    <div class="wrapper mt-5 mb-4">
      <div id="map" ref="map"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Access",
  data() {
    return {
      shop_id: this.$route.params.id, // 店舗ID
    };
  },
  methods: {
    initMap() {
      new google.maps.Map(this.$refs.map, {
        zoom: 15,
        center: { lat: 35.662, lng: 139.703 },
      });
    },
  },
  mounted() {
    this.initMap();
  },
};
</script>

<style scoped>
#id {
  height: 400px;
  width: 400px;
}
</style>
