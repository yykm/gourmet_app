<template>
  <div id="photoList">
    <PhotoForm :shopId="shopId" />

    <Photo :photoId="'aaa'" />
  </div>
</template>

<script>
import Photo from "./../components/Photo.vue";
import PhotoForm from "./../components/PhotoForm.vue";

export default {
  name: "PhotoList",
  components: {
    Photo,
    PhotoForm
  },
  data() {
    return {
      shopId: this.$route.params.id, // 店舗ID
      photoId: null // 写真ID
    };
  },

};
</script>

<style scoped>
</style>
